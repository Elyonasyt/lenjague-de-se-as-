<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Logs Laravel</title>
<meta http-equiv="refresh" content="5">
<style>
body{background:#0f172a;color:#fff;font-family:monospace;padding:25px}
a{color:#67e8f9}.row{background:#1e293b;padding:10px;border-radius:8px;margin:6px 0;white-space:pre-wrap;word-break:break-word}
</style>
</head>
<body>
<h1>📄 Logs Laravel</h1>
<p><a href="{{ route('admin') }}">← Volver al administrador</a></p>

@forelse($logs as $log)
    <div class="row">{{ $log }}</div>
@empty
    <p>No hay logs o el archivo laravel.log todavía no existe.</p>
@endforelse
</body>
</html>
