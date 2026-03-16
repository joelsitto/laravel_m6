<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP') — GestióPro</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --surface: #1a1a1a;
            --border: #2a2a2a;
            --accent: #e8ff47;
            --accent-dim: #b8cc2a;
            --text: #e8e8e8;
            --text-muted: #666;
            --danger: #ff4757;
            --success: #2ed573;
            --warning: #ffa502;
            --info: #1e90ff;
            --mono: 'IBM Plex Mono', monospace;
            --sans: 'IBM Plex Sans', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: var(--sans); font-size: 14px; min-height: 100vh; }

        /* NAV */
        nav { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 56px; position: sticky; top: 0; z-index: 100; }
        .nav-brand { font-family: var(--mono); font-size: 13px; color: var(--accent); letter-spacing: 0.1em; text-decoration: none; }
        .nav-links { display: flex; gap: 0.25rem; }
        .nav-links a { color: var(--text-muted); text-decoration: none; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 13px; transition: all 0.15s; }
        .nav-links a:hover, .nav-links a.active { color: var(--text); background: var(--border); }
        .nav-user { font-size: 12px; color: var(--text-muted); font-family: var(--mono); }

        /* LAYOUT */
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem; }
        .page-title { font-size: 22px; font-weight: 600; }
        .page-subtitle { color: var(--text-muted); font-size: 13px; margin-top: 0.25rem; font-family: var(--mono); }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 4px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; transition: all 0.15s; font-family: var(--sans); }
        .btn-primary { background: var(--accent); color: #0f0f0f; }
        .btn-primary:hover { background: var(--accent-dim); }
        .btn-ghost { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { border-color: #444; color: var(--text); }
        .btn-danger { background: transparent; color: var(--danger); border: 1px solid var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 12px; }
        .btn-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        /* TABLE */
        .table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111; color: var(--text-muted); font-family: var(--mono); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border); font-weight: 500; }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* CARDS */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-title { font-size: 12px; font-family: var(--mono); color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; }
        .field-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
        .field { display: flex; flex-direction: column; gap: 0.3rem; }
        .field-label { font-size: 11px; color: var(--text-muted); font-family: var(--mono); text-transform: uppercase; letter-spacing: 0.07em; }
        .field-value { font-size: 14px; }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.55rem; border-radius: 3px; font-size: 11px; font-weight: 500; font-family: var(--mono); letter-spacing: 0.04em; }
        .badge-planificacio { background: rgba(30,144,255,0.15); color: var(--info); border: 1px solid rgba(30,144,255,0.3); }
        .badge-en_curs     { background: rgba(46,213,115,0.15); color: var(--success); border: 1px solid rgba(46,213,115,0.3); }
        .badge-pausat      { background: rgba(255,165,2,0.15);  color: var(--warning); border: 1px solid rgba(255,165,2,0.3); }
        .badge-finalitzat  { background: rgba(102,102,102,0.2); color: #999; border: 1px solid rgba(102,102,102,0.3); }
        .badge-cancelat    { background: rgba(255,71,87,0.15);  color: var(--danger); border: 1px solid rgba(255,71,87,0.3); }
        .badge-actiu       { background: rgba(46,213,115,0.15); color: var(--success); border: 1px solid rgba(46,213,115,0.3); }
        .badge-inactiu     { background: rgba(255,71,87,0.15);  color: var(--danger); border: 1px solid rgba(255,71,87,0.3); }

        /* FORMS */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 11px; color: var(--text-muted); font-family: var(--mono); text-transform: uppercase; letter-spacing: 0.07em; }
        input, select, textarea { background: var(--bg); border: 1px solid var(--border); color: var(--text); padding: 0.55rem 0.75rem; border-radius: 4px; font-size: 13px; font-family: var(--sans); width: 100%; transition: border-color 0.15s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); }
        input[readonly] { color: var(--text-muted); cursor: not-allowed; }
        textarea { resize: vertical; min-height: 80px; }
        .form-error { color: var(--danger); font-size: 12px; margin-top: 0.2rem; }
        .form-actions { display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); }

        /* ALERTS */
        .alert { padding: 0.75rem 1rem; border-radius: 4px; font-size: 13px; margin-bottom: 1.5rem; border-left: 3px solid; }
        .alert-success { background: rgba(46,213,115,0.08); border-color: var(--success); color: var(--success); }
        .alert-error   { background: rgba(255,71,87,0.08);  border-color: var(--danger);  color: var(--danger); }

        /* FILTERS */
        .filters { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .filters .form-group { flex: 1; min-width: 160px; margin: 0; }

        /* PAGINATION */
        .pagination { display: flex; justify-content: center; gap: 0.25rem; margin-top: 1.5rem; }
        .pagination a, .pagination span { padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 12px; font-family: var(--mono); color: var(--text-muted); border: 1px solid var(--border); text-decoration: none; }
        .pagination a:hover { border-color: #444; color: var(--text); }
        .pagination .active span { background: var(--accent); color: #0f0f0f; border-color: var(--accent); }

        /* EMPTY STATE */
        .empty { text-align: center; padding: 3rem; color: var(--text-muted); font-family: var(--mono); font-size: 13px; }

        /* STAT CHIPS */
        .stat-row { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .stat { background: var(--surface); border: 1px solid var(--border); border-radius: 6px; padding: 1rem 1.25rem; flex: 1; min-width: 140px; }
        .stat-num { font-size: 24px; font-weight: 600; font-family: var(--mono); color: var(--accent); }
        .stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.07em; margin-top: 0.2rem; }
    </style>
</head>
<body>

<nav>
    <a class="nav-brand" href="{{ route('projectes.index') }}">// GESTIÓ_PRO</a>
    <div class="nav-links">
        <a href="{{ route('projectes.index') }}" class="{{ request()->routeIs('projectes.*') ? 'active' : '' }}">Projectes</a>
        <a href="{{ route('clients.index') }}"   class="{{ request()->routeIs('clients.*')   ? 'active' : '' }}">Clients</a>
    </div>
    <div class="nav-user">{{ Auth::user()->name ?? 'guest' }}</div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">✗ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <strong>Revisa els errors del formulari:</strong>
            <ul style="margin: 0.4rem 0 0 1rem; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>
