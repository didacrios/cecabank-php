# GitHub Actions Workflows

Aquest directori conté els workflows de CI/CD per al projecte Cecabank PHP Client.

## 🔄 Workflows Disponibles

### 1. Tests (`tests.yml`)

**Quan s'executa:**
- Push a `main` o `develop`
- Pull Requests a `main` o `develop`

**Què fa:**
- ✅ Executa tests en múltiples versions de PHP (7.4, 8.0, 8.1, 8.2, 8.3)
- ✅ Valida composer.json
- ✅ Cache de dependències per rapidesa
- ✅ Genera report de tests

**Matriu de tests:**
```yaml
PHP Versions: 7.4, 8.0, 8.1, 8.2, 8.3
OS: ubuntu-latest
```

### 2. Code Quality (`code-quality.yml`)

**Quan s'executa:**
- Push a `main` o `develop`
- Pull Requests a `main` o `develop`

**Què fa:**
- ✅ Valida composer.json (strict mode)
- ✅ Verifica format de composer.json
- ✅ Audita dependències per vulnerabilitats
- ✅ Genera report de qualitat

### 3. Coverage (`coverage.yml`)

**Quan s'executa:**
- Push a `main`
- Pull Requests a `main`

**Què fa:**
- ✅ Executa tests amb cobertura (Xdebug)
- ✅ Genera report de cobertura
- ✅ Mostra percentatge de cobertura

## 📊 Badges

Per afegir badges al teu README:

```markdown
[![Tests](https://github.com/YOUR_USERNAME/cecabank-php/workflows/Tests/badge.svg)](https://github.com/YOUR_USERNAME/cecabank-php/actions?query=workflow%3ATests)
[![Code Quality](https://github.com/YOUR_USERNAME/cecabank-php/workflows/Code%20Quality/badge.svg)](https://github.com/YOUR_USERNAME/cecabank-php/actions?query=workflow%3A%22Code+Quality%22)
```

**Nota:** Reemplaça `YOUR_USERNAME` amb el teu username de GitHub.

## 🔧 Configuració Local

Per simular els workflows localment:

### Tests
```bash
composer validate --strict
composer install --prefer-dist --no-progress
./vendor/bin/phpunit --testdox
```

### Coverage
```bash
composer install --prefer-dist --no-progress
./vendor/bin/phpunit --coverage-text --coverage-clover=coverage.xml
```

## 🤖 Dependabot

El fitxer `dependabot.yml` manté actualitzades:
- 📦 Dependències de Composer (mensualment)
- 🔄 GitHub Actions (mensualment)

## 🚀 Millorar els Workflows

### Afegir més versions de PHP

```yaml
matrix:
  php-version: ['7.4', '8.0', '8.1', '8.2', '8.3', '8.4']
```

### Afegir més OS

```yaml
matrix:
  os: [ubuntu-latest, windows-latest, macos-latest]
  php-version: ['8.2', '8.3']
```

### Afegir PHP CS Fixer

```yaml
- name: 🎨 Check code style
  run: ./vendor/bin/php-cs-fixer fix --dry-run --diff
```

### Afegir PHPStan

```yaml
- name: 🔍 Static Analysis
  run: ./vendor/bin/phpstan analyse src --level=max
```

## 📈 Estadístiques

- **Workflows totals:** 3
- **PHP versions testades:** 5 (7.4, 8.0, 8.1, 8.2, 8.3)
- **Temps estimat per workflow:** 1-3 minuts
- **Cache habilitada:** Sí (Composer)

## 🔒 Secrets

Aquest projecte no requereix secrets de GitHub per executar els workflows. Tot és públic i gratuït para repositoris open source.

## 📚 Recursos

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Workflow Syntax](https://docs.github.com/en/actions/reference/workflow-syntax-for-github-actions)
- [PHP Setup Action](https://github.com/shivammathur/setup-php)
- [Dependabot](https://docs.github.com/en/code-security/dependabot)

