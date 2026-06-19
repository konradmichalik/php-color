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

    public function __toString(): string
    {
        return sprintf('rgb(%d, %d, %d)', $this->red, $this->green, $this->blue);
    }

    private function assertChannel(string $channel, int $value): void
    {
        if ($value < 0 || $value > 255) {
            throw InvalidColorValue::forRgbChannel($channel, $value);
        }
    }
}
