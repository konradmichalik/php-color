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

namespace KonradMichalik\Color\Tests;

use KonradMichalik\Color\Exception\InvalidColorValue;
use KonradMichalik\Color\Hsl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class HslTest extends TestCase
{
    #[Test]
    public function acceptsValidValues(): void
    {
        $hsl = new Hsl(210.0, 60.0, 40.0);

        self::assertSame([210.0, 60.0, 40.0], $hsl->toArray());
        self::assertSame('hsl(210, 60%, 40%)', (string) $hsl);
    }

    #[Test]
    public function rejectsHueOutOfRange(): void
    {
        $this->expectException(InvalidColorValue::class);

        new Hsl(361.0, 50.0, 50.0);
    }

    #[Test]
    public function rejectsSaturationOutOfRange(): void
    {
        $this->expectException(InvalidColorValue::class);

        new Hsl(0.0, 101.0, 50.0);
    }

    #[Test]
    public function rejectsLightnessOutOfRange(): void
    {
        $this->expectException(InvalidColorValue::class);

        new Hsl(0.0, 50.0, -1.0);
    }
}
