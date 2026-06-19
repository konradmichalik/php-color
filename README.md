<div align="center">

# Color

[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/konradmichalik/php-color/php?logo=php)](https://packagist.org/packages/konradmichalik/php-color)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](.github/workflows/ci.yaml)
[![License](https://img.shields.io/packagist/l/konradmichalik/php-color)](LICENSE)

</div>

A small, **framework-agnostic** PHP library for color conversion, luminance,
contrast and deterministic string-to-color hashing. No runtime dependencies.

## 🚀 Features

* Immutable `Color` value object with `Rgb` and `Hsl` companions
* Lossless conversion between hex, RGB and HSL
* WCAG 2.x relative luminance and contrast ratio
* `optimalTextColor()` — pick readable text color for any background
* Deterministic string → color hashing (SHA-256/HSL or CRC32)

## 🔥 Installation

```bash
composer require konradmichalik/php-color
```

## ⚡ Usage

### Conversion

```php
use KonradMichalik\Color\Color;

$color = Color::fromHex('#3366cc');

$color->toRgb();          // Rgb(51, 102, 204)
$color->toHsl();          // Hsl(220.0, 60.0, 50.0)
$color->toHex();          // "#3366cc"

Color::fromRgb(51, 102, 204)->toHex();      // "#3366cc"
Color::fromHsl(220, 60, 50)->toHex();       // "#3366cc"
$color->withLightness(85)->toHex();         // a lighter variant
```

Short hex (`#abc`), missing `#` and surrounding whitespace are all accepted.
Invalid values throw `KonradMichalik\Color\Exception\InvalidColorValue`.

### Contrast & readable text

```php
$background = Color::fromHex('#222222');

$background->relativeLuminance();                  // 0.0185…
$background->contrastRatio(Color::fromHex('#fff')); // 15.9…
$background->isDark();                              // true
$background->optimalTextColor()->toHex();          // "#ffffff"
```

`optimalTextColor()` returns whichever candidate (black/white by default, or two
custom colors) has the higher contrast against the background.

### Deterministic colors from strings

Great for avatar backgrounds or tag colors — the same input always yields the
same color:

```php
use KonradMichalik\Color\ColorHasher;

$hasher = ColorHasher::hsl();              // balanced SHA-256 → HSL (recommended)
$hasher->hash('konrad@example.com');       // stable Color

$hasher = ColorHasher::hsl(saturation: 70, lightness: 45);
$hasher = ColorHasher::crc32();            // lightweight CRC32 → hex
```

Need a custom mapping? Implement `KonradMichalik\Color\Hashing\HashStrategy` and
pass it to `new ColorHasher($strategy)`.

## 🧪 Quality

```bash
composer test    # PHPUnit
composer check   # PHPStan (max), CS, Rector, dependency analysis
composer fix     # apply automatic fixes
```

## 📄 License

This project is licensed under [GPL-2.0-or-later](LICENSE).
