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

use KonradMichalik\Color\Exception\InvalidColorValue;
use Stringable;

use function sprintf;

/**
 * Immutable RGB color representation, each channel in range 0-255.
 */
final readonly class Rgb implements Stringable
{
    public function __construct(
        public int $red,
        public int $green,
        public int $blue,
    ) {
        $this->assertChannel('red', $red);
        $this->assertChannel('green', $green);
        $this->assertChannel('blue', $blue);
    }

    public function __toString(): string
    {
        return sprintf('rgb(%d, %d, %d)', $this->red, $this->green, $this->blue);
    }

    /**
     * @return array{int, int, int}
     */
    public function toArray(): array
    {
        return [$this->red, $this->green, $this->blue];
    }

    public function equals(self $other): bool
    {
        return $this->red === $other->red
            && $this->green === $other->green
            && $this->blue === $other->blue;
    }

    private function assertChannel(string $channel, int $value): void
    {
        if ($value < 0 || $value > 255) {
            throw InvalidColorValue::forRgbChannel($channel, $value);
        }
    }
}
