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
 * Immutable HSL color representation.
 *
 * Hue is given in degrees (0-360), saturation and lightness in percent (0-100).
 */
final readonly class Hsl implements Stringable
{
    public function __construct(
        public float $hue,
        public float $saturation,
        public float $lightness,
    ) {
        $this->assertRange('hue', $hue, 0.0, 360.0);
        $this->assertRange('saturation', $saturation, 0.0, 100.0);
        $this->assertRange('lightness', $lightness, 0.0, 100.0);
    }

    /**
     * @return array{float, float, float}
     */
    public function toArray(): array
    {
        return [$this->hue, $this->saturation, $this->lightness];
    }

    public function __toString(): string
    {
        return sprintf('hsl(%s, %s%%, %s%%)', round($this->hue), round($this->saturation), round($this->lightness));
    }

    private function assertRange(string $component, float $value, float $min, float $max): void
    {
        if ($value < $min || $value > $max) {
            throw InvalidColorValue::forHslComponent($component, $value, $min, $max);
        }
    }
}
