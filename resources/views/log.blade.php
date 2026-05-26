<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Logs</title>

<meta http-equiv="refresh" content="3">

<style>

body{
background:#0f172a;
color:white;
font-family:Arial;
padding:30px;
}

.log-box{
background:#1e293b;
padding:20px;
margin-bottom:20px;
border-radius:15px;
border-left:5px solid #00ffcc;
}

pre{
white-space:pre-wrap;
}

</style>

</head>
<body>

<h1>Logs Laravel</h1>

@foreach($logs as $log)

<div class="log-box">

<pre>[{{ $log }}</pre>

</div>

@endforeach

</body>
</html>