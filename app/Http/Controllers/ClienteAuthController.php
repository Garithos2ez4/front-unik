<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\DetalleCliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Services\HeaderServiceInterface;

class ClienteAuthController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();

        return view('auth.register', [
            'categorias' => $categorias,
            'empresa' => $empresa,
            'marcas' => $marcas,
            'tipos' => $tipos,
            'tipoCambio' => $tipoCambio,
        ]);
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'nullable|string|max:100',
            'idTipoDocumento' => 'required|integer',
            'numeroDocumento' => 'required|string|max:20',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:Detalle_Cliente,email',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellidoPaterno.required' => 'El apellido paterno es obligatorio.',
            'idTipoDocumento.required' => 'Selecciona un tipo de documento.',
            'numeroDocumento.required' => 'El numero de documento es obligatorio.',
            'telefono.required' => 'El telefono es obligatorio.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.unique' => 'Este correo ya esta registrado.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
            'password.min' => 'La contrasena debe tener al menos 6 caracteres.',
        ]);

        // Verificar si el cliente ya existe en la tabla Cliente
        $cliente = Cliente::where('numeroDocumento', $request->numeroDocumento)->first();

        if ($cliente) {
            // Verificar si ya tiene cuenta web (DetalleCliente)
            $detalleExists = DetalleCliente::where('idCliente', $cliente->idCliente)->exists();
            if ($detalleExists) {
                return back()->withErrors(['numeroDocumento' => 'Este número de documento ya tiene una cuenta web registrada. Inicia sesión.'])->withInput();
            }

            // Actualizar datos del cliente por si cambiaron telefono o correo
            $cliente->update([
                'nombre' => $request->nombre,
                'apellidoPaterno' => $request->apellidoPaterno,
                'apellidoMaterno' => $request->apellidoMaterno ?? '',
                'idTipoDocumento' => $request->idTipoDocumento,
                'telefono' => $request->telefono,
                'correo' => $request->email,
            ]);
        } else {
            // Crear registro en tabla Cliente si no existe
            $nextId = Cliente::max('idCliente') + 1;
            $cliente = Cliente::create([
                'idCliente' => $nextId,
                'nombre' => $request->nombre,
                'apellidoPaterno' => $request->apellidoPaterno,
                'apellidoMaterno' => $request->apellidoMaterno ?? '',
                'numeroDocumento' => $request->numeroDocumento,
                'idTipoDocumento' => $request->idTipoDocumento,
                'telefono' => $request->telefono,
                'correo' => $request->email,
            ]);
        }

        // Crear registro en tabla Detalle_Cliente (credenciales)
        $detalleCliente = DetalleCliente::create([
            'idCliente' => $cliente->idCliente,
            'email' => $request->email,
            'password' => $request->password, // El cast 'hashed' del modelo lo encripta automaticamente
        ]);

        // Iniciar sesion automaticamente despues de registrarse
        Auth::guard('cliente')->login($detalleCliente);

        return redirect()->route('home')->with('success', 'Cuenta creada exitosamente. Bienvenido!');
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();

        return view('auth.login', [
            'categorias' => $categorias,
            'empresa' => $empresa,
            'marcas' => $marcas,
            'tipos' => $tipos,
            'tipoCambio' => $tipoCambio,
        ]);
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Ingresa tu correo electronico.',
            'password.required' => 'Ingresa tu contrasena.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::guard('cliente')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesion
     */
    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Has cerrado sesion correctamente.');
    }

    /**
     * API para buscar cliente por documento
     */
    public function searchClientByDocument(Request $request)
    {
        $numeroDocumento = $request->query('numeroDocumento');
        $cliente = Cliente::where('numeroDocumento', $numeroDocumento)->first();

        if ($cliente) {
            return response()->json(['success' => true, 'cliente' => $cliente]);
        }

        return response()->json(['success' => false]);
    }
}
