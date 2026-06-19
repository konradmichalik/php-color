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

namespace KonradMichalik\Color;

use KonradMichalik\Color\Hashing\{Crc32Strategy, HashStrategy, Sha256HslStrategy};

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
