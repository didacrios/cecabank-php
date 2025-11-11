<?php

/**
 * Example: Handling Payment Response
 *
 * This example shows how to validate and process the response
 * from Cecabank after a payment attempt.
 */

require '../vendor/autoload.php';

use Cecabank\Client;
use Cecabank\Exceptions\InvalidSignatureException;
use Cecabank\Exceptions\InvalidTransactionException;

// Initialize the client with the same configuration
$client = new Client([
    'Environment' => 'test',
    'ClaveCifrado' => 'your_secret_key_here',
    'MerchantID' => '123456789',
    'AcquirerBIN' => '0000000000',
    'TerminalID' => '00000001',
    'TipoMoneda' => '978',
    'Exponente' => '2',
    'Cifrado' => 'SHA2',
    'Pago_soportado' => 'SSL'
]);

$paymentSuccess = false;
$errorMessage = '';
$transactionData = [];

try {
    // Validate the transaction signature
    $client->checkTransaction($_POST);

    // Get transaction data
    $transactionData = [
        'order_number' => $_POST['Num_operacion'] ?? 'N/A',
        'amount' => $_POST['Importe'] ?? 'N/A',
        'reference' => $_POST['Referencia'] ?? 'N/A',
        'description' => $_POST['Descripcion'] ?? 'N/A'
    ];

    // Check if payment was successful
    if ($transactionData['description'] === $client->successCode()) {
        $paymentSuccess = true;

        // Here you would typically:
        // 1. Update your database (mark order as paid)
        // 2. Send confirmation email
        // 3. Log the transaction
        // 4. Etc.

    } else {
        $errorMessage = 'Payment was declined: ' . htmlspecialchars($transactionData['description']);
    }

} catch (InvalidSignatureException $e) {
    $errorMessage = 'Security error: Invalid signature. This transaction may be fraudulent.';
    // Log this for security review

} catch (InvalidTransactionException $e) {
    $errorMessage = 'Transaction error: ' . $e->getMessage();

} catch (Exception $e) {
    $errorMessage = 'Unexpected error: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        .transaction-details {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .transaction-details p {
            margin: 5px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Payment Result</h1>

    <?php if ($paymentSuccess): ?>
        <div class="success">
            <h2>✓ Payment Successful!</h2>
            <p>Your payment has been processed successfully.</p>
        </div>

        <div class="transaction-details">
            <h3>Transaction Details</h3>
            <p><strong>Order Number:</strong> <?= htmlspecialchars($transactionData['order_number']) ?></p>
            <p><strong>Amount:</strong> €<?= number_format((int)$transactionData['amount'] / 100, 2) ?></p>
            <p><strong>Reference:</strong> <?= htmlspecialchars($transactionData['reference']) ?></p>
        </div>

    <?php else: ?>
        <div class="error">
            <h2>✗ Payment Failed</h2>
            <p><?= htmlspecialchars($errorMessage) ?></p>
        </div>

        <?php if (!empty($transactionData)): ?>
            <div class="transaction-details">
                <h3>Transaction Information</h3>
                <p><strong>Order Number:</strong> <?= htmlspecialchars($transactionData['order_number']) ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <a href="payment-form.php">← Back to payment form</a>
</body>
</html>

