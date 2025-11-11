# Guía de Contribución

¡Gracias por tu interés en contribuir a Cecabank PHP Client! 🎉

## 📋 Tabla de Contenidos

- [Código de Conducta](#código-de-conducta)
- [Cómo Contribuir](#cómo-contribuir)
- [Proceso de Pull Request](#proceso-de-pull-request)
- [Estándares de Código](#estándares-de-código)
- [Ejecutar Tests](#ejecutar-tests)
- [Reportar Bugs](#reportar-bugs)

## 📜 Código de Conducta

Este proyecto se adhiere a un código de conducta. Al participar, se espera que mantengas un ambiente respetuoso y constructivo.

## 🤝 Cómo Contribuir

### 1. Fork del Proyecto

```bash
# Fork en GitHub y clona tu fork
git clone https://github.com/TU_USUARIO/cecabank-php.git
cd cecabank-php
```

### 2. Crea una Rama

```bash
git checkout -b feature/mi-nueva-funcionalidad
# o
git checkout -b fix/arreglar-bug
```

**Convención de nombres de ramas:**
- `feature/` - Nueva funcionalidad
- `fix/` - Corrección de bugs
- `docs/` - Cambios en documentación
- `refactor/` - Refactorización de código
- `test/` - Añadir o mejorar tests

### 3. Realiza tus Cambios

Asegúrate de:
- ✅ Escribir código limpio y legible
- ✅ Seguir los estándares de código (PSR-12)
- ✅ Añadir tests para nueva funcionalidad
- ✅ Actualizar la documentación si es necesario

### 4. Commit de Cambios

Usa mensajes de commit descriptivos siguiendo [Conventional Commits](https://www.conventionalcommits.org/):

```bash
git commit -m "feat: añadir soporte para nuevas monedas"
git commit -m "fix: corregir validación de firma"
git commit -m "docs: actualizar ejemplos de uso"
git commit -m "test: añadir tests para refunds"
```

**Prefijos de commit:**
- `feat:` - Nueva funcionalidad
- `fix:` - Corrección de bug
- `docs:` - Documentación
- `test:` - Tests
- `refactor:` - Refactorización
- `chore:` - Mantenimiento

## 🔄 Proceso de Pull Request

### Antes de Enviar

Ejecuta estos comandos para verificar que todo está correcto:

```bash
# 1. Validar composer
composer validate --strict

# 2. Instalar dependencias
composer install

# 3. Ejecutar tests
./vendor/bin/phpunit

# 4. Verificar que todos los tests pasan
./vendor/bin/phpunit --testdox
```

### Checklist para PR

Antes de enviar tu Pull Request, asegúrate de:

- [ ] ✅ Los tests pasan localmente
- [ ] ✅ Has añadido tests para tu código nuevo
- [ ] ✅ La documentación está actualizada
- [ ] ✅ No hay conflictos con la rama main
- [ ] ✅ El código sigue PSR-12
- [ ] ✅ Has actualizado CHANGELOG.md si es necesario
- [ ] ✅ El commit message es descriptivo

### Crear el Pull Request

1. Push a tu fork:
```bash
git push origin feature/mi-nueva-funcionalidad
```

2. Ve a GitHub y crea el Pull Request
3. Rellena la plantilla de PR con detalles
4. Espera la revisión del código

### Revisión de Código

- Los CI checks deben pasar (tests en múltiples versiones de PHP)
- Un maintainer revisará tu código
- Puede haber comentarios o sugerencias de mejora
- Una vez aprobado, tu PR será merged

## 📐 Estándares de Código

### PSR-12

Este proyecto sigue [PSR-12](https://www.php-fig.org/psr/psr-12/) para el estilo de código.

### Principios

- **SOLID**: Sigue los principios SOLID
- **DRY**: No repitas código (Don't Repeat Yourself)
- **KISS**: Mantén las cosas simples (Keep It Simple, Stupid)
- **Métodos cortos**: Máximo 20 líneas por método

### Ejemplo de Código

```php
<?php

namespace Cecabank;

/**
 * Descripción clara de la clase
 */
class MiClase
{
    /**
     * Descripción clara del método
     *
     * @param string $parametro Descripción del parámetro
     * @return bool
     */
    public function miMetodo(string $parametro): bool
    {
        // Código claro y legible
        return true;
    }
}
```

## 🧪 Ejecutar Tests

### Tests Unitarios

```bash
# Ejecutar todos los tests
./vendor/bin/phpunit

# Ejecutar tests con output detallado
./vendor/bin/phpunit --testdox

# Ejecutar tests con coverage
./vendor/bin/phpunit --coverage-text
```

### Escribir Tests

Sigue el formato **Given-When-Then**:

```php
public function testMetodoConCondicionesShouldReturnEsperado()
{
    // Given (Preparación)
    $client = new Client($config);

    // When (Acción)
    $result = $client->metodo();

    // Then (Verificación)
    $this->assertEquals('esperado', $result);
}
```

## 🐛 Reportar Bugs

### Antes de Reportar

1. Verifica que el bug no esté ya reportado
2. Asegúrate de usar la última versión
3. Prueba en el entorno de test

### Información a Incluir

Cuando reportes un bug, incluye:

- **Descripción clara** del problema
- **Pasos para reproducir** el bug
- **Comportamiento esperado** vs actual
- **Versión** de PHP y de la librería
- **Código de ejemplo** que reproduzca el problema
- **Stack trace** si es aplicable

### Template de Bug Report

```markdown
## Descripción
[Descripción clara del bug]

## Pasos para Reproducir
1. [Primer paso]
2. [Segundo paso]
3. [Tercer paso]

## Comportamiento Esperado
[Qué debería pasar]

## Comportamiento Actual
[Qué pasa actualmente]

## Entorno
- PHP Version: 8.2
- Librería Version: 1.0.0
- OS: Ubuntu 22.04

## Código de Ejemplo
\`\`\`php
// Código que reproduce el bug
\`\`\`
```

## 📝 Mejoras de Documentación

Las mejoras en la documentación son siempre bienvenidas:

- Corregir typos
- Mejorar explicaciones
- Añadir ejemplos
- Traducir contenido
- Actualizar información obsoleta

## ❓ Preguntas

Si tienes preguntas:

1. Revisa la [documentación](../README.md)
2. Busca en [Issues cerrados](../../issues?q=is%3Aissue+is%3Aclosed)
3. Abre un [nuevo Issue](../../issues/new) con la etiqueta `question`

## 🙏 Gracias

¡Gracias por contribuir a hacer este proyecto mejor! Cada contribución, grande o pequeña, es valorada y apreciada.

---

**Recuerda:** Este proyecto está bajo licencia GPL-3.0-or-later. Al contribuir, aceptas que tu código también esté bajo esta licencia.

