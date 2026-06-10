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
            'imagen_setup' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

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
            $path = $request->file('imagen_setup')->store('public/reviews');
            // Remove 'public/' to be accessible via storage helper
            $imagenPath = str_replace('public/', '', $path);
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