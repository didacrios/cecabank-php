<?php
/**
 * Cecabank PHP Client
 *
 * @package   Cecabank
 * @copyright Copyright (C) 2012-2024 Cecabank
 * @copyright Copyright (C) 2024 Dídac Rios (modifications and standalone library)
 * @license   GPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Cecabank;

use Cecabank\Exceptions\InvalidConfigurationException;
use Cecabank\Exceptions\InvalidSignatureException;
use Cecabank\Exceptions\InvalidTransactionException;
use Exception;
use SimpleXMLElement;

class Client
{
    /**
     * Library version (Semantic Versioning)
     */
    public const VERSION = '1.0.0';

    /** @var array<string, string> */
    private array $options = [
        'Environment' => 'test',
        'TerminalID' => '1',
        'TipoMoneda' => '978',
        'Exponente' => '2',
        'Cifrado' => 'SHA1',
        'Idioma' => '1',
        'Pago_soportado' => 'SSL',
        'versionMod' => ''
    ];

    /** @var array<string, string> */
    private array $currencies = [
        'EUR' => '978',
        'AUD' => '36',
        'CAD' => '124',
        'CNY' => '156',
        'CZK' => '203',
        'DKK' => '208',
        'JPY' => '392',
        'MXN' => '484',
        'NOK' => '578',
        'RUB' => '643',
        'SEK' => '752',
        'CHF' => '756',
        'GBP' => '826',
        'USD' => '840',
        'RON' => '946',
        'PLN' => '985',
        'ARS' => '32',
        'CLP' => '152',
        'COP' => '170',
        'INR' => '356',
        'PEN' => '604',
        'BRL' => '986',
        'VEF' => '937',
        'TRY' => '949',
    ];

    /** @var array<int, string> */
    private array $o_required = [
        'Environment',
        'ClaveCifrado',
        'MerchantID',
        'AcquirerBIN',
        'TerminalID',
        'TipoMoneda',
        'Exponente',
        'Cifrado',
        'Pago_soportado'
    ];

    /** @var array<int, string> */
    private array $o_optional = [
        'Idioma',
        'Descripcion',
        'URL_OK',
        'URL_NOK',
        'Tipo_operacion',
        'Datos_operaciones',
        'PAN',
        'Caducidad',
        'CVV2',
        'Pago_elegido',
        'versionMod'
    ];

    private string $environment = '';

    /** @var array<string, string> */
    private array $environments = [
        'test' => 'https://tpv.ceca.es/tpvweb/tpv/compra.action',
        'real' => 'https://pgw.ceca.es/tpvweb/tpv/compra.action'
    ];

    private string $refund_environment = '';

    /** @var array<string, string> */
    private array $refund_environments = [
        'test' => 'https://democonsolatpvvirtual.ceca.es/webapp/ConsTpvVirtWeb/ConsTpvVirtS?modo=anularOperacionExt',
        'real' => 'https://comercios.ceca.es/webapp/ConsTpvVirtWeb/ConsTpvVirtS?modo=anularOperacionExt'
    ];

    private string $success = '$*$OKY$*$';

    /** @var array<string, mixed> */
    private array $values = [];

    /** @var array<string, mixed> */
    private array $hidden = [];

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->setOption($options);
    }

    /**
     * @param string|array<string, mixed> $option
     * @param mixed $value
     * @return $this
     */
    public function setOption(string|array $option, mixed $value = null): self
    {
        if (is_array($option)) {
            $options = $option;
        } elseif ($value !== null) {
            $options = [$option => $value];
        } else {
            throw new InvalidConfigurationException(
                sprintf('Option "%s" cannot be empty', $option)
            );
        }

        $options = array_merge($this->options, $options);

        foreach ($this->o_required as $requiredOption) {
            if (empty($options[$requiredOption])) {
                throw new InvalidConfigurationException(
                    sprintf('Option "%s" is required', $requiredOption)
                );
            }
            $this->options[$requiredOption] = $options[$requiredOption];
        }

        foreach ($this->o_optional as $optionalOption) {
            if (array_key_exists($optionalOption, $options)) {
                $this->options[$optionalOption] = $options[$optionalOption];
            }
        }

        if (isset($options['environments'])) {
            $this->environments = array_merge($this->environments, $options['environments']);
        }

        $this->setEnvironment($options['Environment']);

        return $this;
    }

    /**
     * @param string|null $key
     * @return mixed|array<string, string>
     */
    public function getOption(?string $key = null): mixed
    {
        return $key ? $this->options[$key] : $this->options;
    }

    public function setEnvironment(string $mode): self
    {
        $env = $this->getEnvironments($mode);
        $this->environment = is_string($env) ? $env : '';

        $refundEnv = $this->getRefundEnvironments($mode);
        $this->refund_environment = is_string($refundEnv) ? $refundEnv : '';

        return $this;
    }

    public function getPath(string $path = ''): string
    {
        return $this->environment . $path;
    }

    public function getRefundPath(string $path = ''): string
    {
        return $this->refund_environment . $path;
    }

    /**
     * @param string|null $key
     * @return string|array<string, string>
     */
    public function getEnvironments(?string $key = null): string|array
    {
        if (empty($this->environments[$key])) {
            $envs = implode('|', array_keys($this->environments));
            throw new InvalidConfigurationException(
                sprintf('Environment "%s" is not valid [%s]', $key, $envs)
            );
        }

        return $key ? $this->environments[$key] : $this->environments;
    }

    /**
     * @param string|null $key
     * @return string|array<string, string>
     */
    public function getRefundEnvironments(?string $key = null): string|array
    {
        if (empty($this->refund_environments[$key])) {
            $envs = implode('|', array_keys($this->refund_environments));
            throw new InvalidConfigurationException(
                sprintf('Refund Environment "%s" is not valid [%s]', $key, $envs)
            );
        }

        return $key ? $this->refund_environments[$key] : $this->refund_environments;
    }

    public function getCurrencyCode(string $currency): string
    {
        if (isset($this->currencies[$currency])) {
            return $this->currencies[$currency];
        }
        return '978';
    }

    /**
     * @param array<string, mixed> $options
     * @return $this
     */
    public function setFormHiddens(array $options): self
    {
        $this->hidden = $this->values = [];

        $options['Importe'] = $this->getAmount($options['Importe']);

        $this->setValueDefault($options, 'MerchantID', 9);
        $this->setValueDefault($options, 'AcquirerBIN', 10);
        $this->setValueDefault($options, 'TerminalID', 8);
        $this->setValueDefault($options, 'TipoMoneda');
        $this->setValueDefault($options, 'Exponente');
        $this->setValueDefault($options, 'Cifrado');
        $this->setValueDefault($options, 'Pago_soportado');
        $this->setValueDefault($options, 'versionMod');
        $this->setValueDefault($options, 'Idioma');

        $this->setValue($options, 'TipoMoneda');
        $this->setValue($options, 'Num_operacion');
        $this->setValue($options, 'Importe');
        $this->setValue($options, 'URL_OK');
        $this->setValue($options, 'URL_NOK');
        $this->setValue($options, 'Descripcion');
        $this->setValue($options, 'Tipo_operacion');
        $this->setValue($options, 'Datos_operaciones');
        $this->setValue($options, 'datos_acs_20');

        if (!empty($options['PAN'])) {
            $this->setCreditCardInputs($options);
        }

        $this->setValueLength('MerchantID', 9);
        $this->setValueLength('AcquirerBIN', 10);
        $this->setValueLength('TerminalID', 8);

        $options['Firma'] = $this->getSignature();
        $options['firma_acs_20'] = $this->makeHash($options['datos_acs_20'], false);

        $this->setValue($options, 'Firma');
        $this->setValue($options, 'firma_acs_20');

        $this->setHiddensFromValues();

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function refund(array $options): bool
    {
        $this->hidden = $this->values = [];

        $options['Importe'] = $this->getAmount($options['Importe']);

        $this->setValueDefault($options, 'MerchantID', 9);
        $this->setValueDefault($options, 'AcquirerBIN', 10);
        $this->setValueDefault($options, 'TerminalID', 8);
        $this->setValueDefault($options, 'TipoMoneda');
        $this->setValueDefault($options, 'Exponente');
        $this->setValueDefault($options, 'Cifrado');
        $this->setValueDefault($options, 'Idioma');

        $this->setValue($options, 'TipoMoneda');
        $this->setValue($options, 'Num_operacion');
        $this->setValue($options, 'Importe');
        $this->setValue($options, 'Referencia');

        if (!empty($options['TIPO_ANU'])) {
            $this->setValue($options, 'TIPO_ANU');
        }

        $this->setValueLength('MerchantID', 9);
        $this->setValueLength('AcquirerBIN', 10);
        $this->setValueLength('TerminalID', 8);

        $options['Firma'] = $this->getRefundSignature();

        $this->setValue($options, 'Firma');

        $url = $this->getRefundPath();
        $data = $this->values;

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            return false;
        }

        $xml = new SimpleXMLElement($result);
        try {
            $value = $xml->attributes()->valor;
            return $value == 'OK';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    private function setCreditCardInputs(array $options): void
    {
        $options['Pago_elegido'] = 'SSL';

        $this->setValue($options, 'PAN');
        $this->setValue($options, 'Caducidad');
        $this->setValue($options, 'CVV2');
        $this->setValue($options, 'Pago_elegido');
    }

    private function setValueLength(string $key, int $length): self
    {
        $this->values[$key] = str_pad($this->values[$key], $length, '0', STR_PAD_LEFT);

        return $this;
    }

    private function setHiddensFromValues(): self
    {
        $this->hidden = $this->values;

        return $this;
    }

    public function getFormHiddens(): string
    {
        if (empty($this->hidden)) {
            throw new InvalidConfigurationException('Form fields must be initialized previously');
        }

        $html = '';

        foreach ($this->hidden as $field => $value) {
            $html .= "\n" . '<input type="hidden" name="' . $field . '" value="' . $value . '" />';
        }

        return trim($html);
    }

    /**
     * @return array<string, mixed>
     */
    public function getFormHiddensAsArray(): array
    {
        if (empty($this->hidden)) {
            throw new InvalidConfigurationException('Form fields must be initialized previously');
        }

        return $this->hidden;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function setValueDefault(array $options, string $option, ?int $length = null): self
    {
        if (isset($options[$option])) {
            $this->values[$option] = $options[$option];
        } elseif (isset($this->options[$option])) {
            $this->values[$option] = $this->options[$option];
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    private function setValue(array $options, string $option): self
    {
        if (isset($options[$option])) {
            $this->values[$option] = $options[$option];
        }

        return $this;
    }

    /**
     * @param mixed $amount
     */
    public function getAmount(mixed $amount): string
    {
        if (empty($amount)) {
            return '000';
        }

        if (preg_match('/[\.,]/', $amount)) {
            return str_replace(['.', ','], '', $amount);
        }

        return (string)($amount * 100);
    }

    public function getSignature(): string
    {
        $fields = [
            'MerchantID',
            'AcquirerBIN',
            'TerminalID',
            'Num_operacion',
            'Importe',
            'TipoMoneda',
            'Exponente',
            'Cifrado',
            'URL_OK',
            'URL_NOK'
        ];

        $key = '';

        foreach ($fields as $field) {
            if (!isset($this->values[$field])) {
                throw new InvalidSignatureException(
                    sprintf('Field "%s" is empty and is required to create signature key', $field)
                );
            }

            $key .= $this->values[$field];
        }

        return $this->makeHash($key);
    }

    public function getRefundSignature(): string
    {
        $fields = [
            'MerchantID',
            'AcquirerBIN',
            'TerminalID',
            'Num_operacion',
            'Importe',
            'TipoMoneda',
            'Exponente',
            'Referencia',
            'Cifrado'
        ];

        $key = '';

        foreach ($fields as $field) {
            if (!isset($this->values[$field])) {
                throw new InvalidSignatureException(
                    sprintf('Field "%s" is empty and is required to create signature key', $field)
                );
            }

            $key .= $this->values[$field];
        }

        return $this->makeHash($key);
    }

    /**
     * @param array<string, mixed> $post
     */
    public function checkTransaction(array $post): string
    {
        if (empty($post) || empty($post['Firma'])) {
            throw new InvalidTransactionException('POST data is empty');
        }

        $fields = [
            'MerchantID',
            'AcquirerBIN',
            'TerminalID',
            'Num_operacion',
            'Importe',
            'TipoMoneda',
            'Exponente',
            'Referencia'
        ];

        $key = '';

        foreach ($fields as $field) {
            if (empty($post[$field])) {
                throw new InvalidTransactionException(
                    sprintf('Field "%s" is empty and is required to verify transaction', $field)
                );
            }

            $key .= $post[$field];
        }

        $signature = $this->makeHash($key);

        if ($signature !== $post['Firma']) {
            throw new InvalidSignatureException(
                sprintf('Signature not valid (%s != %s)', $signature, $post['Firma'])
            );
        }

        return $post['Firma'];
    }

    private function makeHash(string $message, bool $replace = true): string
    {
        $message = $this->options['ClaveCifrado'] . $message;

        if ($this->options['Cifrado'] === 'SHA2') {
            if ($replace) {
                $message = str_replace('&amp;', '&', $message);
                $message = str_replace('#038;', '', $message);
            }
            return hash('sha256', $message);
        }

        return sha1($message);
    }

    public function successCode(): string
    {
        return $this->success;
    }
}

