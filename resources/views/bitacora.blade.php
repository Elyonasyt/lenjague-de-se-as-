<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora del Sistema</title>

    <style>
        body{
            background:#0f172a;
            color:white;
            font-family:Arial;
            padding:30px;
        }

        h1{
            margin-bottom:20px;
        }

        .log-box{
            background:#1e293b;
            padding:20px;
            border-radius:15px;
            margin-bottom:20px;
            border-left:5px solid #00c6ff;
        }

        .insert{ border-left-color:#00ff99; }
        .update{ border-left-color:#ffc107; }
        .delete{ border-left-color:#ff4d4d; }

        .fecha{
            color:#00ffcc;
            font-weight:bold;
            margin-bottom:10px;
        }

        pre{
            white-space:pre-wrap;
            word-wrap:break-word;
        }
    </style>
</head>

<body>

<h1>📊 Bitácora del Sistema</h1>

@forelse($bitacora as $log)

    @php
        $tipo = strtolower($log->accion);
    @endphp

    <div class="log-box {{ $tipo }}">

        <div class="fecha">
            [{{ $log->fecha }}]
        </div>

        @php
            $accionTexto = match(strtolower($log->accion)) {
                'insert' => 'insertó',
                'update' => 'actualizó',
                'delete' => 'eliminó',
                default => strtolower($log->accion)
            };

            $nombre = $log->first_name . ' ' . $log->last_name;
        @endphp

        <strong>Usuario:</strong> {{ $nombre }}
        <br>

        <strong>Acción:</strong> {{ $nombre }} {{ $accionTexto }} este registro
        <br>

        <strong>Tabla:</strong> {{ $log->tabla }}
        <br><br>

        <pre>{{ $log->descripcion }}</pre>

    </div>

@empty

    <p>No hay registros en la bitácora</p>

@endforelse

</body>
</html>
