<?php

namespace Cecabank\Tests;

use Cecabank\Client;
use Cecabank\Exceptions\InvalidConfigurationException;
use Cecabank\Exceptions\InvalidSignatureException;
use Cecabank\Exceptions\InvalidTransactionException;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private function getValidConfig(): array
    {
        return [
            'Environment' => 'test',
            'ClaveCifrado' => 'test_secret_key',
            'MerchantID' => '123456789',
            'AcquirerBIN' => '0000000000',
            'TerminalID' => '00000001',
            'TipoMoneda' => '978',
            'Exponente' => '2',
            'Cifrado' => 'SHA1',
            'Pago_soportado' => 'SSL'
        ];
    }

    public function testConstructWithValidConfigShouldCreateClient()
    {
        // Given
        $config = $this->getValidConfig();

        // When
        $client = new Client($config);

        // Then
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConstructWithMissingRequiredFieldShouldThrowException()
    {
        // Given
        $config = $this->getValidConfig();
        unset($config['MerchantID']);

        // When & Then
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Option "MerchantID" is required');

        new Client($config);
    }

    public function testGetOptionShouldReturnConfigValue()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $merchantId = $client->getOption('MerchantID');

        // Then
        $this->assertEquals('123456789', $merchantId);
    }

    public function testGetAmountWithDecimalShouldConvertCorrectly()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $amount = $client->getAmount('12.50');

        // Then
        $this->assertEquals('1250', $amount);
    }

    public function testGetAmountWithIntegerShouldConvertCorrectly()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $amount = $client->getAmount(15);

        // Then
        $this->assertEquals('1500', $amount);
    }

    public function testGetCurrencyCodeWithValidCurrencyShouldReturnCode()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $code = $client->getCurrencyCode('USD');

        // Then
        $this->assertEquals('840', $code);
    }

    public function testGetCurrencyCodeWithInvalidCurrencyShouldReturnDefault()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $code = $client->getCurrencyCode('XXX');

        // Then
        $this->assertEquals('978', $code);
    }

    public function testSetEnvironmentWithInvalidModeShouldThrowException()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When & Then
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Environment "invalid" is not valid');

        $client->setEnvironment('invalid');
    }

    public function testGetPathShouldReturnEnvironmentUrl()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $path = $client->getPath();

        // Then
        $this->assertEquals('https://tpv.ceca.es/tpvweb/tpv/compra.action', $path);
    }

    public function testSetFormHiddensShouldPrepareFormData()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        $paymentData = [
            'Num_operacion' => '000001',
            'Importe' => '10.50',
            'URL_OK' => 'https://example.com/success',
            'URL_NOK' => 'https://example.com/error',
            'Descripcion' => 'Test payment',
            'datos_acs_20' => ''
        ];

        // When
        $client->setFormHiddens($paymentData);
        $hiddens = $client->getFormHiddensAsArray();

        // Then
        $this->assertArrayHasKey('Firma', $hiddens);
        $this->assertArrayHasKey('Importe', $hiddens);
        $this->assertEquals('1050', $hiddens['Importe']);
        $this->assertEquals('000001', $hiddens['Num_operacion']);
    }

    public function testGetFormHiddensWithoutInitializationShouldThrowException()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When & Then
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Form fields must be initialized previously');

        $client->getFormHiddens();
    }

    public function testSuccessCodeShouldReturnExpectedValue()
    {
        // Given
        $config = $this->getValidConfig();
        $client = new Client($config);

        // When
        $successCode = $client->successCode();

        // Then
        $this->assertEquals('$*$OKY$*$', $successCode);
    }
}

