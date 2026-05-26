<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Auditoría del Sistema</title>

<style>

body{
    margin:0;
    padding:20px;
    background:#eef1f5;
    font-family:Arial;
}

.container{
    width:95%;
    margin:auto;
}

h1{
    text-align:center;
    margin-bottom:20px;
    color:#222;
}

/* TABLA */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

th{
    background:#111;
    color:white;
    padding:12px;
    font-size:14px;
    text-transform:uppercase;
}

td{
    border-bottom:1px solid #ddd;
    padding:10px;
    font-size:13px;
    vertical-align:top;
}

tr:hover{
    background:#f1f7ff;
}

/* COLORES ACCIONES */
.insert{
    color:#1e7e34;
    font-weight:bold;
}

.update{
    color:#d39e00;
    font-weight:bold;
}

.delete{
    color:#c82333;
    font-weight:bold;
}

/* BLOQUES DE TEXTO */
.query{
    max-width:350px;
    white-space:pre-wrap;
    word-break:break-word;
    font-size:12px;
    color:#333;
}

/* BADGE */
.badge{
    padding:4px 8px;
    border-radius:5px;
    font-size:12px;
    color:white;
}

.badge.insert{ background:#28a745; }
.badge.update{ background:#ffc107; color:#000; }
.badge.delete{ background:#dc3545; }

</style>

</head>

<body>

<div class="container">

<h1>📊 Auditoría del Sistema</h1>

<table>

<tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Usuario</th>
    <th>Acción</th>
    <th>Tabla</th>
    <th>ID Registro</th>
    <th>Datos Anteriores</th>
    <th>Datos Nuevos</th>
</tr>

@foreach($logs as $log)

@php
$class = strtolower($log->accion);
@endphp

<tr>

<td>{{ $log->id_auditoria }}</td>
<td>{{ $log->fecha }}</td>
<td>{{ $log->usuario }}</td>

<td>
    <span class="badge {{ $class }}">
        {{ $log->accion }}
    </span>
</td>

<td>{{ $log->tabla_afectada }}</td>
<td>{{ $log->id_registro }}</td>

<td class="query">
{{ $log->datos_anteriores ?? '—' }}
</td>

<td class="query">
{{ $log->datos_nuevos ?? '—' }}
</td>

</tr>

@endforeach

</table>

</div>

</body>
</html>