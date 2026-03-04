<?php
$pageTitle = $pageTitle ?? 'PUSTAKA';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> — PUSTAKA</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #f0f4ff;
    --surface: #ffffff;
    --surface2: #f8f9ff;
    --border: #e2e8f8;
    --accent: #4f6ef7;
    --accent-light: #eef1ff;
    --accent2: #f97316;
    --accent2-light: #fff4ed;
    --red: #ef4444;
    --red-light: #fef2f2;
    --green: #22c55e;
    --green-light: #f0fdf4;
    --text: #1e293b;
    --text2: #475569;
    --muted: #94a3b8;
    --font: 'Plus Jakarta Sans', sans-serif;
    --font-display: 'Lora', serif;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { background:var(--bg); color:var(--text); font-family:var(--font); min-height:100vh; }

  body::before {
    content:''; position:fixed; inset:0; pointer-events:none; z-index:0;
    background-image: radial-gradient(circle, #c7d2fe 1px, transparent 1px);
    background-size: 28px 28px; opacity:0.35;
  }

  .app-wrap { position:relative; z-index:1; max-width:1100px; margin:0 auto; padding:0 2rem 4rem; }

  /* HEADER */
  header {
    padding:2.5rem 0 2rem; display:flex; align-items:flex-end;
    justify-content:space-between; border-bottom:2px solid var(--border); margin-bottom:2.5rem;
  }
  
  .logo-title {
    font-family:var(--font-display); font-size:2.8rem; font-weight:600; line-height:1;
    color: var(--accent);
  }
  .logo-sub { font-size:0.8rem; color:var(--muted); margin-top:0.4rem; }
  .header-stats { display:flex; gap:2rem; padding-bottom:0.5rem; }
  .stat-item { text-align:right; }
  .stat-num { font-family:var(--font-display); font-size:2rem; font-weight:600; color:var(--accent); line-height:1; }
  .stat-label { font-size:0.7rem; color:var(--muted); letter-spacing:0.08em; text-transform:uppercase; margin-top:0.2rem; }

  /* TOOLBAR */
  .toolbar { display:flex; gap:0.8rem; margin-bottom:2rem; align-items:center; }
  .search-wrap { flex:1; position:relative; }
  .search-wrap input {
    width:100%; background:var(--surface); border:2px solid var(--border); color:var(--text);
    font-family:var(--font); font-size:0.9rem; padding:0.7rem 1rem 0.7rem 2.8rem;
    border-radius:12px; outline:none; transition:all 0.2s;
    box-shadow: 0 1px 4px rgba(79,110,247,0.06);
  }
  .search-wrap input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(79,110,247,0.12); }
  .search-wrap input::placeholder { color:var(--muted); }
  .search-icon { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--muted); }
  .filter-select {
    background:var(--surface); border:2px solid var(--border); color:var(--text);
    font-family:var(--font); font-size:0.85rem; padding:0.7rem 1rem;
    border-radius:12px; outline:none; cursor:pointer; min-width:140px;
    box-shadow: 0 1px 4px rgba(79,110,247,0.06);
  }
  .filter-select:focus { border-color:var(--accent); }
  .filter-select option { background:white; }
  .btn-add {
    background:var(--accent); color:white; font-family:var(--font); font-weight:600;
    font-size:0.85rem; padding:0.7rem 1.4rem; border:none; border-radius:12px; cursor:pointer;
    display:flex; align-items:center; gap:0.4rem; transition:all 0.2s; white-space:nowrap;
    text-decoration:none; box-shadow:0 2px 8px rgba(79,110,247,0.3);
  }
  .btn-add:hover { background:#3b55e0; transform:translateY(-1px); box-shadow:0 4px 16px rgba(79,110,247,0.35); }

  /* GRID */
  .books-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1.2rem; }
  .book-card {
    background:var(--surface); border:2px solid var(--border); border-radius:16px;
    padding:1.4rem; position:relative; transition:all 0.25s; overflow:hidden;
    animation:fadeIn 0.35s ease forwards;
    box-shadow: 0 2px 8px rgba(79,110,247,0.06);
  }
  @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
  .book-card:hover { border-color:var(--accent); transform:translateY(-3px); box-shadow:0 8px 28px rgba(79,110,247,0.15); }
  .book-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:4px;
    background:linear-gradient(90deg, var(--accent), var(--accent2)); opacity:0; transition:opacity 0.2s; border-radius:16px 16px 0 0;
  }
  .book-card:hover::before { opacity:1; }

  .card-genre { font-size:0.62rem; letter-spacing:0.15em; text-transform:uppercase; padding:0.25rem 0.7rem; border-radius:20px; display:inline-block; margin-bottom:0.9rem; font-weight:700; }
  .genre-Fiksi     { background:#ede9fe; color:#7c3aed; }
  .genre-Non-Fiksi { background:#dcfce7; color:#15803d; }
  .genre-Sains     { background:#fef9c3; color:#a16207; }
  .genre-Sejarah   { background:#fee2e2; color:#b91c1c; }
  .genre-Teknologi { background:#dbeafe; color:#1d4ed8; }
  .genre-Lainnya   { background:#f1f5f9; color:#475569; }

  .book-title { font-family:var(--font-display); font-size:1.15rem; font-weight:600; color:var(--text); line-height:1.3; margin-bottom:0.25rem; }
  .book-author { font-size:0.8rem; color:var(--muted); margin-bottom:1rem; font-style:italic; }
  .book-meta { display:flex; flex-direction:column; gap:0.4rem; margin-bottom:1.2rem; }
  .meta-row { display:flex; justify-content:space-between; align-items:center; font-size:0.78rem; }
  .meta-key { color:var(--muted); font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
  .meta-val { color:var(--text2); font-weight:500; }

  .card-actions { display:flex; gap:0.6rem; border-top:2px solid var(--border); padding-top:1rem; }
  .btn-edit, .btn-del {
    flex:1; font-family:var(--font); font-size:0.8rem; font-weight:600;
    padding:0.55rem; border-radius:10px; border:2px solid; cursor:pointer; transition:all 0.18s;
    text-align:center; text-decoration:none; display:block;
  }
  .btn-edit { background:var(--accent-light); border-color:var(--accent); color:var(--accent); }
  .btn-edit:hover { background:var(--accent); color:white; }
  .btn-del  { background:var(--red-light); border-color:#fca5a5; color:var(--red); }
  .btn-del:hover  { background:var(--red); border-color:var(--red); color:white; }

  /* EMPTY */
  .empty-state { grid-column:1/-1; text-align:center; padding:5rem 2rem; color:var(--muted); }
  .empty-state .empty-icon { font-size:3.5rem; margin-bottom:1rem; }
  .empty-state h3 { font-family:var(--font-display); font-size:1.4rem; color:var(--text); margin-bottom:0.5rem; }

  /* FORM PAGE */
  .form-page { max-width:560px; margin:0 auto; padding-top:1rem; }
  .page-heading { font-family:var(--font-display); font-size:2rem; font-weight:600; margin-bottom:0.4rem; color:var(--accent); }
  .page-sub { font-size:0.82rem; color:var(--muted); margin-bottom:2rem; }
  .form-card { background:var(--surface); border:2px solid var(--border); border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(79,110,247,0.08); }
  .form-group { margin-bottom:1.2rem; }
  .form-label { display:block; font-size:0.72rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--text2); margin-bottom:0.5rem; }
  .form-input, .form-select, .form-textarea {
    width:100%; background:var(--surface2); border:2px solid var(--border); color:var(--text);
    font-family:var(--font); font-size:0.9rem; padding:0.7rem 0.9rem;
    border-radius:10px; outline:none; transition:all 0.2s;
  }
  .form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color:var(--accent); box-shadow:0 0 0 3px rgba(79,110,247,0.12); background:white;
  }
  .form-input::placeholder, .form-textarea::placeholder { color:var(--muted); }
  .form-select option { background:white; }
  .form-textarea { resize:vertical; min-height:80px; }
  .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .form-actions { display:flex; gap:0.8rem; margin-top:1.8rem; }
  .btn-cancel {
    flex:1; background:var(--surface2); border:2px solid var(--border); color:var(--text2);
    font-family:var(--font); font-weight:600; font-size:0.88rem; padding:0.75rem; border-radius:10px;
    cursor:pointer; transition:all 0.18s; text-align:center; text-decoration:none; display:block;
  }
  .btn-cancel:hover { border-color:var(--text2); color:var(--text); }
  .btn-save {
    flex:2; background:var(--accent); border:none; color:white; font-family:var(--font);
    font-weight:700; font-size:0.88rem; padding:0.75rem; border-radius:10px; cursor:pointer;
    transition:all 0.18s; box-shadow:0 2px 8px rgba(79,110,247,0.3);
  }
  .btn-save:hover { background:#3b55e0; transform:translateY(-1px); box-shadow:0 4px 16px rgba(79,110,247,0.35); }

  .alert { padding:0.9rem 1.2rem; border-radius:12px; font-size:0.85rem; margin-bottom:1.5rem; border-left:4px solid; font-weight:500; }
  .alert-success { background:var(--green-light); border-color:var(--green); color:#15803d; }
  .alert-error   { background:var(--red-light);   border-color:var(--red);   color:#b91c1c; }

  ::-webkit-scrollbar { width:6px; }
  ::-webkit-scrollbar-track { background:var(--bg); }
  ::-webkit-scrollbar-thumb { background:var(--border); border-radius:3px; }

  @media(max-width:600px) {
    header { flex-direction:column; align-items:flex-start; gap:1rem; }
    .toolbar { flex-wrap:wrap; }
    .form-row { grid-template-columns:1fr; }
    .logo-title { font-size:2rem; }
  }
</style>
</head>
<body>
<div class="app-wrap">
