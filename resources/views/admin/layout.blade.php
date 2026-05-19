<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title','Admin') — Peta Halal</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*,*::before,*::after{
  box-sizing:border-box;
  margin:0;
  padding:0;
}

:root{
  --g:#1a9e5c;
  --gd:#15834c;
  --gl:#e8f7ef;
  --gm:#d0f0e0;

  --r:#e53e3e;

  --s0:#f8fafc;
  --s1:#f1f5f9;
  --s2:#e2e8f0;
  --s4:#94a3b8;
  --s6:#475569;
  --s7:#334155;
  --s9:#0f172a;

  --sw:220px;
  --font:'Plus Jakarta Sans',sans-serif;
}

body{
  font-family:var(--font);
  background:var(--s0);
  color:var(--s7);
  display:flex;
  min-height:100vh;
}

/* SIDEBAR */
.sidebar{
  width:var(--sw);
  background:#fff;
  border-right:1px solid var(--s2);
  display:flex;
  flex-direction:column;
  position:fixed;
  top:0;
  left:0;
  bottom:0;
  z-index:100;
}

.sidebar-logo{
  padding:24px 20px 20px;
  font-size:28px;
  font-weight:800;
  color:var(--g);
  line-height:1.1;
  letter-spacing:-1px;
  border-bottom:1px solid var(--s1);
}

.sidebar-nav{
  flex:1;
  padding:16px 12px;
  display:flex;
  flex-direction:column;
  gap:4px;
}

.nav-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:12px 14px;
  border-radius:12px;
  font-size:14px;
  font-weight:500;
  color:var(--s6);
  text-decoration:none;
  transition:.2s;
}

.nav-item svg{
  width:18px;
  height:18px;
  flex-shrink:0;
}

.nav-item:hover{
  background:var(--s1);
  color:var(--s9);
}

.nav-item.active{
  background:var(--gl);
  color:var(--g);
  font-weight:700;
}

.sidebar-foot{
  padding:16px 14px;
  border-top:1px solid var(--s1);
  font-size:12px;
  color:var(--s4);
}

/* MAIN */
.main{
  margin-left:var(--sw);
  flex:1;
  min-height:100vh;
  display:flex;
  flex-direction:column;
}

/* TOPBAR */
.topbar{
  display:flex;
  justify-content:flex-end;
  align-items:center;
  padding:20px 32px 0;
  background:transparent;
}

/* PROFILE */
.profile-menu{
  position:relative;
}

.profile-btn{
  display:flex;
  align-items:center;
  gap:12px;
  border:none;
  background:#fff;
  padding:10px 14px;
  border-radius:14px;
  cursor:pointer;
  font-family:var(--font);
  font-size:14px;
  font-weight:600;
  color:var(--s7);
  box-shadow:0 4px 14px rgba(0,0,0,.06);
  transition:.2s;
}

.profile-btn:hover{
  transform:translateY(-1px);
}

.avatar{
  width:36px;
  height:36px;
  border-radius:50%;
  background:var(--gm);
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
  color:var(--g);
  flex-shrink:0;
}

.dropdown{
  position:absolute;
  top:115%;
  right:0;
  width:150px;
  background:#fff;
  border:1px solid var(--s2);
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  display:none;
  z-index:999;
}

.dropdown.show{
  display:block;
}

.dropdown-item{
  width:100%;
  border:none;
  background:#fff;
  padding:12px 14px;
  text-align:left;
  cursor:pointer;
  font-family:var(--font);
  font-size:14px;
  color:var(--r);
  transition:.15s;
}

.dropdown-item:hover{
  background:var(--s1);
}

/* PAGE */
.page{
  padding:32px;
  flex:1;
}

.page-content{
  width:100%;
}

/* BIAR MODAL TIDAK MUNCUL LANGSUNG */
.modal-overlay{
  display:none;
}

.modal-overlay.open{
  display:flex;
}

/* TABLE */
.table-wrap{
  background:#fff;
  border-radius:18px;
  overflow:hidden;
  border:1px solid var(--s2);
  margin-top:20px;
}

table{
  width:100%;
  border-collapse:collapse;
}

th{
  background:var(--s1);
  padding:14px;
  font-size:13px;
  text-align:left;
  color:var(--s6);
}

