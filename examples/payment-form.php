<?php

/**
 * Example: Creating a Payment Form
 *
 * This example shows how to create a payment form that redirects
 * the user to Cecabank's payment gateway.
 */

require '../vendor/autoload.php';

use Cecabank\Client;
use Cecabank\Exceptions\CecabankException;

try {
    // Initialize the client with your credentials
    $client = new Client([
        'Environment' => 'test', // Use 'real' for production
        'ClaveCifrado' => 'your_secret_key_here',
        'MerchantID' => '123456789',
        'AcquirerBIN' => '0000000000',
        'TerminalID' => '00000001',
        'TipoMoneda' => '978', // EUR
        'Exponente' => '2',
        'Cifrado' => 'SHA2', // SHA1 or SHA2
        'Pago_soportado' => 'SSL'
    ]);

    // Prepare payment data
    $orderNumber = uniqid('ORDER-'); // Generate unique order number
    $amount = '25.99'; // Payment amount

    $client->setFormHiddens([
        'Num_operacion' => $orderNumber,
        'Importe' => $amount,
        'URL_OK' => 'http://localhost/examples/payment-response.php?status=ok',
        'URL_NOK' => 'http://localhost/examples/payment-response.php?status=error',
        'Descripcion' => 'Order #' . $orderNumber,
        'datos_acs_20' => '' // Additional data for 3D Secure 2.0
    ]);

    // Get the payment gateway URL
    $paymentUrl = $client->getPath();

} catch (CecabankException $e) {
    die('Configuration error: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cecabank Payment Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .payment-info {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <h1>Cecabank Payment Example</h1>

    <div class="payment-info">
        <h2>Order Details</h2>
        <p><strong>Order Number:</strong> <?= htmlspecialchars($orderNumber) ?></p>
        <p><strong>Amount:</strong> €<?= htmlspecialchars($amount) ?></p>
        <p><strong>Environment:</strong> Test</p>
    </div>

    <form method="POST" action="<?= htmlspecialchars($paymentUrl) ?>">
        <?= $client->getFormHiddens() ?>
        <button type="submit">Proceed to Payment</button>
    </form>

    <p style="color: #666; font-size: 14px;">
        You will be redirected to Cecabank's secure payment gateway.
    </p>
</body>
</html>

