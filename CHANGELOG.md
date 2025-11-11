# Changelog

Tots els canvis notables d'aquest projecte es documentaran en aquest fitxer.

El format està basat en [Keep a Changelog](https://keepachangelog.com/ca/1.0.0/),
i aquest projecte segueix [Semantic Versioning](https://semver.org/lang/ca/).

## [Unreleased]

## [1.0.0] - 2024-11-11

### Afegit
- Client PHP natiu per a Cecabank TPV Virtual
- Suport per a entorns test i producció
- Generació automàtica de signatures (SHA1 i SHA256)
- Validació de transaccions amb verificació de signatura
- Suport per a devolucions (refunds)
- Suport per a 15+ monedes internacionals
- Excepcions tipades:
  - `CecabankException` (base)
  - `InvalidConfigurationException`
  - `InvalidSignatureException`
  - `InvalidTransactionException`
- Tests unitaris (12 tests, 18 assertions)
- Documentació completa al README
- 3 exemples pràctics d'ús:
  - Formulari de pagament
  - Validació de resposta
  - Devolucions
- Llicència GPL-3.0-or-later (compatible amb repositoris originals)
- Autoloading PSR-4
- Compatible amb PHP 7.4+

### Notes
- Primera versió estable basada en els clients oficials de Cecabank
- Extret i modernitzat del codi comú dels plugins per a WooCommerce, PrestaShop, Magento, osCommerce i GiveWP

[Unreleased]: https://github.com/didacrios/cecabank-php/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/didacrios/cecabank-php/releases/tag/v1.0.0

