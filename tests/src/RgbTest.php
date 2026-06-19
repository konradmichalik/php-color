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

namespace KonradMichalik\Color\Tests;

use KonradMichalik\Color\Exception\InvalidColorValue;
use KonradMichalik\Color\Rgb;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RgbTest extends TestCase
{
    #[Test]
    public function acceptsBoundaryValues(): void
    {
        $rgb = new Rgb(0, 128, 255);

        self::assertSame([0, 128, 255], $rgb->toArray());
        self::assertSame('rgb(0, 128, 255)', (string) $rgb);
    }

    #[Test]
    public function rejectsNegativeChannel(): void
    {
        $this->expectException(InvalidColorValue::class);

        new Rgb(-1, 0, 0);
    }

    #[Test]
    public function rejectsTooLargeChannel(): void
    {
        $this->expectException(InvalidColorValue::class);

        new Rgb(0, 0, 256);
    }

    #[Test]
    public function equalsComparesAllChannels(): void
    {
        self::assertTrue((new Rgb(1, 2, 3))->equals(new Rgb(1, 2, 3)));
        self::assertFalse((new Rgb(1, 2, 3))->equals(new Rgb(3, 2, 1)));
    }
}
