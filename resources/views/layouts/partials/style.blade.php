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

.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 1000;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.modal-overlay.open {
  display: flex;
}

.modal {
  background: #fff;
  border-radius: 18px;
  width: 100%;
  max-width: 680px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,.15);
}

.modal-head {
  padding: 22px 24px 18px;
  border-bottom: 1px solid var(--s2);
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
  flex-shrink: 0;
}

.modal-title {
  font-size: 17px;
  font-weight: 800;
  color: var(--s9);
}

.modal-sub {
  font-size: 12px;
  color: var(--s4);
  margin-top: 3px;
}

.modal-x {
  border: none;
  background: var(--s1);
  width: 30px;
  height: 30px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  color: var(--s6);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-x:hover { background: var(--s2); }

.modal-body {
  padding: 22px 24px;
  overflow-y: auto;
  flex: 1;
}

.modal-foot {
  padding: 16px 24px;
  border-top: 1px solid var(--s2);
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  flex-shrink: 0;
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