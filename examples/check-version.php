<?php

/**
 * Example: Checking Library Version
 *
 * This example shows how to check the current version
 * of the Cecabank PHP Client library.
 */

require '../vendor/autoload.php';

use Cecabank\Client;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cecabank Client - Version Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .version-box {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .version-number {
            font-size: 48px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: left;
        }
        .info h3 {
            margin-top: 0;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <h1>Cecabank PHP Client</h1>

    <div class="version-box">
        <p>Current Version</p>
        <div class="version-number">v<?= Client::VERSION ?></div>
        <p style="color: #666;">
            Following <a href="https://semver.org/" target="_blank">Semantic Versioning</a>
        </p>
    </div>

    <div class="info">
        <h3>ℹ️ About Semantic Versioning</h3>
        <p>Version numbers follow the <strong>MAJOR.MINOR.PATCH</strong> format:</p>
        <ul>
            <li><strong>MAJOR</strong>: Incompatible API changes</li>
            <li><strong>MINOR</strong>: New backwards-compatible functionality</li>
            <li><strong>PATCH</strong>: Backwards-compatible bug fixes</li>
        </ul>
    </div>

    <div class="info">
        <h3>📋 Version Information</h3>
        <p><strong>PHP Version:</strong> <?= PHP_VERSION ?></p>
        <p><strong>Library Version:</strong> <?= Client::VERSION ?></p>
        <p><strong>License:</strong> GPL-3.0-or-later</p>
    </div>

    <?php
    // You can also use the version programmatically
    $version = Client::VERSION;
    list($major, $minor, $patch) = explode('.', $version);
    ?>

    <div class="info">
        <h3>🔍 Version Breakdown</h3>
        <p><strong>Major:</strong> <?= $major ?></p>
        <p><strong>Minor:</strong> <?= $minor ?></p>
        <p><strong>Patch:</strong> <?= $patch ?></p>
    </div>

    <p style="margin-top: 30px;">
        <a href="https://github.com/cecabank/cecabank-php/blob/main/CHANGELOG.md">View Changelog</a>
    </p>
</body>
</html>

