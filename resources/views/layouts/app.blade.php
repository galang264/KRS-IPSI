<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem KRS' }} | Tugas-3</title>
    <style>
        :root { --ink:#1e293b; --muted:#64748b; --line:#dbe3ed; --bg:#f4f7fb; --brand:#153e75; --brand2:#245ea8; --danger:#a32020; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter,Segoe UI,Arial,sans-serif; color:var(--ink); background:var(--bg); }
        header { background:linear-gradient(120deg,#12325b,#2462a1); color:#fff; padding:18px 5vw; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; }
        header a { color:#fff; text-decoration:none; }
        .brand { font-size:1.15rem; font-weight:800; letter-spacing:.2px; }
        .subbrand { color:#dbeafe; font-size:.86rem; margin-top:3px; }
        nav { display:flex; gap:8px; flex-wrap:wrap; }
        nav a { padding:8px 11px; border-radius:8px; background:rgba(255,255,255,.11); font-size:.9rem; }
        nav a:hover { background:rgba(255,255,255,.2); }
        main { width:min(1180px,92vw); margin:28px auto 52px; }
        .page-head { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; flex-wrap:wrap; margin-bottom:20px; }
        h1 { margin:0 0 6px; font-size:1.75rem; }
        p.lead { margin:0; color:var(--muted); }
        .panel { background:#fff; border:1px solid var(--line); border-radius:16px; padding:22px; box-shadow:0 8px 24px rgba(24,55,90,.045); }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:6px; border:0; cursor:pointer; border-radius:9px; padding:9px 13px; font-weight:650; font-size:.92rem; text-decoration:none; }
        .btn-primary { background:var(--brand2); color:#fff; }
        .btn-light { background:#eaf0f7; color:#233b58; }
        .btn-danger { background:#fee2e2; color:#991b1b; }
        .btn:hover { filter:brightness(.97); }
        .alerts { margin-bottom:16px; }
        .alert { padding:12px 14px; border-radius:10px; margin-bottom:8px; border:1px solid; overflow-wrap:anywhere; }
        .alert-success { color:#166534; background:#f0fdf4; border-color:#bbf7d0; }
        .alert-error { color:#991b1b; background:#fef2f2; border-color:#fecaca; }
        .table-wrap { overflow-x:auto; }
        table { border-collapse:collapse; width:100%; font-size:.93rem; }
        th,td { text-align:left; padding:12px 11px; border-bottom:1px solid #e8edf3; white-space:nowrap; }
        th { color:#52657b; background:#f8fafc; font-weight:750; }
        tbody tr:hover { background:#fbfdff; }
        .actions { display:flex; gap:7px; align-items:center; }
        .actions form { margin:0; }
        .field { margin-bottom:17px; }
        label { display:block; font-weight:700; margin-bottom:7px; font-size:.94rem; }
        input, select, textarea { width:100%; border:1px solid #cbd5e1; border-radius:9px; padding:10px 11px; background:white; color:var(--ink); font:inherit; }
        input:focus,select:focus,textarea:focus { outline:3px solid #dbeafe; border-color:#60a5fa; }
        .error-text { color:#b91c1c; font-size:.85rem; margin-top:5px; }
        .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 18px; }
        .form-actions { display:flex; gap:9px; margin-top:12px; }
        .cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; }
        .entity-card { display:block; padding:18px; background:white; border:1px solid var(--line); border-radius:14px; text-decoration:none; color:var(--ink); transition:transform .12s ease,box-shadow .12s ease; }
        .entity-card:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(24,55,90,.08); }
        .entity-card strong { display:block; font-size:1.03rem; margin-bottom:6px; }
        .entity-card span { color:var(--muted); font-size:.88rem; }
        .footer-note { margin-top:18px; color:var(--muted); font-size:.85rem; }
        .pagination { margin-top:18px; }
        .pagination nav { display:flex; justify-content:space-between; }
        .pagination a,.pagination span { background:#fff; border:1px solid var(--line); color:var(--brand); padding:7px 10px; border-radius:7px; text-decoration:none; }
        @media(max-width:650px) { .form-grid { grid-template-columns:1fr; } header { align-items:flex-start; } h1 { font-size:1.45rem; } .panel { padding:15px; } }
    </style>
</head>
<body>
<header>
    <div>
        <a class="brand" href="{{ route('home') }}">Sistem KRS Sederhana</a>
        <div class="subbrand">Tugas-3 · Laravel + MySQL/WAMP · Tanpa autentikasi</div>
    </div>
    <nav>
        <a href="{{ route('home') }}">Dashboard</a>
        <a href="{{ route('crud.index', 'mahasiswa') }}">Mahasiswa</a>
        <a href="{{ route('crud.index', 'mata-kuliah') }}">Mata Kuliah</a>
        <a href="{{ route('crud.index', 'krs') }}">KRS</a>
    </nav>
</header>
<main>
    @if (session('success') || session('error'))
        <div class="alerts">
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
        </div>
    @endif
    @if ($errors->any())
        <div class="alerts"><div class="alert alert-error"><strong>Periksa input berikut:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif
    @yield('content')
</main>
</body>
</html>
