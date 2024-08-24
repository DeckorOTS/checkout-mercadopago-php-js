<?php

// Desactiva la notificación de errores deprecados en PHP
error_reporting(~E_DEPRECATED);

// Carga el autoload de Composer para gestionar dependencias
require_once '../../vendor/autoload.php';

// Importa las clases necesarias del SDK de MercadoPago
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

// Agrega credenciales ACCESS_TOKEN
MercadoPagoConfig::setAccessToken("APP_USR-2144920030270384-081810-fbae0fa7177288c3009c69afc0868b99-1952707964");

// Crea una instancia del cliente de preferencias de MercadoPago
$client = new PreferenceClient();

// Define las URLs de retorno para los diferentes estados de pago
$backUrls = [
    "success" => "https://www.tu-sitio/success",
    "failure" => "http://www.tu-sitio/failure",
    "pending" => "http://www.tu-sitio/pending"
];

// Crea una preferencia de pago con los detalles del producto y otras configuraciones
$preference = $client->create([
    "items" => [
        [ // Primer producto
            "id" => "DEP-0001",
            "title" => "Justificante IMSS",
            "description" => "Urgencias", // Opcional
            "picture_url" => "assets\img\JustificanteModelo1.png",  // Opcional
            "category_id" => "",  // Opcional
            "quantity" => 1,
            "currency_id" => "MXN", // Opcional
            "unit_price" => 100
        ]
    ],
    // Información del comprador
    "payer" => [
        "name" => "Test",
        "surname" => "User",
        "email" => "your_test_email@example.com",
        "phone" => [
            "area_code" => "11",
            "number" => "4444-4444"
        ],
        "identification" => [
            "type" => "CPF",
            "number" => "19119119100"
        ],
        "address" => [
            "zip_code" => "06233200",
            "street_name" => "Street",
            "street_number" => "123"
        ]
    ],
    // Webhook
    "notification_url" => "https://586a-189-216-17-222.ngrok-free.app/index.php",
    // URLs de retorno configuradas anteriormente
    "back_urls" => $backUrls,
    // Configura la redirección automática en caso de que el pago sea aprobado
    "auto_return" => "approved",
    // Modo binario de pago (true significa que solo se aceptan pagos completos y no se permite un estado pendiente)
    "binary_mode" => true,
    // Referencia externa para identificar la transacción en el sistema del vendedor
    "external_reference" => "CDP001",
    // Descripción que aparecerá en el extracto de la tarjeta del comprador
    "statement_descriptor" => "MI TIENDA CDP",
    // Configuración de métodos de pago
    "payment_methods" => [
        // Métodos de pago excluidos (por ejemplo, American Express)
        "excluded_payment_methods" => [
            [
                "id" => ""
            ]
        ],
        // Tipos de pago excluidos (por ejemplo, transferencia bancaria)
        "excluded_payment_types" => [
            [
                "id" => ""
            ]
        ],
        // Número máximo de cuotas permitido
        "installments" => 1
    ],
    // Configuración de tarjetas de crédito
    "credit_card" => [
        "installments" => 1
    ]
]);

// Retorna el ID de la preferencia
echo json_encode(['preferenceId' => $preference->id]);