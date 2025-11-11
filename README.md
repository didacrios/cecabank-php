<div align="center">

# 💳 Cecabank PHP Client

[![Version](https://img.shields.io/badge/version-1.0.0-blue?style=flat-square)]()
[![Tests](https://github.com/didacrios/cecabank-php/workflows/Tests/badge.svg)](https://github.com/didacrios/cecabank-php/actions?query=workflow%3ATests)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen?style=flat-square)](https://phpstan.org/)
[![Code Quality](https://github.com/didacrios/cecabank-php/workflows/Code%20Quality/badge.svg)](https://github.com/didacrios/cecabank-php/actions?query=workflow%3A%22Code+Quality%22)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue?style=flat-square)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-GPL--3.0-blue?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0.html)

**Cliente PHP nativo para la pasarela de pago Cecabank (TPV Virtual)**

Framework-agnostic · PHP 7.4+ · GPL-3.0-or-later

[Instalación](#-instalación) • [Uso](#-uso-básico) • [Ejemplos](examples/) • [Tests](#-tests) • [Changelog](CHANGELOG.md)

</div>

---

## ✨ Características

<table>
<tr>
<td width="50%">

**🔧 Desarrollo**
- ✅ Framework-agnostic
- ✅ PHP 7.4+ compatible
- ✅ PSR-4 Autoloading
- ✅ Composer ready

</td>
<td width="50%">

**🔐 Seguridad**
- ✅ Firmas SHA1/SHA256
- ✅ Validación de transacciones
- ✅ Excepciones tipadas
- ✅ GPL-3.0 licensed

</td>
</tr>
<tr>
<td>

**💰 Funcionalidades**
- ✅ Pagos online
- ✅ Devoluciones (refunds)
- ✅ 15+ monedas
- ✅ Test & Producción

</td>
<td>

**📦 Calidad**
- ✅ Tests unitarios (15 tests)
- ✅ Documentación completa
- ✅ Ejemplos prácticos
- ✅ Semantic Versioning

</td>
</tr>
</table>

## 📦 Instalación

Instala la librería vía Composer:

```bash
composer require didacrios/cecabank-php
```

> [!NOTE]
> Esta librería requiere PHP 7.4 o superior y la extensión `ext-simplexml`.

## 🚀 Inicio Rápido

<details>
<summary><b>Ver ejemplo completo de configuración</b></summary>

### Configuración Básica

```php
<?php

require 'vendor/autoload.php';

use Cecabank\Client;

$client = new Client([
    'Environment' => 'test', // 'test' o 'real'
    'ClaveCifrado' => 'tu_clave_secreta',
    'MerchantID' => '123456789',
    'AcquirerBIN' => '0000000000',
    'TerminalID' => '00000001',
    'TipoMoneda' => '978', // EUR
    'Exponente' => '2',
    'Cifrado' => 'SHA2', // 'SHA1' o 'SHA2'
    'Pago_soportado' => 'SSL'
]);
```

</details>

## 💻 Uso Básico

### 1. Crear un Formulario de Pago

```php
// Preparar los datos del pago
$client->setFormHiddens([
    'Num_operacion' => '000001', // Número único de operación
    'Importe' => '10.50', // Importe (se convertirá a 1050)
    'URL_OK' => 'https://tusitio.com/pago-exitoso',
    'URL_NOK' => 'https://tusitio.com/pago-error',
    'Descripcion' => 'Compra en MiTienda',
    'datos_acs_20' => '' // Datos adicionales para 3D Secure 2.0
]);

// Obtener los campos hidden como HTML
$hiddenFields = $client->getFormHiddens();

// O como array
$fieldsArray = $client->getFormHiddensAsArray();
```

### 2. Mostrar el Formulario de Pago

```php
<form method="POST" action="<?= $client->getPath() ?>">
    <?= $client->getFormHiddens() ?>
    <button type="submit">Pagar</button>
</form>
```

### 3. Validar la Respuesta del TPV

> [!IMPORTANT]
> Cuando Cecabank redirige al usuario de vuelta a tu sitio, **siempre valida la firma** para evitar fraudes:

```php
try {
    $client->checkTransaction($_POST);

    // Verificar si el pago fue exitoso
    if ($_POST['Descripcion'] === $client->successCode()) {
        echo "Pago exitoso!";
        // Actualizar tu base de datos, enviar emails, etc.
    } else {
        echo "Pago rechazado: " . $_POST['Descripcion'];
    }

} catch (\Cecabank\Exceptions\InvalidSignatureException $e) {
    echo "Error: Firma no válida";
} catch (\Cecabank\Exceptions\InvalidTransactionException $e) {
    echo "Error: Transacción inválida";
}
```

### 4. Realizar una Devolución (Refund)

```php
$result = $client->refund([
    'Num_operacion' => '000001',
    'Importe' => '10.50',
    'Referencia' => '123456789012' // Referencia de la transacción original
]);

if ($result) {
    echo "Devolución realizada correctamente";
} else {
    echo "Error al realizar la devolución";
}
```

## 🌍 Monedas Soportadas

<details>
<summary><b>Ver todas las monedas disponibles (click para expandir)</b></summary>

La librería incluye soporte para múltiples monedas:

```php
$currencyCode = $client->getCurrencyCode('EUR'); // Retorna '978'
$currencyCode = $client->getCurrencyCode('USD'); // Retorna '840'
$currencyCode = $client->getCurrencyCode('GBP'); // Retorna '826'
```

| Moneda | Código ISO | Código Cecabank |
|--------|-----------|-----------------|
| 🇪🇺 EUR | EUR | 978 |
| 🇺🇸 USD | USD | 840 |
| 🇬🇧 GBP | GBP | 826 |
| 🇦🇺 AUD | AUD | 36 |
| 🇨🇦 CAD | CAD | 124 |
| 🇨🇳 CNY | CNY | 156 |
| 🇨🇿 CZK | CZK | 203 |
| 🇩🇰 DKK | DKK | 208 |
| 🇯🇵 JPY | JPY | 392 |
| 🇲🇽 MXN | MXN | 484 |
| 🇳🇴 NOK | NOK | 578 |
| 🇷🇺 RUB | RUB | 643 |
| 🇸🇪 SEK | SEK | 752 |
| 🇨🇭 CHF | CHF | 756 |
| 🇷🇴 RON | RON | 946 |
| 🇵🇱 PLN | PLN | 985 |

Y más: ARS, CLP, COP, INR, PEN, BRL, VEF, TRY.

</details>

## Gestión de Importes

La librería convierte automáticamente los importes:

```php
$client->getAmount('10.50');  // Retorna '1050'
$client->getAmount('10,50');  // Retorna '1050'
$client->getAmount(15);       // Retorna '1500'
```

## 🚨 Excepciones

La librería lanza excepciones tipadas para facilitar el manejo de errores:

| Excepción | Cuándo se lanza |
|-----------|-----------------|
| `InvalidConfigurationException` | Configuración incorrecta o incompleta |
| `InvalidSignatureException` | Firma no válida (posible fraude) |
| `InvalidTransactionException` | Datos de transacción incorrectos |
| `CecabankException` | Excepción base (otras situaciones) |

```php
use Cecabank\Exceptions\InvalidConfigurationException;
use Cecabank\Exceptions\InvalidSignatureException;

try {
    $client = new Client($config);
    $client->checkTransaction($_POST);
} catch (InvalidConfigurationException $e) {
    // Manejar error de configuración
} catch (InvalidSignatureException $e) {
    // Manejar error de firma
}
```

## 🌐 Entornos

| Entorno | Configuración | URL |
|---------|---------------|-----|
| 🧪 **Test** | `'Environment' => 'test'` | `https://tpv.ceca.es/tpvweb/tpv/compra.action` |
| 🚀 **Producción** | `'Environment' => 'real'` | `https://pgw.ceca.es/tpvweb/tpv/compra.action` |

> [!WARNING]
> Asegúrate de usar el entorno `test` durante el desarrollo y cambiar a `real` solo en producción.

## 📚 Ejemplos Completos

Puedes encontrar ejemplos completos de uso en el directorio [`examples/`](examples/).

| Ejemplo | Descripción |
|---------|-------------|
| 💳 [`payment-form.php`](examples/payment-form.php) | Crear un formulario de pago |
| ✅ [`payment-response.php`](examples/payment-response.php) | Validar la respuesta del TPV |
| 💰 [`refund.php`](examples/refund.php) | Realizar devoluciones |
| 📌 [`check-version.php`](examples/check-version.php) | Verificar la versión de la librería |

## 🧪 Tests

Ejecutar los tests:

```bash
composer install
./vendor/bin/phpunit
```

**Cobertura actual:** 15 tests, 22 assertions ✅

## 📋 Requisitos

- ![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?style=flat-square&logo=php&logoColor=white) PHP >= 7.4
- 📦 ext-simplexml

## 📦 Versionado

Este proyecto sigue [Semantic Versioning 2.0.0](https://semver.org/lang/es/).

**Versión actual:** ![Version](https://img.shields.io/badge/v1.0.0-blue?style=flat-square)

<details>
<summary><b>¿Qué significa cada número?</b></summary>

| Versión | Cuándo incrementar |
|---------|-------------------|
| **MAJOR** (1.x.x) | Cambios incompatibles con versiones anteriores |
| **MINOR** (x.1.x) | Nueva funcionalidad compatible con versiones anteriores |
| **PATCH** (x.x.1) | Corrección de bugs compatible con versiones anteriores |

### Obtener la versión

```php
echo Cecabank\Client::VERSION; // "1.0.0"
```

</details>

### 📝 Historial de cambios

Ver [CHANGELOG.md](CHANGELOG.md) para el historial completo de cambios.

## ⚖️ Licencia

Este proyecto está licenciado bajo **GNU General Public License v3.0 o posterior** (GPL-3.0-or-later).

> [!IMPORTANT]
> ### Sobre la Licencia GPL v3
>
> Este es un **trabajo derivado** basado en los plugins oficiales de Cecabank para diferentes plataformas (WooCommerce, PrestaShop, Magento, osCommerce, GiveWP), que están licenciados bajo GPL v3.

<details>
<summary><b>Copyright y detalles de licencia</b></summary>

### Copyright

- © 2012-2024 Cecabank (código original)
- © 2024 Dídac Rios (modificaciones y librería standalone)

### ¿Qué significa GPL v3?

| Permiso | Descripción |
|---------|-------------|
| ✅ Uso comercial | Puedes usar este código en proyectos comerciales |
| ✅ Modificación | Puedes modificar el código libremente |
| ✅ Distribución | Puedes distribuir copias |
| ⚠️ Copyleft | **Tu proyecto también debe ser GPL v3** |
| ⚠️ Código fuente | Debes proporcionar el código fuente |

> [!WARNING]
> Si usas esta librería en tu proyecto, **tu proyecto completo debe ser GPL v3** y debes proporcionar el código fuente.

### Más información

- 📄 [LICENSE](LICENSE) - Texto completo de GPL v3
- 📋 [NOTICE](NOTICE) - Información de copyright

</details>

## 🤝 Contribuir

Las contribuciones son bienvenidas. Lee nuestra [Guía de Contribución](.github/CONTRIBUTING.md) para más detalles.

**Proceso rápido:**

1. 🍴 Fork el proyecto
2. 🌿 Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. 💾 Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Push a la rama (`git push origin feature/AmazingFeature`)
5. 🔀 Abre un Pull Request

<details>
<summary><b>Guía para contribuidores</b></summary>

### Antes de contribuir

- [ ] Los tests pasan (`./vendor/bin/phpunit`)
- [ ] El código sigue PSR-12
- [ ] Has actualizado la documentación si es necesario
- [ ] Has añadido tests para nueva funcionalidad

### Código de conducta

Por favor, sé respetuoso y constructivo en todas las interacciones.

</details>

## 💬 Soporte

Para obtener ayuda con la integración de Cecabank:

- 📘 Consulta la [documentación oficial](https://www.cecabank.es/) en la consola de Cecabank
- 📧 Contacta con el soporte técnico de Cecabank
- 🐛 [Reporta issues](../../issues) en este repositorio

## 🙏 Créditos

Esta librería extrae y moderniza el código común encontrado en los plugins oficiales de Cecabank para diferentes plataformas (PrestaShop, WooCommerce, Magento, etc.) y lo convierte en una librería standalone reutilizable.

**Repositorios originales:**
- [cecabank-woocommerce](https://github.com/cecabank/cecabank-woocommerce)
- [cecabank-prestashop](https://github.com/cecabank/cecabank-prestashop)
- [cecabank-magento](https://github.com/cecabank/cecabank-magento)
- [cecabank-oscommerce](https://github.com/cecabank/cecabank-oscommerce)
- [cecabank-givewp](https://github.com/cecabank/cecabank-givewp)

---

> [!CAUTION]
> **⚠️ Cliente NO Oficial**
>
> Este es un cliente NO oficial de Cecabank. Asegúrate de probar exhaustivamente en el entorno de pruebas antes de usar en producción.

<div align="center">

**[⬆ Volver arriba](#-cecabank-php-client)**

Made with ❤️ for the PHP community

</div>

