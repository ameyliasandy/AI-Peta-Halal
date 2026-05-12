<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use App\Models\User;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $stats = $this->service->getStats();
        $verifikasi = $this->service->getVerifikasiList();

        return view('admin.index', compact('stats', 'verifikasi'));
    }
}
?>