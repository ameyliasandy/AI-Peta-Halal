<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Admin') — Peta Halal</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --g:#1a9e5c;--gd:#15834c;--gl:#e8f7ef;--gm:#d0f0e0;
  --r:#e53e3e;--y:#d69e2e;--b:#3182ce;
  --s0:#f8fafc;--s1:#f1f5f9;--s2:#e2e8f0;--s4:#94a3b8;--s6:#475569;--s7:#334155;--s9:#0f172a;
  --sw:220px;--font:'Plus Jakarta Sans',sans-serif;
}
body{font-family:var(--font);background:var(--s0);color:var(--s7);display:flex;min-height:100vh}

/* SIDEBAR */
.sidebar{width:var(--sw);background:#fff;border-right:1px solid var(--s2);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100}
.sidebar-logo{padding:24px 20px 20px;font-size:28px;font-weight:800;color:var(--g);line-height:1.1;letter-spacing:-1px;border-bottom:1px solid var(--s1)}
.sidebar-nav{flex:1;padding:16px 12px;display:flex;flex-direction:column;gap:4px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;font-size:14px;font-weight:500;color:var(--s6);text-decoration:none;transition:all .15s}
.nav-item:hover{background:var(--s1);color:var(--s9)}
.nav-item.active{background:var(--gl);color:var(--g);font-weight:600}
.nav-item svg{width:18px;height:18px;flex-shrink:0}
.sidebar-foot{padding:16px 12px;border-top:1px solid var(--s1);font-size:12px;color:var(--s4)}

/* MAIN */
.main{margin-left:var(--sw);flex:1;display:flex;flex-direction:column;min-height:100vh}

/* TOPBAR */
.topbar{background:#fff;border-bottom:1px solid var(--s2);padding:0 32px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.tb-search{display:flex;align-items:center;gap:8px;background:var(--s1);border-radius:10px;padding:8px 14px;width:300px}
.tb-search input{border:none;background:transparent;font-family:var(--font);font-size:14px;color:var(--s7);outline:none;width:100%}
.tb-search input::placeholder{color:var(--s4)}
.tb-right{display:flex;align-items:center;gap:12px;font-size:14px;font-weight:500;color:var(--s6)}
.avatar{width:36px;height:36px;border-radius:50%;background:var(--gm);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:var(--g);overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}

/* PAGE */
.page{padding:32px;flex:1}

/* BTN */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;font-family:var(--font);font-size:14px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
.btn-primary{background:var(--g);color:#fff}.btn-primary:hover{background:var(--gd)}
.btn-outline{background:#fff;color:var(--s7);border:1.5px solid var(--s2)}.btn-outline:hover{border-color:var(--s4)}
.btn-sm{padding:5px 10px;font-size:12px;border-radius:7px}
.btn-danger{background:var(--r);color:#fff}.btn-danger:hover{opacity:.88}

/* MODAL */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);backdrop-filter:blur(4px);z-index:999;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:20px;width:92%;max-width:780px;max-height:92vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);animation:mIn .2s ease;display:flex;flex-direction:column}
@keyframes mIn{from{transform:translateY(14px) scale(.97);opacity:0}to{transform:none;opacity:1}}
.modal-head{padding:24px 28px 16px;display:flex;align-items:flex-start;justify-content:space-between;border-bottom:1px solid var(--s1);position:sticky;top:0;background:#fff;z-index:2;border-radius:20px 20px 0 0}
.modal-title{font-size:18px;font-weight:700;color:var(--s9)}
.modal-sub{font-size:12px;color:var(--s4);margin-top:3px}
.modal-x{width:34px;height:34px;border-radius:9px;border:1.5px solid var(--s2);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;font-size:18px;color:var(--s4)}
.modal-x:hover{background:var(--s1);color:var(--s7)}
.modal-body{padding:24px 28px;flex:1;overflow-y:auto}
.modal-foot{padding:16px 28px;border-top:1px solid var(--s1);display:flex;justify-content:flex-end;gap:10px;position:sticky;bottom:0;background:#fff;border-radius:0 0 20px 20px}

/* STEPS */
.steps{display:flex;background:var(--s1);border-radius:12px;padding:4px;margin-bottom:24px;gap:2px}
.step-it{flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:8px 4px;border-radius:9px;font-size:12px;font-weight:600;color:var(--s4);cursor:pointer;transition:all .15s;white-space:nowrap}
.step-it.active{background:#fff;color:var(--g);box-shadow:0 1px 4px rgba(0,0,0,.1)}
.step-it.done{color:var(--g)}
.step-n{width:20px;height:20px;border-radius:50%;background:var(--s4);display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;flex-shrink:0}
.step-it.active .step-n,.step-it.done .step-n{background:var(--g)}
.step-panel{display:none}.step-panel.active{display:block}

/* FORM */
.fsec{margin-bottom:22px}
.fsec-title{font-size:14px;font-weight:700;color:var(--s9);border-bottom:2px solid var(--gl);padding-bottom:7px;margin-bottom:14px}
.fgrid{display:grid;gap:13px}
.cols2{grid-template-columns:1fr 1fr}
.cols3{grid-template-columns:1fr 1fr 1fr}
.fg{display:flex;flex-direction:column;gap:5px}
.fg.full{grid-column:1/-1}
label{font-size:12px;font-weight:600;color:var(--s7)}
label .req{color:var(--r);margin-left:2px}
input[type=text],input[type=number],input[type=email],input[type=tel],input[type=date],input[type=file],select,textarea{border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;font-family:var(--font);font-size:13px;color:var(--s7);outline:none;transition:border-color .15s;width:100%}
input:focus,select:focus,textarea:focus{border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.1)}
textarea{resize:vertical;min-height:72px}
.hint{font-size:11px;color:var(--s4)}

/* CHECKLIST */
.checklist{display:flex;flex-direction:column;gap:7px}
.chk-item{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border:1.5px solid var(--s2);border-radius:9px;cursor:pointer;transition:all .15s}
.chk-item:has(input:checked){border-color:var(--g);background:var(--gl)}
.chk-item input[type=checkbox]{width:15px;height:15px;margin-top:2px;flex-shrink:0;accent-color:var(--g);cursor:pointer}
.chk-text{font-size:13px;line-height:1.4}
.chk-sub{font-size:11px;color:var(--s4);margin-top:1px}
.alert-w{background:#fffbeb;border:1px solid #f59e0b;border-radius:9px;padding:11px 14px;font-size:12px;color:#92400e;margin-bottom:12px}

/* TOAST */
#toast{position:fixed;bottom:24px;right:24px;background:var(--s9);color:#fff;padding:11px 18px;border-radius:11px;font-size:14px;font-weight:500;box-shadow:0 8px 24px rgba(0,0,0,.2);z-index:9999;display:none}
#toast.show{display:block;animation:sUp .2s ease}
#toast.success{background:var(--g)}
#toast.error{background:var(--r)}
@keyframes sUp{from{transform:translateY(10px);opacity:0}to{transform:none;opacity:1}}

.spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:sp .6s linear infinite}
@keyframes sp{to{transform:rotate(360deg)}}
</style>
@stack('styles')
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-logo"><span>Pet</span><span>ha.</span></div>
  <nav class="sidebar-nav">

  <!-- Dashboard -->
  <a href="{{ route('admin.index') }}"
     class="nav-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <rect x="3" y="3" width="7" height="7"/>
      <rect x="14" y="3" width="7" height="7"/>
      <rect x="14" y="14" width="7" height="7"/>
      <rect x="3" y="14" width="7" height="7"/>
    </svg>
    Dashboard
  </a>

  <!-- List Usaha -->
  <a href="{{ route('admin.restoran.list') }}"
     class="nav-item {{ request()->routeIs('admin.restoran.*') ? 'active' : '' }}">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <line x1="8" y1="6" x2="21" y2="6"/>
      <line x1="8" y1="12" x2="21" y2="12"/>
      <line x1="8" y1="18" x2="21" y2="18"/>
      <line x1="3" y1="6" x2="3.01" y2="6"/>
      <line x1="3" y1="12" x2="3.01" y2="12"/>
      <line x1="3" y1="18" x2="3.01" y2="18"/>
    </svg>
    List Usaha Halal
  </a>

</nav>
  <div class="sidebar-foot">v1.0 · Peta Halal</div>
</aside>

<div class="main">
  <header class="topbar">
    <div class="tb-search">
      <svg width="15" height="15" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Search here">
    </div>
    <div class="tb-right">
      <span>Hello, {{ session('admin')?->nama ?? 'Admin' }}</span>
      <div class="avatar">{{ strtoupper(substr(session('admin')?->nama ?? 'A', 0, 1)) }}</div>
    </div>
  </header>

<main class="page">
  @yield('content')
</main>
</div>

<div id="toast"></div>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
function showToast(msg, type='success'){
  const t=document.getElementById('toast');
  t.textContent=msg; t.className='show '+type;
  setTimeout(()=>t.className='',3200);
}
async function apiFetch(url,opts={}){
  const res=await fetch(url,{
    headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json',...(opts.headers??{})},
    ...opts
  });
  return res.json();
}
</script>
@stack('scripts')
</body>
</html>