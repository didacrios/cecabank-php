# CI/CD Pipeline

Este documento describe el pipeline de Integración Continua y Despliegue Continuo (CI/CD) del proyecto.

## 🎯 Objetivo

Garantizar la calidad del código mediante tests automáticos, validaciones y checks en cada cambio.

## 📋 Workflows

### 1️⃣ Tests Automáticos

**Archivo:** `.github/workflows/tests.yml`

**Triggers:**
- Push a `main` o `develop`
- Pull Requests a `main` o `develop`

**Matriz de Tests:**

| PHP Version | Status |
|-------------|--------|
| 7.4 | ✅ |
| 8.0 | ✅ |
| 8.1 | ✅ |
| 8.2 | ✅ |
| 8.3 | ✅ |

**Pasos:**
1. Checkout del código
2. Setup de PHP con extensión simplexml
3. Validación de composer.json
4. Cache de dependencias
5. Instalación de dependencias
6. Ejecución de tests
7. Generación de report

### 2️⃣ Code Quality

**Archivo:** `.github/workflows/code-quality.yml`

**Triggers:**
- Push a `main` o `develop`
- Pull Requests a `main` o `develop`

**Checks:**
- ✅ Validación estricta de composer.json
- ✅ Formato de composer.json (normalize)
- ✅ Auditoría de seguridad (`composer audit`)

### 3️⃣ Coverage

**Archivo:** `.github/workflows/coverage.yml`

**Triggers:**
- Push a `main`
- Pull Requests a `main`

**Funcionalidad:**
- Ejecuta tests con Xdebug
- Genera reporte de cobertura
- Muestra estadísticas en el PR

## 🔧 Configuración

### Requisitos

Los workflows requieren:
- PHP >= 7.4
- Extensión `ext-simplexml`
- Composer v2

### Cache

Se usa cache para:
- Dependencias de Composer
- Reduce tiempo de ejecución
- Mejora eficiencia

```yaml
- name: 💾 Cache Composer dependencies
  uses: actions/cache@v4
  with:
    path: ${{ steps.composer-cache.outputs.dir }}
    key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
```

## 🚦 Status Checks

### Branch Protection

Recomendado activar en GitHub:

```yaml
Required status checks:
  - test (PHP 7.4)
  - test (PHP 8.0)
  - test (PHP 8.1)
  - test (PHP 8.2)
  - test (PHP 8.3)
  - code-quality
```

### Configuración en GitHub

1. Ve a `Settings` → `Branches`
2. Add rule para `main`
3. Selecciona:
   - ☑️ Require status checks to pass
   - ☑️ Require branches to be up to date
   - ☑️ Selecciona los workflows necesarios

## 📊 Monitorización

### Ver Resultados

Los resultados de los workflows se pueden ver en:

```
https://github.com/YOUR_USERNAME/cecabank-php/actions
```

### Badges

Los badges se actualizan automáticamente en el README:

```markdown
[![Tests](https://github.com/YOUR_USERNAME/cecabank-php/workflows/Tests/badge.svg)]()
```

## 🤖 Dependabot

**Archivo:** `.github/dependabot.yml`

**Configuración:**
- Updates mensuales de Composer
- Updates mensuales de GitHub Actions
- Auto-asignación de reviewers
- Labels automáticas

**Tipos de PRs:**
- `dependencies, composer` - Actualización de dependencias PHP
- `dependencies, github-actions` - Actualización de Actions

## 🔄 Proceso de PR

### Automático

1. **Crear PR** → Workflows se ejecutan automáticamente
2. **Tests pasan** → Badge verde ✅
3. **Tests fallan** → Badge rojo ❌
4. **Review aprobado** → Merge permitido

### Manual (Opciones)

Si necesitas re-ejecutar:

1. Ve a la pestaña `Actions`
2. Selecciona el workflow
3. Click en `Re-run jobs`

## 📈 Mejoras Futuras

### Posibles Adiciones

1. **PHP CS Fixer**
```yaml
- name: Check code style
  run: ./vendor/bin/php-cs-fixer fix --dry-run
```

2. **PHPStan** (Static Analysis)
```yaml
- name: Static Analysis
  run: ./vendor/bin/phpstan analyse
```

3. **Mutation Testing** (Infection)
```yaml
- name: Mutation Tests
  run: ./vendor/bin/infection
```

4. **Deploy Automático**
```yaml
- name: Deploy to Packagist
  if: github.ref == 'refs/tags/*'
  run: # Deploy logic
```

## 🐛 Troubleshooting

### Tests fallan localmente pero pasan en CI

```bash
# Limpiar cache
composer clear-cache
rm -rf vendor/
composer install

# Ejecutar tests
./vendor/bin/phpunit
```

### Cache problems

```bash
# En GitHub Actions, puedes limpiar cache desde:
Settings → Actions → Caches
```

### Timeout en workflows

Si un workflow tarda mucho:

```yaml
# Aumentar timeout
jobs:
  test:
    timeout-minutes: 30  # Default: 360
```

## 📚 Recursos

- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [Composer Security](https://getcomposer.org/doc/articles/handling-private-packages.md)
- [PHPUnit Documentation](https://phpunit.de/)
- [Setup PHP Action](https://github.com/shivammathur/setup-php)

## 💡 Tips

1. **Tests rápidos:** Usa cache para dependencias
2. **Matriz inteligente:** Solo testa versiones críticas en PRs
3. **Parallel execution:** GitHub Actions ejecuta jobs en paralelo
4. **Fail fast:** `fail-fast: false` para ver todos los fallos

## 🎓 Aprendizaje

Para entender mejor los workflows:

1. Lee cada workflow line by line
2. Prueba cambios en una rama de test
3. Observa los logs en GitHub Actions
4. Experimenta con la matriz de tests

---

**Última actualización:** 2024-11-11

