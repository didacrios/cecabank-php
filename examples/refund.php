<?php

/**
 * Example: Processing a Refund
 *
 * This example shows how to process a refund (anulación)
 * for a previously completed transaction.
 */

require '../vendor/autoload.php';

use Cecabank\Client;
use Cecabank\Exceptions\CecabankException;

// Initialize the client
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

$refundProcessed = false;
$errorMessage = '';
$refundData = [];

// Process refund if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $refundData = [
            'order_number' => $_POST['order_number'],
            'amount' => $_POST['amount'],
            'reference' => $_POST['reference']
        ];

        // Process the refund
        $result = $client->refund([
            'Num_operacion' => $refundData['order_number'],
            'Importe' => $refundData['amount'],
            'Referencia' => $refundData['reference'],
            // Optional: 'TIPO_ANU' => 'P' for partial refund
        ]);

        if ($result) {
            $refundProcessed = true;

            // Here you would typically:
            // 1. Update your database (mark order as refunded)
            // 2. Send notification email
            // 3. Log the refund

        } else {
            $errorMessage = 'Refund failed. Please check the transaction details and try again.';
        }

    } catch (CecabankException $e) {
        $errorMessage = 'Error processing refund: ' . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Process Refund</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #c82333;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #bee5eb;
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Process Refund</h1>

    <?php if ($refundProcessed): ?>
        <div class="success">
            <h2>✓ Refund Processed Successfully</h2>
            <p>The refund has been processed.</p>
            <p><strong>Order Number:</strong> <?= htmlspecialchars($refundData['order_number']) ?></p>
            <p><strong>Amount:</strong> €<?= htmlspecialchars($refundData['amount']) ?></p>
            <p><strong>Reference:</strong> <?= htmlspecialchars($refundData['reference']) ?></p>
        </div>
        <a href="refund.php">Process another refund</a>

    <?php else: ?>
        <?php if ($errorMessage): ?>
            <div class="error">
                <strong>Error:</strong> <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <div class="info">
            <strong>Note:</strong> You need the original transaction reference to process a refund.
            This information is provided by Cecabank in the payment response.
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="order_number">Order Number:</label>
                <input type="text"
                       id="order_number"
                       name="order_number"
                       placeholder="e.g., ORDER-123456"
                       required>
            </div>

            <div class="form-group">
                <label for="amount">Amount to Refund:</label>
                <input type="text"
                       id="amount"
                       name="amount"
                       placeholder="e.g., 25.99"
                       required>
            </div>

            <div class="form-group">
                <label for="reference">Transaction Reference:</label>
                <input type="text"
                       id="reference"
                       name="reference"
                       placeholder="e.g., 123456789012"
                       required>
                <small style="color: #666;">
                    This is the 'Referencia' field from the original payment response
                </small>
            </div>

            <button type="submit">Process Refund</button>
        </form>

        <p style="margin-top: 30px;">
            <a href="payment-form.php">← Back to payment form</a>
        </p>
    <?php endif; ?>
</body>
</html>

