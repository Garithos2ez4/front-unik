<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HeaderServiceInterface;

class ReviewsController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function index(){
        //Variables para el header,nav y footer
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();
        
        // Fetch approved reviews from the database
        $reviews = \App\Models\Review::where('estado', 1)->with(['cliente', 'producto'])->orderBy('created_at', 'desc')->get();
        
        // Data for the form
        $tipoDocumentos = \Illuminate\Support\Facades\DB::table('TipoDocumento')->get();
        $productos = \App\Models\Producto::whereIn('estadoProductoWeb', ['DISPONIBLE', 'EXCLUSIVO', 'OFERTA'])
                        ->orderBy('nombreProducto', 'asc')->get();
        
        return view('reviews',[
            'categorias' => $categorias,
            'empresa' => $empresa,
            'marcas' => $marcas,
            'tipos' => $tipos,
            'tipoCambio' => $tipoCambio,
            'reviews' => $reviews,
            'tipoDocumentos' => $tipoDocumentos,
            'productos' => $productos
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // 0. Anti-bot honeypot check
        if (!empty($request->input('website_hp'))) {
            return redirect()->route('reviews')->with('success', '¡Gracias! Tu reseña ha sido enviada y está pendiente de aprobación.');
        }

        $request->validate([
            'idTipoDocumento' => 'required|integer',
            'numeroDocumento' => 'required|string|max:15',
            'nombre' => 'nullable|string|max:100',
            'apellidoPaterno' => 'nullable|string|max:100',
            'correo' => 'nullable|max:100', // removed email strict rule for existing old users
            'telefono' => 'nullable|string|max:15',
            'idProducto' => 'nullable|integer',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
            'imagen_setup' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'captcha' => ['required', 'captcha'],  // ← mews/captcha validation
        ], [
            'captcha.required' => 'Por favor, completa la verificación de seguridad.',
            'captcha.captcha'  => 'El código de verificación es incorrecto. Inténtalo de nuevo.',
        ]);

        $tipoDoc = (int) $request->idTipoDocumento;
        $numDoc = trim($request->numeroDocumento);

        // Validaciones estrictas por tipo de documento
        if ($tipoDoc === 1) { // DNI
            if (!preg_match('/^[0-9]{8}$/', $numDoc)) {
                return back()->withErrors(['numeroDocumento' => 'El DNI debe contener exactamente 8 dígitos numéricos.'])->withInput();
            }
        } elseif ($tipoDoc === 3) { // RUC
            if (!preg_match('/^(10|15|17|20)[0-9]{9}$/', $numDoc)) {
                return back()->withErrors(['numeroDocumento' => 'El RUC debe tener 11 dígitos numéricos y comenzar con 10, 15, 17 o 20.'])->withInput();
            }
        } elseif ($tipoDoc === 2) { // Carné de Extranjería
            if (!preg_match('/^[A-Za-z0-9]{8,12}$/', $numDoc)) {
                return back()->withErrors(['numeroDocumento' => 'El Carné de Extranjería debe tener entre 8 y 12 caracteres.'])->withInput();
            }
        }

        // Find or create the client
        $cliente = \App\Models\Cliente::where('numeroDocumento', $request->numeroDocumento)->first();
        
        if (!$cliente) {
            // Get the next idCliente if it's not auto-incrementing
            $maxId = \App\Models\Cliente::max('idCliente');
            $nextId = $maxId ? $maxId + 1 : 1;

            $cliente = \App\Models\Cliente::create([
                'idCliente' => $nextId,
                'idTipoDocumento' => $request->idTipoDocumento,
                'numeroDocumento' => $request->numeroDocumento,
                'nombre' => $request->nombre,
                'apellidoPaterno' => $request->apellidoPaterno,
                'apellidoMaterno' => '',
                'correo' => $request->correo,
                'telefono' => $request->telefono,
            ]);
        } else {
            // Si el cliente ya existe pero le faltan datos, los actualizamos
            $updateData = [];
            if (empty($cliente->correo) && !empty($request->correo)) $updateData['correo'] = $request->correo;
            if (empty($cliente->telefono) && !empty($request->telefono)) $updateData['telefono'] = $request->telefono;
            if (empty($cliente->apellidoPaterno) && !empty($request->apellidoPaterno)) $updateData['apellidoPaterno'] = $request->apellidoPaterno;
            
            if (!empty($updateData)) {
                $cliente->update($updateData);
            }
        }
        // Process the image if uploaded
        $imagenPath = null;
        if ($request->hasFile('imagen_setup')) {
            $file = $request->file('imagen_setup');
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            // Guardamos directo en el symlink público (apunta a logunk/storage/app/public)
            $destDir = public_path('storage/reviews');
            if (!file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $filename);
            $imagenPath = 'reviews/' . $filename;
        }

        // Create the review with estado = 0 (pending)
        \App\Models\Review::create([
            'idCliente' => $cliente->idCliente,
            'idProducto' => $request->idProducto ?: null,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'imagen_setup' => $imagenPath,
            'estado' => 0 // Pending approval
        ]);

        return redirect()->route('reviews')->with('success', '¡Gracias! Tu reseña ha sido enviada y está pendiente de aprobación.');
    }

    public function buscarCliente($documento)
    {
        $cliente = \App\Models\Cliente::where('numeroDocumento', $documento)->first();
        
        if ($cliente) {
            return response()->json([
                'encontrado' => true,
                'datos' => [
                    'idTipoDocumento' => $cliente->idTipoDocumento,
                    'nombre' => $cliente->nombre,
                    'apellidoPaterno' => $cliente->apellidoPaterno,
                    'correo' => $cliente->correo,
                    'telefono' => $cliente->telefono,
                ]
            ]);
        }

        return response()->json(['encontrado' => false]);
    }
}