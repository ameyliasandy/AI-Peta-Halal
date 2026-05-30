{{--
  pemilik/toko/form.blade.php
  Halaman daftar usaha baru — khusus Pemilik.
  Tidak ada bagian "Keputusan Admin".
  Extend layout pemilik (sesuaikan dengan layout yang kamu pakai).
--}}
@extends('layouts.pemilik') {{-- ganti dengan layout pemilik kamu --}}
@section('title', 'Daftarkan Usaha Halal')

@push('styles')
<style>
/* ── WRAPPER ── */
.reg-wrap{
    width:100%;
    max-width:680px;
    margin:0 auto;
    padding:18px 14px 120px;
}
.reg-head{text-align:center;margin-bottom:32px}
.reg-head h1{font-size:24px;font-weight:800;color:var(--s9)}
.reg-head p{font-size:14px;color:var(--s4);margin-top:6px;line-height:1.6}

/* ── CARD ── */
.reg-card{background:#fff;border-radius:18px;border:1px solid var(--s2);overflow:hidden;
          box-shadow:0 4px 20px rgba(0,0,0,.05)}

/* ── STEPS (horizontal) ── */
.steps{
    display:flex;
    overflow-x:auto;
    scrollbar-width:none;
    -ms-overflow-style:none;
}

.steps::-webkit-scrollbar{
    display:none;
}
.si{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px 4px;
    border-radius:8px;font-size:12px;font-weight:600;color:var(--s4);cursor:pointer;
    transition:all .15s;white-space:nowrap}
