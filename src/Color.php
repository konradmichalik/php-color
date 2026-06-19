<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "konradmichalik/color".
 *
 * Copyright (C) 2026 Konrad Michalik <hej@konradmichalik.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 */

namespace KonradMichalik\Color;

use KonradMichalik\Color\Exception\InvalidColorValue;
use Stringable;

/**
 * Immutable color value object with conversion, luminance and contrast helpers.
 *
 * RGB is the canonical internal representation; HSL and hex are derived on demand.
 */
final readonly class Color implements Stringable
{
    private function __construct(
        private Rgb $rgb,
    ) {}

    public static function fromRgb(int $red, int $green, int $blue): self
    {
        return new self(new Rgb($red, $green, $blue));
    }

    public static function fromRgbObject(Rgb $rgb): self
    {
        return new self($rgb);
    }

    /**
     * Accepts "#aabbcc", "aabbcc", "#abc" and "abc" (case-insensitive).
     */
    public static function fromHex(string $hex): self
    {
        $normalized = ltrim(trim($hex), '#');

        if (preg_match('/^[0-9a-fA-F]{3}$/', $normalized) === 1) {
            $normalized = $normalized[0] . $normalized[0]
                . $normalized[1] . $normalized[1]
                . $normalized[2] . $normalized[2];
        }

        if (preg_match('/^[0-9a-fA-F]{6}$/', $normalized) !== 1) {
            throw InvalidColorValue::forHex($hex);
        }

        return new self(new Rgb(
            (int) hexdec(substr($normalized, 0, 2)),
            (int) hexdec(substr($normalized, 2, 2)),
            (int) hexdec(substr($normalized, 4, 2)),
        ));
    }

    public static function fromHsl(float $hue, float $saturation, float $lightness): self
    {
        return self::fromHslObject(new Hsl(fmod(fmod($hue, 360.0) + 360.0, 360.0), $saturation, $lightness));
    }

    public static function fromHslObject(Hsl $hsl): self
    {
        $h = $hsl->hue / 360.0;
        $s = $hsl->saturation / 100.0;
        $l = $hsl->lightness / 100.0;

        if ($s === 0.0) {
            $value = (int) round($l * 255);

            return new self(new Rgb($value, $value, $value));
        }

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - ($l * $s);
        $p = (2 * $l) - $q;

        return new self(new Rgb(
            (int) round(self::hueToChannel($p, $q, $h + 1 / 3) * 255),
            (int) round(self::hueToChannel($p, $q, $h) * 255),
            (int) round(self::hueToChannel($p, $q, $h - 1 / 3) * 255),
        ));
    }

    public function toRgb(): Rgb
    {
        return $this->rgb;
    }

    public function toHex(): string
    {
        return sprintf('#%02x%02x%02x', $this->rgb->red, $this->rgb->green, $this->rgb->blue);
    }

    public function toHsl(): Hsl
    {
        $r = $this->rgb->red / 255;
        $g = $this->rgb->green / 255;
        $b = $this->rgb->blue / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max === $min) {
            return new Hsl(0.0, 0.0, $l * 100);
        }

        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

        $h = match ($max) {
            $r => ($g - $b) / $d + ($g < $b ? 6 : 0),
            $g => ($b - $r) / $d + 2,
            default => ($r - $g) / $d + 4,
        };

        return new Hsl(($h / 6) * 360, $s * 100, $l * 100);
    }

    /**
     * Relative luminance per WCAG 2.x, in range 0.0 (black) to 1.0 (white).
     */
    public function relativeLuminance(): float
    {
        return 0.2126 * $this->linearize($this->rgb->red)
            + 0.7152 * $this->linearize($this->rgb->green)
            + 0.0722 * $this->linearize($this->rgb->blue);
    }

    /**
     * WCAG 2.x contrast ratio between this color and another, in range 1.0 to 21.0.
     */
    public function contrastRatio(self $other): float
    {
        $a = $this->relativeLuminance();
        $b = $other->relativeLuminance();
        $lighter = max($a, $b);
        $darker = min($a, $b);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    public function isDark(float $threshold = 0.5): bool
    {
        return $this->relativeLuminance() < $threshold;
    }

    public function isLight(float $threshold = 0.5): bool
    {
        return !$this->isDark($threshold);
    }

    /**
     * Returns whichever candidate has the higher contrast against this color.
     * Defaults to black vs. white, making it ideal for readable text on a background.
     */
    public function optimalTextColor(?self $dark = null, ?self $light = null): self
    {
        $dark ??= self::fromRgb(0, 0, 0);
        $light ??= self::fromRgb(255, 255, 255);

        return $this->contrastRatio($dark) >= $this->contrastRatio($light) ? $dark : $light;
    }

    public function withLightness(float $lightness): self
    {
        $hsl = $this->toHsl();

        return self::fromHsl($hsl->hue, $hsl->saturation, $lightness);
    }

    public function equals(self $other): bool
    {
        return $this->rgb->equals($other->rgb);
    }

    public function __toString(): string
    {
        return $this->toHex();
    }

    private static function hueToChannel(float $p, float $q, float $t): float
    {
        $t = fmod(fmod($t, 1.0) + 1.0, 1.0);

        return match (true) {
            $t < 1 / 6 => $p + ($q - $p) * 6 * $t,
            $t < 1 / 2 => $q,
            $t < 2 / 3 => $p + ($q - $p) * (2 / 3 - $t) * 6,
            default => $p,
        };
    }

    private function linearize(int $channel): float
    {
        $c = $channel / 255;

        return $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;
    }
}
