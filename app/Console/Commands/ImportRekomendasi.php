<?php

namespace App\Console\Commands;

use App\Models\Rekomendasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRekomendasi extends Command
{
    protected $signature = 'import:rekomendasi 
                            {file : Path ke file CSV} 
                            {--truncate : Kosongkan tabel sebelum import}';
    
    protected $description = 'Import rekomendasi dari CSV hasil Colab';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("❌ File tidak ditemukan: {$filePath}");
            return 1;
        }

        if ($this->option('truncate')) {
            DB::table('rekomendasi')->truncate();
            $this->info('✅ Tabel rekomendasi dikosongkan');
        }

        // Baca CSV
        $csv = array_map('str_getcsv', file($filePath));
        $header = array_shift($csv);
        
        $this->info("📊 Memproses " . count($csv) . " baris data...");
        
        $bar = $this->output->createProgressBar(count($csv));
        $bar->start();

        $chunks = [];
        $success = 0;
        $failed = 0;

        foreach ($csv as $row) {
            try {
                $data = array_combine($header, $row);
                
                $chunks[] = [
                    'user_id' => (int) $data['user_id'],
                    'id_restoran' => (int) $data['id_restoran'],
                    'score' => (float) $data['score'],
                    'rank' => (int) $data['rank'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($chunks) >= 100) {
                    DB::table('rekomendasi')->insertOrIgnore($chunks);
                    $success += count($chunks);
                    $chunks = [];
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $failed++;
                $this->error("\n❌ Error: " . $e->getMessage());
            }
        }

        // Insert sisa data
        if (!empty($chunks)) {
            DB::table('rekomendasi')->insertOrIgnore($chunks);
            $success += count($chunks);
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ Import selesai!");
        $this->info("   ✅ Berhasil: {$success} baris");
        if ($failed > 0) {
            $this->warn("   ⚠️  Gagal: {$failed} baris");
        }

        return 0;
    }
}