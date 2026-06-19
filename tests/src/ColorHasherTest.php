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

use KonradMichalik\Color\Color;
use KonradMichalik\Color\ColorHasher;
use KonradMichalik\Color\Hashing\Crc32Strategy;
use KonradMichalik\Color\Hashing\Sha256HslStrategy;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ColorHasherTest extends TestCase
{
    #[Test]
    public function hslStrategyIsDeterministic(): void
    {
        $hasher = ColorHasher::hsl();

        self::assertSame(
            $hasher->hash('konrad@example.com')->toHex(),
            $hasher->hash('konrad@example.com')->toHex(),
        );
    }

    #[Test]
    public function crc32StrategyIsDeterministic(): void
    {
        $hasher = ColorHasher::crc32();

        self::assertSame(
            $hasher->hash('Konrad Michalik')->toHex(),
            $hasher->hash('Konrad Michalik')->toHex(),
        );
    }

    #[Test]
    public function differentInputsYieldDifferentColors(): void
    {
        $hasher = ColorHasher::hsl();

        self::assertNotSame(
            $hasher->hash('alice')->toHex(),
            $hasher->hash('bob')->toHex(),
        );
    }

    #[Test]
    public function hslStrategyHonoursSaturationAndLightness(): void
    {
        $color = (new Sha256HslStrategy(80.0, 30.0))->hash('whatever');
        $hsl = $color->toHsl();

        self::assertEqualsWithDelta(80.0, $hsl->saturation, 1.0);
        self::assertEqualsWithDelta(30.0, $hsl->lightness, 1.0);
    }

    #[Test]
    public function crc32StrategyProducesValidColor(): void
    {
        $color = (new Crc32Strategy())->hash('anything');

        self::assertInstanceOf(Color::class, $color);
        self::assertMatchesRegularExpression('/^#[0-9a-f]{6}$/', $color->toHex());
    }
}
