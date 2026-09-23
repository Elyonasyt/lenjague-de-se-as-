<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Bitácora LSM</title>
<style>
body{background:#0f172a;color:#fff;font-family:Arial;padding:30px}
a{color:#67e8f9}
.log{background:#1e293b;padding:18px;border-radius:14px;margin:12px 0;border-left:5px solid #22d3ee}
.insert{border-left-color:#22c55e}.update{border-left-color:#f59e0b}.delete{border-left-color:#ef4444}.login{border-left-color:#8b5cf6}.logout{border-left-color:#64748b}
small{color:#94a3b8}
</style>
</head>
<body>
<h1>📊 Bitácora del sistema</h1>
<p><a href="{{ route('admin') }}">← Volver al administrador</a></p>

@forelse($bitacora as $b)
    <div class="log {{ strtolower($b->accion) }}">
        <strong>{{ $b->accion }}</strong> · {{ $b->tabla }}
        <br>
        Usuario:
        {{ trim(($b->first_name ?? '') . ' ' . ($b->last_name ?? '')) ?: 'Sistema / usuario eliminado' }}
        <br>
        {{ $b->descripcion }}
        <br>
        <small>{{ $b->fecha }}</small>
    </div>
@empty
    <p>No hay registros.</p>
@endforelse
</body>
</html>
