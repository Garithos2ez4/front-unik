<?php

namespace App\Http\Controllers;

use App\Services\HeaderServiceInterface;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected $headerService;

    public function __construct(HeaderServiceInterface $headerService)
    {
        $this->headerService = $headerService;
    }

    public function index()
    {
        // Variables requeridas por layouts.app
        $categorias = $this->headerService->obtenerCategorias();
        $empresa = $this->headerService->obtenerEmpresa();
        $marcas = $this->headerService->obtenerMarcas();
        $tipos = $this->headerService->obtenerTipo();
        $tipoCambio = $this->headerService->obtenerCambioDolar();

        $faqs = [
            [
                'pregunta' => '¿Cómo solicitar un envío?',
                'respuesta' => 'Una vez confirmada tu compra, dirígete a la sección de <strong>Envíos</strong> o contáctanos por WhatsApp. Allí podrás proporcionar los datos de la agencia seleccionada y nosotros gestionaremos la entrega de tu pedido a dicha agencia de forma rápida y segura.',
                'respuesta_texto' => 'Una vez confirmada tu compra, dirígete a la sección de \'Envíos\' o contáctanos por WhatsApp. Allí podrás proporcionar los datos de la agencia seleccionada y nosotros gestionaremos la entrega de tu pedido a dicha agencia.',
                'icono' => 'bi-box-seam'
            ],
            [
                'pregunta' => '¿Cómo iniciar sesión o registrarme?',
                'respuesta' => 'En la parte superior derecha de nuestra web encontrarás un ícono de usuario. Haz clic ahí para <strong>Iniciar Sesión</strong>. Si eres un usuario nuevo, puedes registrarte fácilmente ingresando tu DNI o RUC; nuestro sistema autocompletará tus datos oficiales al instante para ahorrarte tiempo.',
                'respuesta_texto' => 'En la parte superior de nuestra web encontrarás un ícono de usuario. Haz clic ahí para \'Iniciar Sesión\'. Si eres un usuario nuevo, puedes registrarte fácilmente ingresando tu DNI/RUC y el sistema autocompletará tus datos usando nuestro servicio integrado.',
                'icono' => 'bi-person-circle'
            ],
            [
                'pregunta' => '¿Cómo solicitar un link de pago?',
                'respuesta' => 'Al finalizar tu carrito de compras y generar tu pedido, puedes contactarnos directamente por WhatsApp proporcionando tu número de orden. Nuestro equipo te enviará al instante un <strong>link de pago seguro</strong> o un código QR para que completes tu transacción rápidamente.',
                'respuesta_texto' => 'Al finalizar tu carrito de compras y elegir la opción de pago, puedes contactarnos directamente por WhatsApp proporcionando tu número de orden. Nuestro equipo te enviará al instante un link de pago seguro o un código QR para que completes tu transacción.',
                'icono' => 'bi-link-45deg'
            ],
            [
                'pregunta' => '¿Cómo funciona la garantía?',
                'respuesta' => 'Todos nuestros productos cuentan con garantía (36, 12, 6 o 3 meses dependiendo del producto). Para hacerla válida, es requisito indispensable presentar tu comprobante de compra y que el producto no presente daños físicos. Para más información, te invitamos a visitar nuestra sección detallada de <a href="' . route('garantia') . '">Políticas de Garantía</a>.',
                'respuesta_texto' => 'Todos nuestros productos cuentan con garantía (36, 12, 6 o 3 meses dependiendo del producto). Para hacerla válida, debes presentar tu comprobante de compra y el producto no debe presentar daños físicos. Para más información, visita nuestra sección de \'Políticas de Garantía\'.',
                'icono' => 'bi-shield-check'
            ],
            [
                'pregunta' => '¿Dónde se encuentra nuestra tienda física?',
                'respuesta' => 'Nos encontramos ubicados en: <strong>' . $empresa->ubicacion . '</strong>.<br> ¡Te esperamos! Podrás ver nuestros productos en persona y recibir asesoramiento especializado de nuestro equipo.',
                'respuesta_texto' => $empresa->ubicacion . '. Te esperamos para que puedas ver nuestros productos en persona y recibir asesoramiento especializado.',
                'icono' => 'bi-shop'
            ],
            [
                'pregunta' => '¿Puedo tramitar la garantía directamente yo mismo?',
                'respuesta' => 'Sí, puedes hacer efectiva la garantía por tu cuenta. Solo necesitas acercarte con tu respectivo comprobante de compra y asegurarte de que el producto aún se encuentre dentro del plazo de garantía establecido.',
                'respuesta_texto' => 'Sí, puedes hacer efectiva la garantía por tu cuenta. Solo necesitas acercarte con tu respectivo comprobante de compra y asegurarte de que el producto aún se encuentre dentro del plazo de garantía establecido.',
                'icono' => 'bi-tools'
            ],
            [
                'pregunta' => '¿Qué necesito para retirar una compra en tienda?',
                'respuesta' => 'Para retirar una compra, necesitas presentar tu <strong>comprobante de compra</strong> (impreso o digital). En caso de no ser el titular de la compra, la persona que realice el recojo deberá presentar su DNI físico y el titular debe haber dejado sus datos autorizando el recojo previamente.',
                'respuesta_texto' => 'Para retirar una compra, necesitas presentar tu comprobante de compra. En caso de no ser el titular de la compra, la persona que realice el recojo deberá presentar su DNI físico y el titular debe haber dejado sus datos autorizando el recojo previamente.',
                'icono' => 'bi-bag-check'
            ],
            [
                'pregunta' => '¿Cuál es el horario de atención en tienda?',
                'respuesta' => 'Nuestro horario de atención en tienda física es de <strong>Lunes a Sábado de 9:00 AM a 7:00 PM</strong> y los <strong>Domingos de 9:00 AM a 6:00 PM</strong>. ¡Te esperamos!',
                'respuesta_texto' => 'Nuestro horario de atención en tienda física es de Lunes a Sábado de 9:00 AM a 7:00 PM y los Domingos de 9:00 AM a 6:00 PM.',
                'icono' => 'bi-clock-history'
            ],
            [
                'pregunta' => '¿Tienen costo adicional los envíos por agencia?',
                'respuesta' => 'Trabajamos con agencias seleccionadas donde <strong>no cobramos costos adicionales</strong> por enviar y entregar el paquete a la agencia. Sin embargo, para <strong>otras agencias de tu preferencia</strong>, sí podría aplicar un pequeño costo adicional por el traslado. En todos los casos, el flete de la agencia a tu destino final es asumido por ti.',
                'respuesta_texto' => 'En ciertas agencias seleccionadas no cobramos costos adicionales por dejar tu paquete. En otras agencias de tu preferencia, sí se cobra un adicional. El flete hacia tu destino es asumido por el cliente.',
                'icono' => 'bi-truck'
            ]
        ];

        return view('faq', [
            'categorias' => $categorias,
            'empresa' => $empresa,
            'marcas' => $marcas,
            'tipos' => $tipos,
            'tipoCambio' => $tipoCambio,
            'faqs' => $faqs
        ]);
    }
}
