# AGENTS.md

## Project overview

`konradmichalik/php-color` is a small, framework-agnostic PHP library for color conversion (hex, RGB, HSL), luminance, contrast and deterministic string-to-color hashing. Composer package type `library`.

- PHP `~8.2 || ~8.3 || ~8.4 || ~8.5` (Composer platform pinned to 8.2)
- No runtime dependencies
- Namespace `KonradMichalik\Color\` maps to `src/`, tests use `KonradMichalik\Color\Tests\` in `tests/src/`

## Structure

- `src/Color.php`, `src/Rgb.php`, `src/Hsl.php`: color value objects and conversions
- `src/ColorHasher.php`: string-to-color hashing
- `src/Hashing/`: `HashStrategy` interface with `Crc32Strategy` and `Sha256HslStrategy`
- `src/Exception/`: `Exception` and `InvalidColorValue`
- `tests/src/`: PHPUnit tests (`ColorTest`, `RgbTest`, `HslTest`, `ColorHasherTest`)
- `.github/workflows/`: `cgl.yml`, `tests.yml`, `release.yml`

## Development commands

```bash
composer install

composer lint            # lint:composer, lint:editorconfig, lint:php
composer fix             # fix:composer, fix:editorconfig, fix:php
composer sca             # phpstan analyse --memory-limit=2G
composer test            # phpunit without coverage
composer test:coverage   # phpunit with coverage, reports in .build/coverage
composer migration       # rector process -c rector.php
```

## Testing

- PHPUnit 11 or 12, config in `phpunit.xml`, suite `tests/src`. Risky tests and warnings fail the run
- Coverage reports go to `.build/coverage` (Clover, HTML, JUnit)
- CI (`.github/workflows/tests.yml`) calls a reusable workflow from `konradmichalik/reusable-github-actions` on every push, across PHP 8.2 to 8.5 with `highest` and `lowest` dependencies. `cgl.yml` calls the matching reusable CGL workflow, whose steps are defined in that repository
- Write a test for new behavior and for every bug fix

## Code style and linting

- PHP CS Fixer with `konradmichalik/php-cs-fixer-preset` (`.php-cs-fixer.php`), file header generated from `composer.json`
- PHPStan at level `max` over `src` and `tests/src`, with `phpstan-phpunit`
- Rector (`rector.php`) with `UP_TO_PHP_82` plus dead code, code quality and type declaration sets. Run it on demand via `composer migration`, it is not part of `composer lint`
- `composer normalize` keeps `composer.json` sorted (`ergebnis/composer-normalize`)
- EditorConfig (`ec` via `armin/editorconfig-cli`): UTF-8, LF, 4 spaces, tabs in JSON and NEON, 2 spaces in YAML
- Every PHP file starts with `declare(strict_types=1);` and the license header

## Git workflow

- Commit format: `<type>: <description>` with type one of `feat`, `fix`, `refactor`, `docs`, `test`, `chore`, `perf`, `ci`
- Describe the change, not what prompted it
- No co-author trailers
- Pull requests should describe the change and ideally reference an issue
