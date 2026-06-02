<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title','Admin') — Peta Halal</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@include('layouts.partials.style')

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