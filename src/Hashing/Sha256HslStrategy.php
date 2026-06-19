<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "konradmichalik/php-color".
 *
 * Copyright (C) 2026 Konrad Michalik <hej@konradmichalik.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 */

namespace KonradMichalik\Color\Hashing;

use KonradMichalik\Color\Color;

/**
 * Derives a hue from the SHA-256 hash of the input and pairs it with a fixed
 * saturation and lightness. Produces visually balanced, well-readable colors —
 * ideal for avatar backgrounds and tag colors.
 */
final readonly class Sha256HslStrategy implements HashStrategy
{
    public function __construct(
        private float $saturation = 65.0,
        private float $lightness = 50.0,
    ) {}

    public function hash(string $input): Color
    {
        $hue = hexdec(substr(hash('sha256', $input), 0, 8)) % 360;

        return Color::fromHsl((float) $hue, $this->saturation, $this->lightness);
    }
}
