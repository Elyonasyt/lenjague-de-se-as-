<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $this->soloAdmin();

        $usuarios = DB::table('users')->orderByDesc('id_user')->get();

        $categorias = DB::table('categories')
            ->orderBy('tipo_categoria')
            ->orderBy('nombre_categoria')
            ->get();

        $palabras = DB::table('words as w')
            ->join('categories as c', 'c.id_categoria', '=', 'w.id_categoria')
            ->select('w.*', 'c.nombre_categoria', 'c.tipo_categoria')
            ->orderBy('w.palabra_espanol')
            ->get();

        $imagenes = DB::table('images as i')
            ->join('categories as c', 'c.id_categoria', '=', 'i.id_categoria')
            ->leftJoin('words as w', 'w.id_palabra', '=', 'i.id_palabra')
            ->select(
                'i.*',
                'c.nombre_categoria',
                'w.palabra_espanol'
            )
            ->orderByDesc('i.id_imagen')
            ->get();

        $traducciones = DB::table('translations as t')
            ->join('users as u', 'u.id_user', '=', 't.id_usuario')
            ->select(
                't.*',
                'u.first_name',
                'u.last_name'
            )
            ->orderByDesc('t.fecha_traduccion')
            ->limit(300)
            ->get();

        $muestras = DB::table('sign_samples as s')
            ->join('words as w', 'w.id_palabra', '=', 's.id_palabra')
            ->select(
                'w.id_palabra',
                'w.palabra_espanol',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('w.id_palabra', 'w.palabra_espanol')
            ->orderByDesc('total')
            ->get();

        return view('admin', compact(
            'usuarios',
            'categorias',
            'palabras',
            'imagenes',
            'traducciones',
            'muestras'
        ));
    }

    /* =========================
       USUARIOS
    ========================= */
    public function usuarioStore(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'middle_name' => ['nullable','string','max:100'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role' => ['required','in:USER,ADMIN'],
        ]);

        DB::table('users')->insert([
            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),
            'middle_name' => $request->middle_name ?: null,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'registration_date' => now()->toDateString(),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Usuario agregado.');
    }

    public function usuarioUpdate(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'middle_name' => ['nullable','string','max:100'],
            'email' => ['required','email'],
            'role' => ['required','in:USER,ADMIN'],
        ]);

        $data = [
            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),
            'middle_name' => $request->middle_name ?: null,
            'email' => strtolower(trim($request->email)),
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id_user', $id)->update($data);

        return back()->with('success', 'Usuario actualizado.');
    }

    public function usuarioDelete($id)
    {
        $this->soloAdmin();

        if ((int)$id === (int)session('usuario_id')) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta mientras estás conectado.']);
        }

        DB::table('users')->where('id_user', $id)->delete();

        return back()->with('success', 'Usuario eliminado.');
    }

    /* =========================
       CATEGORÍAS
    ========================= */
    public function categoriaStore(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'nombre_categoria' => ['required','string','max:100'],
            'tipo_categoria' => ['required','in:NUMEROS,LETRAS,PALABRAS,COLORES'],
            'descripcion' => ['nullable','string'],
        ]);

        DB::table('categories')->insert([
            'nombre_categoria' => trim($request->nombre_categoria),
            'tipo_categoria' => $request->tipo_categoria,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Categoría agregada.');
    }

    public function categoriaUpdate(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'nombre_categoria' => ['required','string','max:100'],
            'tipo_categoria' => ['required','in:NUMEROS,LETRAS,PALABRAS,COLORES'],
        ]);

        DB::table('categories')
            ->where('id_categoria', $id)
            ->update([
                'nombre_categoria' => trim($request->nombre_categoria),
                'tipo_categoria' => $request->tipo_categoria,
                'descripcion' => $request->descripcion,
            ]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function categoriaDelete($id)
    {
        $this->soloAdmin();

        try {
            DB::table('categories')->where('id_categoria', $id)->delete();
            return back()->with('success', 'Categoría eliminada.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'No se puede eliminar: la categoría tiene palabras o imágenes relacionadas.']);
        }
    }

    /* =========================
       PALABRAS
    ========================= */
    public function palabraStore(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'palabra_espanol' => ['required','string','max:255'],
            'id_categoria' => ['required','integer','exists:categories,id_categoria'],
        ]);

        DB::table('words')->insert([
            'palabra_espanol' => strtoupper(trim($request->palabra_espanol)),
            'id_categoria' => $request->id_categoria,
        ]);

        return back()->with('success', 'Palabra agregada.');
    }

    public function palabraUpdate(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'palabra_espanol' => ['required','string','max:255'],
            'id_categoria' => ['required','integer','exists:categories,id_categoria'],
        ]);

        DB::table('words')
            ->where('id_palabra', $id)
            ->update([
                'palabra_espanol' => strtoupper(trim($request->palabra_espanol)),
                'id_categoria' => $request->id_categoria,
            ]);

        return back()->with('success', 'Palabra actualizada.');
    }

    public function palabraDelete($id)
    {
        $this->soloAdmin();

        DB::table('words')->where('id_palabra', $id)->delete();

        return back()->with('success', 'Palabra eliminada.');
    }

    /* =========================
       IMÁGENES
    ========================= */
    public function imagenStore(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'id_categoria' => ['required','integer','exists:categories,id_categoria'],
            'id_palabra' => ['nullable','integer','exists:words,id_palabra'],
            'imagen' => ['required','image','mimes:jpg,jpeg,png,webp,gif','max:4096'],
            'descripcion' => ['nullable','string'],
        ]);

        $archivo = $request->file('imagen');
        $nombre = time() . '_' . Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $archivo->getClientOriginalExtension();

        $archivo->move(public_path('images/signs'), $nombre);

        DB::table('images')->insert([
            'id_categoria' => $request->id_categoria,
            'id_palabra' => $request->id_palabra ?: null,
            'ruta_imagen' => 'images/signs/' . $nombre,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Imagen agregada.');
    }

    public function imagenUpdate(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'id_categoria' => ['required','integer','exists:categories,id_categoria'],
            'id_palabra' => ['nullable','integer','exists:words,id_palabra'],
            'descripcion' => ['nullable','string'],
            'imagen' => ['nullable','image','mimes:jpg,jpeg,png,webp,gif','max:4096'],
        ]);

        $img = DB::table('images')->where('id_imagen', $id)->first();
        abort_if(!$img, 404);

        $data = [
            'id_categoria' => $request->id_categoria,
            'id_palabra' => $request->id_palabra ?: null,
            'descripcion' => $request->descripcion,
        ];

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $nombre = time() . '_' . Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $archivo->getClientOriginalExtension();

            $archivo->move(public_path('images/signs'), $nombre);

            if ($img->ruta_imagen && file_exists(public_path($img->ruta_imagen))) {
                @unlink(public_path($img->ruta_imagen));
            }

            $data['ruta_imagen'] = 'images/signs/' . $nombre;
        }

        DB::table('images')->where('id_imagen', $id)->update($data);

        return back()->with('success', 'Imagen actualizada.');
    }

    public function imagenDelete($id)
    {
        $this->soloAdmin();

        $img = DB::table('images')->where('id_imagen', $id)->first();

        if ($img && $img->ruta_imagen && file_exists(public_path($img->ruta_imagen))) {
            @unlink(public_path($img->ruta_imagen));
        }

        DB::table('images')->where('id_imagen', $id)->delete();

        return back()->with('success', 'Imagen eliminada.');
    }

    /* =========================
       TRADUCCIONES
    ========================= */
    public function traduccionUpdate(Request $request, $id)
    {
        $this->soloAdmin();

        $request->validate([
            'texto_ingresado' => ['required','string','max:1000'],
            'tipo_entrada' => ['required','in:TEXTO,VOZ,CAMARA'],
        ]);

        DB::table('translations')
            ->where('id_traduccion', $id)
            ->update([
                'texto_ingresado' => trim($request->texto_ingresado),
                'tipo_entrada' => $request->tipo_entrada,
            ]);

        return back()->with('success', 'Traducción actualizada.');
    }

    public function traduccionDelete($id)
    {
        $this->soloAdmin();

        DB::table('translations')
            ->where('id_traduccion', $id)
            ->delete();

        return back()->with('success', 'Traducción eliminada.');
    }

    private function soloAdmin(): void
    {
        abort_if(!session('usuario_id'), 401, 'Debes iniciar sesión.');
        abort_if(session('usuario_role') !== 'ADMIN', 403, 'Acceso solo para administrador.');
    }
}
