<?php
namespace App\Services;

use App\Repositories\CalculadoraRepositoryInterface;
use App\Repositories\CategoriaProductoRepositoryInterface;
use App\Repositories\EmpresaRepositoryInterface;
use App\Repositories\MarcaProductoRepositoryInterface;
use App\Repositories\registroUpdateRepositoryInterface;
use App\Repositories\TipoProductoRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Exception;

class HeaderService implements HeaderServiceInterface
{
    protected $empresaRepository;
    protected $categoriaRepository;
    protected $marcaRepository;
    protected $tipoRepository;
    protected $registroRepository;
    protected $calculadoraRepository;

    private static  $id = 2;

    public function __construct(EmpresaRepositoryInterface $empresaRepository,
                                CategoriaProductoRepositoryInterface $categoriaRepository,
                                MarcaProductoRepositoryInterface $marcaRepository,
                                TipoProductoRepositoryInterface $tipoRepository,
                                registroUpdateRepositoryInterface $registroRepository,
                                CalculadoraRepositoryInterface $calculadoraRepository)
    {
        $this->empresaRepository = $empresaRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->marcaRepository = $marcaRepository;
        $this->tipoRepository = $tipoRepository;
        $this->registroRepository = $registroRepository;
        $this->calculadoraRepository = $calculadoraRepository;
    }
    public function obtenerEmpresa()
    {
        return $this->empresaRepository->getOne('idEmpresa',self::$id);
    }

    public function obtenerCategorias()
    {
        return $this->categoriaRepository->getAll();
    }

    public function obtenerMarcas()
    {
        return $this->marcaRepository->getAll()->sortBy('nombreMarca'); //MarcaProducto::select('idMarca','nombreMarca','imagenMarca','slugMarca')->orderBy('nombreMarca','asc')->get();
    }

    public function obtenerTipo()
    {
        return $this->tipoRepository->getAll();
    }
    public function obtenerLinkRedes(){
        return $this->obtenerLinkRedes()->first();
    }

   public function getApiDolar() {

       $fecha = date("Y-m-d");

        // Configuración del cliente Guzzle
        $client = new Client([
            'base_uri' => 'https://api.apis.net.pe',
            'verify' => false, // Deshabilita la verificación SSL (ajustar en producción)
        ]);

        // Token de autenticación
        $token = 'apis-token-9519.CtzX-NCD9Ejr-S-6oxsV2gMlV06VeMNH';

        // Parámetros de la solicitud
        $parameters = [
        'http_errors' => false,
        'connect_timeout' => 5,
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Referer' => 'https://apis.net.pe/api-sunat-tipo-de-cambio',
            'User-Agent' => 'laravel/guzzle',
            'Accept' => 'application/json',
        ],
        'query' => ['fecha' => (string)$fecha], // Parámetro de fecha
        ];

        try {
            // Realizar la solicitud GET
            $response = $client->request('GET', '/v2/sunat/tipo-cambio', $parameters);

            // Verificar el código de estado de la respuesta
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                // Obtener y decodificar el cuerpo de la respuesta JSON
                $data = json_decode($response->getBody()->getContents(), true);

                // Retornar el precio de compra formateado
                return number_format($data['precioVenta'], 2, '.', '');
            } else {
                // Manejar otros códigos de estado si es necesario
                return null;
            }
        } catch (RequestException $e) {
            // Capturar errores de Guzzle (por ejemplo, tiempo de conexión agotado)
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $reason = $e->getResponse()->getReasonPhrase();
                echo "Error HTTP $statusCode: $reason";
            } else {
                echo 'Error de conexión: ' . $e->getMessage();
            }
        } catch (Exception $e) {
            // Capturar y manejar otras excepciones
            echo 'Excepción capturada: ' . $e->getMessage();
        }

        return null; // Manejo de retorno en caso de error
    }

    function updateTipoCambio($backup){
        $switch = false;
        $horaActual = date("H:i:s");
        $fechaActual = now()->format('Y-m-d');
        $lastUpdate = $this->registroRepository->get();

        if (!empty($lastUpdate) && isset($lastUpdate->ultimaFecha)) {
            if ($lastUpdate->ultimaFecha->format('Y-m-d') != $fechaActual && $horaActual > '10:30:00') {
                $switch = true;
            }
        }

        if($switch){
            $tc = $this->getApiDolar();
            if($tc != null){
                $this->calculadoraRepository->updateTC($tc);

                $this->registroRepository->update();
            }else{
                $this->calculadoraRepository->updateTC($backup);

                $this->registroRepository->update();
            }
        }
    }

    function obtenerCambioDolar(){
        $calculadora = $this->calculadoraRepository->get();
        $this->updateTipoCambio($calculadora->tasaCambio);
        return $calculadora->tasaCambio;
    }

    function obtenerCambioDolarFijo(){
        $calculadora = $this->calculadoraRepository->findById(2);

        if(!$calculadora){
            return null;
        }

        return $calculadora->tasaCambio;
    }
}
