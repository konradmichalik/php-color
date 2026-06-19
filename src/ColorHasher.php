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

namespace KonradMichalik\Color;

use KonradMichalik\Color\Hashing\Crc32Strategy;
use KonradMichalik\Color\Hashing\HashStrategy;
use KonradMichalik\Color\Hashing\Sha256HslStrategy;

/**
 * Turns an arbitrary string into a stable, deterministic color using a
 * pluggable strategy. The same input always yields the same color.
 */
final readonly class ColorHasher
{
    public function __construct(
        private HashStrategy $strategy,
    ) {}

    /**
     * Balanced HSL-based colors derived from SHA-256 — recommended default.
     */
    public static function hsl(float $saturation = 65.0, float $lightness = 50.0): self
    {
        return new self(new Sha256HslStrategy($saturation, $lightness));
    }

    /**
     * Lightweight CRC32-based colors.
     */
    public static function crc32(): self
    {
        return new self(new Crc32Strategy());
    }

    public function hash(string $input): Color
    {
        return $this->strategy->hash($input);
    }
}
