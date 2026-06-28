<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1D9E75">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <title>@yield('title', 'Petha – Pemilik Usaha')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    /* Reset & variabel */
    *,*::before,*::after{box-sizing:border-box}

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
        --s5:#64748b;
        --s6:#475569;
        --s7:#334155;
        --s9:#0f172a;
        --font:'Plus Jakarta Sans',sans-serif;
    }

    html{scroll-behavior:smooth;-webkit-tap-highlight-color:transparent}
    body{overflow-x:hidden;background:#f8faf9;font-family:var(--font)}

    .no-scrollbar::-webkit-scrollbar{display:none}
    .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}

    /* Tombol */
    .btn{
        display:inline-flex;align-items:center;justify-content:center;gap:6px;
        padding:10px 16px;border-radius:12px;border:none;
        font-size:14px;font-weight:600;font-family:var(--font);
        cursor:pointer;transition:.2s;text-decoration:none;
    }
    .btn-primary{background:var(--g);color:#fff}
    .btn-primary:hover{background:var(--gd)}
    .btn-outline{background:#fff;border:1.5px solid var(--s2);color:var(--s7)}
    .btn-outline:hover{border-color:var(--s4)}
    .btn-sm{padding:6px 12px;font-size:13px;border-radius:9px}
    .btn-danger{background:var(--r);color:#fff}
    .btn-danger:hover{background:#c53030}

    /* Badge */
    .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
    .bv{background:#dcfce7;color:#15803d}
    .bp{background:#fef9c3;color:#854d0e}
    .br{background:#fee2e2;color:#b91c1c}
    .bn{background:var(--s1);color:var(--s5)}

    /* Spinner */
    .spinner{
        width:16px;height:16px;
        border:2px solid rgba(255,255,255,.35);
        border-top-color:#fff;
        border-radius:50%;
        animation:spin .7s linear infinite;
    }
    @keyframes spin{to{transform:rotate(360deg)}}

    /* MODAL */
    .modal-overlay{
        display:none;
        position:fixed;inset:0;
        background:rgba(0,0,0,.5);
        z-index:1000;
        align-items:center;
        justify-content:center;
        padding:16px;
    }
    .modal-overlay.open{display:flex}

    .modal{
        background:#fff;
        border-radius:18px;
        width:100%;
        max-width:560px;
        max-height:92vh;
        display:flex;
        flex-direction:column;
        box-shadow:0 24px 60px rgba(0,0,0,.18);
        animation:modalIn .2s ease;
    }
    @keyframes modalIn{
        from{opacity:0;transform:translateY(12px) scale(.98)}
        to{opacity:1;transform:none}
    }

    .modal-head{
        padding:20px 22px 16px;
        border-bottom:1px solid var(--s2);
        display:flex;align-items:flex-start;justify-content:space-between;gap:12px;
        flex-shrink:0;
    }
    .modal-title{font-size:16px;font-weight:800;color:var(--s9)}
    .modal-sub{font-size:12px;color:var(--s4);margin-top:3px}

    .modal-x{
        border:none;background:var(--s1);
        width:30px;height:30px;border-radius:8px;
        cursor:pointer;font-size:14px;color:var(--s6);
        flex-shrink:0;display:flex;align-items:center;justify-content:center;
        transition:.15s;
    }
    .modal-x:hover{background:var(--s2)}

    .modal-body{
        padding:20px 22px;
        overflow-y:auto;
        flex:1;
    }

    .modal-foot{
        padding:14px 22px;
        border-top:1px solid var(--s2);
        display:flex;justify-content:flex-end;gap:8px;
        flex-shrink:0;
    }

    /* Form dalam modal */
    .g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .fg{display:flex;flex-direction:column;gap:5px}
    label{font-size:12px;font-weight:600;color:var(--s7)}
    .req{color:var(--r);margin-left:2px}

    input[type=text],input[type=number],input[type=email],
    input[type=tel],input[type=date],input[type=file],
    select,textarea{
        border:1.5px solid var(--s2);border-radius:9px;
        padding:8px 11px;
        font-family:var(--font);font-size:13px;color:var(--s7);
        outline:none;transition:border-color .15s;
        width:100%;background:#fff;
    }
    input:focus,select:focus,textarea:focus{
        border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.08)
    }
    textarea{resize:vertical;min-height:66px}

    /* Dropdown Profile */
    .profile-dropdown {
        position: relative;
        display: inline-block;
    }
    .profile-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px 5px 5px;
        border-radius: 999px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
        user-select: none;
    }
    .profile-pill:hover {
        border-color: #1D9E75;
        box-shadow: 0 0 0 3px rgba(29,158,117,.08);
    }
    .profile-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg,#1D9E75,#085041);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }
    .profile-name {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        max-width: 90px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .profile-chevron {
        color: #94a3b8;
        transition: transform .2s;
        flex-shrink: 0;
    }
    .profile-pill.open .profile-chevron {
        transform: rotate(180deg);
    }
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 12px 32px rgba(0,0,0,.12);
        min-width: 180px;
        z-index: 200;
        overflow: hidden;
        animation: dropIn .15s ease;
        display: none;
    }
    .dropdown-menu.open {
        display: block;
    }
    @keyframes dropIn {
        from { opacity:0; transform:translateY(-6px) scale(.97); }
        to   { opacity:1; transform:none; }
    }
    .dropdown-header {
        padding: 12px 14px 8px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dropdown-header p {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin: 0 0 1px;
    }
    .dropdown-header span {
        font-size: 11px;
        color: #94a3b8;
    }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: background .12s;
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        font-family: var(--font);
    }
    .dropdown-item:hover {
        background: #f8faf9;
        color: #1D9E75;
    }
    .dropdown-item.danger {
        color: #dc2626;
    }
    .dropdown-item.danger:hover {
        background: #fff5f5;
        color: #dc2626;
    }
    .dropdown-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 2px 0;
    }
    </style>

    @stack('styles')
</head>

<body class="bg-[#f8faf9] text-gray-900 antialiased">

<div class="w-full min-h-screen bg-[#f8faf9]">

    {{-- FLASH --}}
    @if(session('success'))
        <div id="flash-success" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-sm">
            <div class="bg-white border border-green-200 rounded-2xl px-4 py-3 shadow">
                <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-error" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-sm">
            <div class="bg-white border border-red-200 rounded-2xl px-4 py-3 shadow">
                <p class="text-sm font-medium text-gray-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- TOPBAR --}}
    <div class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-gray-100">
        <div class="w-full flex items-center justify-between px-4 py-3">

            {{-- Logo --}}
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-9 h-9 rounded-2xl bg-[#1D9E75] flex items-center justify-center flex-shrink-0 shadow-sm">
                    <span class="text-white font-black text-sm">P</span>
                </div>
                <div class="min-w-0">
                    <h1 class="text-[18px] leading-tight font-extrabold text-[#1D9E75] truncate">Petha</h1>
                    <p class="text-[11px] text-gray-400 leading-none truncate">Halal Food Platform</p>
                </div>
            </div>

            {{-- Profile Pill Dropdown --}}
            <div class="profile-dropdown flex-shrink-0" id="profileDropdown">
                <div class="profile-pill" id="profilePill" onclick="toggleProfileDropdown()">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="profile-name hidden sm:block">{{ Auth::user()->name ?? 'Pemilik' }}</span>
                    {{-- Chevron --}}
                    <svg class="profile-chevron" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </div>

                {{-- Dropdown Menu --}}
                <div class="dropdown-menu" id="profileMenu">
                    {{-- Header info user --}}
                    <div class="dropdown-header">
                        <p>{{ Auth::user()->name ?? 'Pemilik' }}</p>
                        <span>{{ Auth::user()->email ?? '' }}</span>
                    </div>

                    {{-- Edit Profil --}}
                    <a href="{{ route('profile.index') }}" class="dropdown-item">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Edit Profil
                    </a>

                    <div class="dropdown-divider"></div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- NAV TABS --}}
    <div class="flex gap-2 px-4 py-3 overflow-x-auto border-b border-gray-100 bg-white no-scrollbar">
        <a href="{{ route('pemilik.dashboard') }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-medium
                  {{ request()->routeIs('pemilik.dashboard') ? 'bg-[#1D9E75] text-white' : 'border border-gray-200 text-gray-500 bg-white' }}">
            Dashboard
        </a>
        <a href="{{ route('pemilik.toko.index') }}"
           class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-medium
                  {{ request()->routeIs('pemilik.toko.*') ? 'bg-[#1D9E75] text-white' : 'border border-gray-200 text-gray-500 bg-white' }}">
            Daftar Usaha
        </a>
    </div>

    {{-- CONTENT --}}
    <main class="pb-28 px-4 pt-4">
        @yield('content')
    </main>

</div>

{{-- TOAST --}}
<div id="toast"
     style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%);
            background:#111827;color:#fff;padding:11px 20px;border-radius:12px;
            font-size:13px;font-weight:600;z-index:9999;display:none;
            box-shadow:0 8px 20px rgba(0,0,0,.18);white-space:nowrap">
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

/* Flash auto-hide */
setTimeout(() => {
    ['flash-success','flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.remove();
    });
}, 3000);

/* Toast */
function showToast(message, type = 'success') {
    const t = document.getElementById('toast');
    t.innerText = message;
    t.style.background = type === 'error' ? '#dc2626' : '#16a34a';
    t.style.display = 'block';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { t.style.display = 'none'; }, 3000);
}

/* apiFetch helper */
async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            ...(opts.headers ?? {}),
        },
        ...opts,
    });
    return res.json();
}

/* Profile Dropdown */
function toggleProfileDropdown() {
    const pill = document.getElementById('profilePill');
    const menu = document.getElementById('profileMenu');
    const isOpen = menu.classList.contains('open');

    if (isOpen) {
        menu.classList.remove('open');
        pill.classList.remove('open');
    } else {
        menu.classList.add('open');
        pill.classList.add('open');
    }
}

/* Tutup dropdown jika klik di luar */
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('profileDropdown');
    const menu     = document.getElementById('profileMenu');
    const pill     = document.getElementById('profilePill');
    if (dropdown && !dropdown.contains(e.target)) {
        menu.classList.remove('open');
        pill.classList.remove('open');
    }
});

/* Tutup dropdown jika tekan Escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('profileMenu')?.classList.remove('open');
        document.getElementById('profilePill')?.classList.remove('open');
    }
});
</script>

@stack('scripts')

</body>
</html>