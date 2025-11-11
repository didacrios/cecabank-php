# Guia de Publicació de Versions

Aquest document explica com publicar noves versions de la llibreria Cecabank PHP Client.

## Semantic Versioning

Aquest projecte segueix [Semantic Versioning 2.0.0](https://semver.org/lang/ca/):

- **MAJOR** (X.0.0): Canvis incompatibles amb versions anteriors
- **MINOR** (0.X.0): Nova funcionalitat compatible amb versions anteriors
- **PATCH** (0.0.X): Correccions de bugs compatibles amb versions anteriors

## Process de Release

### 1. Actualitzar el codi

Assegura't que tots els canvis estan commited i que els tests passen:

```bash
./vendor/bin/phpunit
```

### 2. Actualitzar la versió

Actualitza els següents fitxers:

**`src/Cecabank/Client.php`**
```php
public const VERSION = '1.1.0';
```

**`README.md`**
```markdown
[![Version](https://img.shields.io/badge/version-1.1.0-blue)]()
```

### 3. Actualitzar el CHANGELOG

Edita `CHANGELOG.md` seguint el format [Keep a Changelog](https://keepachangelog.com/ca/1.0.0/):

```markdown
## [1.1.0] - 2024-11-15

### Afegit
- Nova funcionalitat X
- Nova funcionalitat Y

### Canviat
- Millora en Z

### Corregit
- Bug fix en W

[1.1.0]: https://github.com/user/cecabank-php/compare/v1.0.0...v1.1.0
```

### 4. Commit dels canvis

```bash
git add .
git commit -m "Bump version to 1.1.0"
```

### 5. Crear el tag

```bash
git tag -a v1.1.0 -m "Release version 1.1.0"
```

### 6. Pujar els canvis

```bash
git push origin main
git push origin v1.1.0
```

### 7. Publicar a Packagist

Si és la primera vegada:
1. Ves a https://packagist.org/packages/submit
2. Enganxa l'URL del repositori GitHub
3. Activa l'auto-update hook

Per a versions posteriors, Packagist detectarà automàticament el nou tag.

## Exemples de Versions

### Patch Release (Bug Fix)
- `1.0.0` → `1.0.1`
- Només correccions de bugs
- Compatible amb versions anteriors

### Minor Release (Nova funcionalitat)
- `1.0.1` → `1.1.0`
- Nova funcionalitat compatible
- No trenca codi existent

### Major Release (Breaking Changes)
- `1.1.0` → `2.0.0`
- Canvis incompatibles
- Pot requerir actualització de codi dels usuaris

## Checklist abans de Release

- [ ] Tots els tests passen
- [ ] CHANGELOG.md actualitzat
- [ ] VERSION constant actualitzada a Client.php
- [ ] README.md actualitzat amb nova versió
- [ ] Tests de versió passen (`ClientVersionTest.php`)
- [ ] Documentació revisada
- [ ] Exemples verificats
- [ ] No hi ha codi de debug o TODOs pendents

## Revertir una Release

Si necessites revertir un tag:

```bash
git tag -d v1.1.0
git push origin :refs/tags/v1.1.0
```

Després contacta amb Packagist per eliminar la versió si és necessari.