td{
  padding:14px;
  border-top:1px solid var(--s1);
  font-size:13px;
  vertical-align:top;
}

tr:hover{
  background:#fafafa;
}

/* FILTER */
.filter-bar{
  display:flex;
  gap:12px;
  margin:20px 0;
  flex-wrap:wrap;
}

.filter-input,
.filter-select{
  height:42px;
  border:1.5px solid var(--s2);
  border-radius:12px;
  padding:0 14px;
  background:#fff;
  font-family:var(--font);
}

.filter-input{
  min-width:240px;
}

/* STATS */
.stats-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:16px;
  margin:24px 0;
}

.stat-box{
  background:#fff;
  border:1px solid var(--s2);
  border-radius:18px;
  padding:20px;
}

.stat-num{
  font-size:28px;
  font-weight:800;
  color:var(--s9);
}

.stat-label{
  font-size:13px;
  color:var(--s4);
  margin-top:4px;
}

/* PAGE HEADER */
.page-head{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:20px;
  margin-bottom:24px;
}

.page-title{
  font-size:28px;
  font-weight:800;
  color:var(--s9);
}

.page-sub{
  margin-top:4px;
  font-size:14px;
  color:var(--s4);
}

/* ACTION */
.action-group{
  display:flex;
  gap:8px;
  flex-wrap:wrap;
}

/* BUTTON */
.btn{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:8px 16px;
  border-radius:10px;
  font-family:var(--font);
  font-size:14px;
  font-weight:600;
  cursor:pointer;
  border:none;
  text-decoration:none;
  transition:.2s;
}

.btn-primary{
  background:var(--g);
  color:#fff;
}

.btn-primary:hover{
  background:var(--gd);
}

/* TOAST */
#toast{
  position:fixed;
  bottom:24px;
  right:24px;
  background:var(--s9);
  color:#fff;
  padding:11px 18px;
  border-radius:11px;
  font-size:14px;
  font-weight:500;
  box-shadow:0 8px 24px rgba(0,0,0,.2);
  z-index:9999;
  display:none;
}

#toast.show{
  display:block;
}

#toast.success{
  background:var(--g);
}

#toast.error{
  background:var(--r);
}
</style>

@stack('styles')
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">

  <div class="sidebar-logo">
    <span>Pet</span><span>ha.</span>
  </div>

  <nav class="sidebar-nav">

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

  <div class="sidebar-foot">
    v1.0 · Peta Halal
  </div>

</aside>

<!-- MAIN -->
<div class="main">

  <!-- TOPBAR -->
  <header class="topbar">

    <div class="profile-menu">

      <button type="button"
              class="profile-btn"
              onclick="toggleDropdown(event)">

        <span>
          Hello, {{ session('admin')?->nama ?? 'Admin' }}
        </span>

        <div class="avatar">
          {{ strtoupper(substr(session('admin')?->nama ?? 'A', 0, 1)) }}
        </div>

      </button>

      <div class="dropdown" id="dropdownMenu">

        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button type="submit" class="dropdown-item">
            Logout
          </button>

        </form>

      </div>

    </div>

  </header>

  <!-- CONTENT -->
<main class="page">
    <div class="page-content">
        @yield('content')
    </div>
</main>

</div>

<!-- TOAST -->
<div id="toast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

function showToast(msg, type='success'){
  const t = document.getElementById('toast');

  t.textContent = msg;
  t.className = 'show ' + type;

  setTimeout(() => {
    t.className = '';
  }, 3200);
}

async function apiFetch(url, opts = {}){
  const res = await fetch(url,{
    headers:{
      'X-CSRF-TOKEN': CSRF,
      'Accept':'application/json',
      ...(opts.headers ?? {})
    },
    ...opts
  });

  return res.json();
}

/* DROPDOWN */
function toggleDropdown(event){
  event.stopPropagation();

  document
    .getElementById('dropdownMenu')
    .classList.toggle('show');
}

window.addEventListener('click', function(e){

  if(!e.target.closest('.profile-menu')){
    document
      .getElementById('dropdownMenu')
      .classList.remove('show');
  }

});
</script>

@stack('scripts')

</body>
</html>