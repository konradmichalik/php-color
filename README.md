<div align="center">

# Color

[![Coverage](https://img.shields.io/coverallsCoverage/github/konradmichalik/php-color?logo=coveralls)](https://coveralls.io/github/konradmichalik/php-color)
[![CGL](https://img.shields.io/github/actions/workflow/status/konradmichalik/php-color/cgl.yml?label=cgl&logo=github)](https://github.com/konradmichalik/php-color/actions/workflows/cgl.yml)
[![Tests](https://img.shields.io/github/actions/workflow/status/konradmichalik/php-color/tests.yml?label=tests&logo=github)](https://github.com/konradmichalik/php-color/actions/workflows/tests.yml)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/konradmichalik/php-color/php?logo=php)](https://packagist.org/packages/konradmichalik/php-color)
[![License](https://img.shields.io/packagist/l/konradmichalik/php-color)](LICENSE)

</div>

A small, **framework-agnostic** PHP library for color conversion, luminance,
contrast and deterministic string-to-color hashing. No runtime dependencies.

## 🚀 Features

* Immutable `Color` value object with `Rgb` and `Hsl` companions
* Lossless conversion between hex, RGB and HSL
* Multi-format string parsing via `fromString()` / `tryFromString()`
* CSS `rgb()` / `rgba()` output with optional alpha channel
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

### Parsing arbitrary strings

`fromString()` accepts hex, `rgb(r, g, b)` and `hsl(h, s%, l%)` in one call —
whitespace is ignored, prefixes are case-insensitive and the HSL percent signs
are optional:

```php
Color::fromString('#ff0000');          // red
Color::fromString('f00');              // red
Color::fromString('rgb(255, 0, 0)');   // red
Color::fromString('hsl(0, 100%, 50%)'); // red
Color::fromString('HSL(0,100,50)');    // red
```

Use `tryFromString()` for fallbacks — it returns `null` instead of throwing:

```php
Color::tryFromString($userInput) ?? Color::fromHex('#cccccc');
```

### CSS output with alpha

```php
$rgb = Color::fromHex('#ff0000')->toRgb();

$rgb->toCssString();        // "rgb(255, 0, 0)"
$rgb->toCssString(0.8);     // "rgba(255, 0, 0, 0.8)"

Color::fromHex('#ff0000')->toRgbaString(0.8); // "rgba(255, 0, 0, 0.8)"
```

The model stays RGB-only; alpha is an output concern. Values outside `0.0–1.0`
throw `KonradMichalik\Color\Exception\InvalidColorValue`.

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
composer test           # PHPUnit
composer test:coverage  # PHPUnit with code coverage
composer lint           # CS, EditorConfig and composer.json
composer sca            # PHPStan (max)
composer migration      # Rector
composer fix            # apply automatic fixes
```

## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## 📄 License

This project is licensed under [GPL-2.0-or-later](LICENSE).
