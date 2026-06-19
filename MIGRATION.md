# Migration

How the three source extensions map onto this library. Replace the local
utility classes with calls to `KonradMichalik\Color\*`.

## `typo3-environment-indicator` — `Utility/ColorUtility`

| Old | New |
| --- | --- |
| `ColorUtility::hexToRgb($hex)` | `Color::fromHex($hex)->toRgb()` |
| `ColorUtility::hslToRgb($h, $s, $l)` | `Color::fromHsl($h, $s, $l)->toRgb()` |
| `ColorUtility::colorToRgb($value)` | `Color::fromHex($value)->toRgb()` |
| `ColorUtility::getOptimalTextColor($bg)` | `Color::fromHex($bg)->optimalTextColor()->toHex()` |
| `ColorUtility::getColoredString($string)` | `ColorHasher::hsl()->hash($string)->toHex()` |

## `typo3-letter-avatar` — `Service/Colorize`

| Old | New |
| --- | --- |
| `Colorize::stringToColor($string)` | `ColorHasher::crc32()->hash($string)->toHex()` |

> The CRC32 strategy reproduces the original `crc32 → hex` behaviour. If a visual
> change is acceptable, prefer `ColorHasher::hsl()` for more balanced colors.

## `typo3-backend-themes` — `CssGenerator`

| Old | New |
| --- | --- |
| `CssGenerator::isDarkColor($hex)` | `Color::fromHex($hex)->isDark()` |
| hex validation (`HEX_COLOR_PATTERN`) | `Color::fromHex($hex)` (throws `InvalidColorValue` on failure) |
| luminance-based text color | `Color::fromHex($hex)->optimalTextColor()->toHex()` |

## Notes

* All conversions are immutable — methods return new instances.
* Invalid input throws `KonradMichalik\Color\Exception\InvalidColorValue`
  (implements `KonradMichalik\Color\Exception\Exception`), so wrap parsing of
  user-provided values in a `try/catch` where the old code returned a fallback.