.si.on{background:#fff;color:var(--g);box-shadow:0 1px 4px rgba(0,0,0,.1)}
.si.done{color:var(--g)}
.sn{width:20px;height:20px;border-radius:50%;background:var(--s4);display:flex;
    align-items:center;justify-content:center;font-size:11px;color:#fff;flex-shrink:0}
.si.on .sn,.si.done .sn{background:var(--g)}

/* ── FORM BODY ── */
.reg-body{padding:32px}
.sp{display:none}.sp.on{display:block}

/* ── FOOTER NAVIGASI ── */
.reg-foot{padding:20px 28px;border-top:1px solid var(--s1);
          display:flex;align-items:center;justify-content:space-between;gap:12px}
.reg-foot-info{font-size:12px;color:var(--s4)}

/* Komponen form (sama dengan admin) */
.fsec{margin-bottom:20px}
.ft{font-size:14px;font-weight:700;color:var(--s9);border-bottom:2px solid var(--gl);
    padding-bottom:7px;margin-bottom:14px}
.fg{display:flex;flex-direction:column;gap:5px}
label{font-size:12px;font-weight:600;color:var(--s7)}
.req{color:var(--r);margin-left:2px}
.hint{font-size:11px;color:var(--s4)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
input[type=text],input[type=number],input[type=email],input[type=tel],
input[type=date],input[type=file],select,textarea{
  border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;
  font-family:var(--font);font-size:13px;color:var(--s7);
  outline:none;transition:border-color .15s;width:100%}
input:focus,select:focus,textarea:focus{
  border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.1)}
textarea{resize:vertical;min-height:68px}
.chk-wrap{display:flex;flex-direction:column;gap:7px}
.ci{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;
    border:1.5px solid var(--s2);border-radius:9px;cursor:pointer;transition:all .15s}
.ci:has(input:checked){border-color:var(--g);background:var(--gl)}
.ci input[type=checkbox]{width:15px;height:15px;margin-top:2px;flex-shrink:0;
    accent-color:var(--g);cursor:pointer}
.ct{font-size:13px;line-height:1.4}
.cs{font-size:11px;color:var(--s4);margin-top:1px}
.alert-w{background:#fffbeb;border:1px solid #f59e0b;border-radius:9px;
          padding:10px 14px;font-size:12px;color:#92400e;margin-bottom:12px}

@media (max-width:640px){

    .reg-head h1{
        font-size:20px;
        line-height:1.3;
    }

    .reg-head p{
        font-size:12px;
        line-height:1.5;
        padding:0 6px;
    }

    .reg-card{
        border-radius:16px;
    }

    .reg-body{
        padding:18px;
    }

    .reg-foot{
        padding:16px 18px;
        flex-direction:column;
        align-items:stretch;
    }

    .reg-foot > div:last-child{
        width:100%;
        display:flex;
    }

    .reg-foot button{
        flex:1;
        justify-content:center;
    }

    .g2,
    .g3{
        grid-template-columns:1fr;
    }

    .si{
        min-width:130px;
        padding:12px 10px;
    }
    .sp{
    display:none;
    padding-top:10px;
    }

    .sp.on{
        display:block;
    }
    .steps{
    display:flex;
    overflow-x:auto;
    scrollbar-width:none;
    -ms-overflow-style:none;
    padding:14px 14px 0;
    margin-bottom:10px;
    }
}

</style>
@endpush

@section('content')
<div class="reg-wrap">

  {{-- Header --}}
  <div class="reg-head">
    <h1>Daftarkan Usaha Halal Anda</h1>
    <p>Lengkapi formulir berikut untuk mendaftarkan usaha ke Peta Halal Batam.<br>
       Data Anda akan ditinjau oleh tim kami dalam 1–3 hari kerja.</p>
  </div>

  <div class="reg-card">

    {{-- Step indicator --}}
    <div class="steps">
      <div class="si on" id="s1" onclick="gS(1)"><div class="sn">1</div>Info Dasar</div>
      <div class="si"    id="s2" onclick="gS(2)"><div class="sn">2</div>Lokasi & Kontak</div>
      <div class="si"    id="s3" onclick="gS(3)"><div class="sn">3</div>Klaim Halal</div>
      <div class="si"    id="s4" onclick="gS(4)"><div class="sn">4</div>Kirim</div>
    </div>

    {{-- Form --}}
    <div class="reg-body">
      <form id="restoranForm" method="POST" action="{{ route('pemilik.toko.store') }}" enctype="multipart/form-data">

        @csrf

        {{-- STEP 1 --}}
        <div class="sp on" id="p1">
          @include('form_nambah_usaha.info_dasar', ['isAdmin' => false])
        </div>

        {{-- STEP 2 --}}
        <div class="sp" id="p2">
          @include('form_nambah_usaha.lokasi', ['isAdmin' => false])
        </div>

        {{-- STEP 3 — versi pemilik (tanpa keputusan admin) --}}
        <div class="sp" id="p3">
          @include('form_nambah_usaha.pemilik_dokumen', ['isAdmin' => false])
        </div>

        {{-- STEP 4 --}}
        <div class="sp" id="p4">
          @include('form_nambah_usaha.review', ['isAdmin' => false])
        </div>

      </form>
    </div>

    {{-- Navigasi --}}
    <div class="reg-foot">
      <div class="reg-foot-info">Langkah <strong id="stepLabel">1</strong> dari 4</div>
      <div style="display:flex;gap:8px">
        <button type="button" class="btn btn-outline" id="btnPrev" onclick="prevS()" style="display:none">← Kembali</button>
        <button type="button" class="btn btn-primary" id="btnNext" onclick="nextS()">Selanjutnya →</button>
        <button
          type="button"
          class="btn btn-primary"
          id="btnSave"
          style="display:none"
          onclick="event.preventDefault(); saveRestoran();">

          <span id="saveL">Kirim Pendaftaran</span>
          <span id="saveS" class="spinner" style="display:none"></span>
      </button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
window.FORM_IS_ADMIN = false;
window.FORM_EDIT_ID  = null;

async function saveRestoran(){

    const form = document.getElementById('restoranForm');

    if(!form){
        alert('Form tidak ditemukan');
        return;
    }

    const btnSave = document.getElementById('btnSave');
    const saveL   = document.getElementById('saveL');
    const saveS   = document.getElementById('saveS');

    try{

        // loading state
        btnSave.disabled = true;

        if(saveL) saveL.innerText = 'Mengirim...';
        if(saveS) saveS.style.display = 'inline-block';

        const formData = new FormData(form);

        const response = await fetch("{{ route('pemilik.toko.store') }}",{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),

                'Accept':'application/json'
            },
            body:formData
        });

        const result = await response.json();

        if(response.ok){

            if(typeof showToast === 'function'){
                showToast(
                    result.message ?? 'Usaha berhasil didaftarkan',
                    'success'
                );
            }else{
                alert(result.message ?? 'Usaha berhasil didaftarkan');
            }

            setTimeout(() => {
                window.location.href =
                    "{{ route('pemilik.toko.index') }}";
            },1200);

        }else{

            console.log(result);

            let errMsg = 'Terjadi kesalahan';

            if(result.errors){

                errMsg = Object.values(result.errors)
                    .flat()
                    .join('\n');

            }else if(result.message){

                errMsg = result.message;
            }

            if(typeof showToast === 'function'){
                showToast(errMsg,'error');
            }else{
                alert(errMsg);
            }
        }

    }catch(err){

        console.error(err);

        if(typeof showToast === 'function'){
            showToast('Server error','error');
        }else{
            alert('Server error');
        }

    }finally{

        btnSave.disabled = false;

        if(saveL) saveL.innerText = 'Kirim Pendaftaran';
        if(saveS) saveS.style.display = 'none';
    }
}

</script>
@include('form_nambah_usaha._form_scripts')
<script>
// Update label "Langkah X dari 4" saat step berubah
const _origGS = gS;
gS = function(n) {
  _origGS(n);
  const lbl = document.getElementById('stepLabel');
  if (lbl) lbl.textContent = n;
};

// Inisialisasi
resetRestoranForm();
</script>
@endpush
