<?php

declare(strict_types=1);

/*
 * This file is part of the "php-color" Composer package.
 *
 * (c) Konrad Michalik <hej@konradmichalik.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KonradMichalik\Color\Hashing;

use KonradMichalik\Color\Color;

use function sprintf;

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
