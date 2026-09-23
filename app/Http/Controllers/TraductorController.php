<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TraductorController extends Controller
{
    public function index()
    {
        $idUsuario = $this->usuarioId();
        return $this->render($idUsuario, [], '');
    }

    public function traducir(Request $request)
    {
        $request->validate([
            'texto' => ['required','string','max:1000'],
            'tipo_entrada' => ['nullable','in:TEXTO,VOZ,CAMARA'],
        ]);

        $idUsuario = $this->usuarioId();
        $texto = trim($request->texto);
        $tipo = $request->input('tipo_entrada', 'TEXTO');

        $resultado = $this->resolverTexto($texto);

        $idTraduccion = DB::table('translations')->insertGetId([
            'texto_ingresado' => $texto,
            'fecha_traduccion' => now(),
            'id_usuario' => $idUsuario,
            'id_palabra' => null,
            'resultado_json' => json_encode($resultado, JSON_UNESCAPED_UNICODE),
            'tipo_entrada' => $tipo,
        ]);

        DB::table('history')->insert([
            'id_traduccion' => $idTraduccion,
        ]);

        return $this->render($idUsuario, $resultado, $texto);
    }

    public function reconsultar($id)
    {
        $idUsuario = $this->usuarioId();

        $t = DB::table('translations')
            ->where('id_traduccion', $id)
            ->where('id_usuario', $idUsuario)
            ->first();

        abort_if(!$t, 404);

        $resultado = json_decode($t->resultado_json ?? '[]', true);
        if (!is_array($resultado)) {
            $resultado = [];
        }

        return $this->render($idUsuario, $resultado, $t->texto_ingresado);
    }

    private function render(int $idUsuario, array $resultado, string $textoAnterior)
    {
        $usuario = DB::table('users')
            ->where('id_user', $idUsuario)
            ->first();

        $historial = DB::table('history as h')
            ->join('translations as t', 't.id_traduccion', '=', 'h.id_traduccion')
            ->where('t.id_usuario', $idUsuario)
            ->orderByDesc('t.fecha_traduccion')
            ->select('t.*')
            ->limit(100)
            ->get();

        return view('traductor', compact(
            'usuario',
            'historial',
            'resultado',
            'textoAnterior'
        ));
    }

    private function resolverTexto(string $texto): array
    {
        $normalizado = Str::lower(Str::ascii($texto));
        $normalizado = preg_replace('/[^a-z0-9\s]/u', ' ', $normalizado);
        $tokens = preg_split('/\s+/', trim($normalizado));

        $salida = [];

        foreach ($tokens as $token) {
            if ($token === '') {
                continue;
            }

            $completa = $this->buscarPalabra($token);

            if ($completa) {
                $salida[] = $completa;
                continue;
            }

            $letras = preg_split('//u', strtoupper($token), -1, PREG_SPLIT_NO_EMPTY);

            foreach ($letras as $letra) {
                $item = $this->buscarLetra($letra);

                $salida[] = $item ?? [
                    'texto' => $letra,
                    'ruta_imagen' => 'images/signs/no-image.svg',
                    'tipo' => 'NO_ENCONTRADO',
                ];
            }
        }

        return $salida;
    }

    private function buscarPalabra(string $texto): ?array
    {
        $fila = DB::table('words as w')
            ->join('categories as c', 'c.id_categoria', '=', 'w.id_categoria')
            ->leftJoin('images as i', 'i.id_palabra', '=', 'w.id_palabra')
            ->whereRaw('LOWER(TRIM(w.palabra_espanol)) = ?', [strtolower($texto)])
            ->orderByRaw("CASE WHEN c.tipo_categoria='PALABRAS' THEN 0 ELSE 1 END")
            ->select(
                'w.id_palabra',
                'w.palabra_espanol',
                'c.tipo_categoria',
                'i.ruta_imagen'
            )
            ->first();

        if (!$fila || !$fila->ruta_imagen) {
            return null;
        }

        return [
            'id_palabra' => $fila->id_palabra,
            'texto' => strtoupper($fila->palabra_espanol),
            'ruta_imagen' => $fila->ruta_imagen,
            'tipo' => $fila->tipo_categoria,
        ];
    }

    private function buscarLetra(string $letra): ?array
    {
        $fila = DB::table('words as w')
            ->join('categories as c', 'c.id_categoria', '=', 'w.id_categoria')
            ->leftJoin('images as i', 'i.id_palabra', '=', 'w.id_palabra')
            ->whereRaw('UPPER(TRIM(w.palabra_espanol)) = ?', [strtoupper($letra)])
            ->where('c.tipo_categoria', 'LETRAS')
            ->select(
                'w.id_palabra',
                'w.palabra_espanol',
                'i.ruta_imagen'
            )
            ->first();

        if (!$fila || !$fila->ruta_imagen) {
            return null;
        }

        return [
            'id_palabra' => $fila->id_palabra,
            'texto' => strtoupper($fila->palabra_espanol),
            'ruta_imagen' => $fila->ruta_imagen,
            'tipo' => 'LETRAS',
        ];
    }

    private function usuarioId(): int
    {
        $id = session('usuario_id');
        abort_if(!$id, 401, 'Debes iniciar sesión.');
        return (int)$id;
    }
}
