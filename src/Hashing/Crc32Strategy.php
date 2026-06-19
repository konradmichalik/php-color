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

namespace KonradMichalik\Color\Hashing;

use KonradMichalik\Color\Color;

/**
 * Derives a color from the lowest 24 bits of the CRC32 checksum of the input.
 * Mirrors the lightweight "crc32 to hex" approach for fast, deterministic colors.
 */
final readonly class Crc32Strategy implements HashStrategy
{
    public function hash(string $input): Color
    {
        return Color::fromHex(sprintf('#%06X', crc32($input) & 0xFFFFFF));
    }
}
