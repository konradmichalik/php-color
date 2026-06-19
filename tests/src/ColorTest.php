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
use KonradMichalik\Color\Exception\InvalidColorValue;
use KonradMichalik\Color\Rgb;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ColorTest extends TestCase
{
    #[Test]
    #[DataProvider('hexProvider')]
    public function fromHexParsesValidValues(string $input, string $expectedHex): void
    {
        self::assertSame($expectedHex, Color::fromHex($input)->toHex());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function hexProvider(): iterable
    {
        yield 'long with hash' => ['#aabbcc', '#aabbcc'];
        yield 'long without hash' => ['aabbcc', '#aabbcc'];
        yield 'short with hash' => ['#abc', '#aabbcc'];
        yield 'short without hash' => ['abc', '#aabbcc'];
        yield 'uppercase' => ['#AABBCC', '#aabbcc'];
        yield 'whitespace' => ['  #fff  ', '#ffffff'];
    }

    #[Test]
    #[DataProvider('invalidHexProvider')]
    public function fromHexRejectsInvalidValues(string $input): void
    {
        $this->expectException(InvalidColorValue::class);

        Color::fromHex($input);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidHexProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'too short' => ['#ab'];
        yield 'wrong length' => ['#abcde'];
        yield 'non-hex chars' => ['#gghhii'];
    }

    #[Test]
    public function fromRgbRoundTripsToHex(): void
    {
        self::assertSame('#ff8800', Color::fromRgb(255, 136, 0)->toHex());
    }

    #[Test]
    public function fromRgbObjectWrapsTheGivenValue(): void
    {
        $rgb = new Rgb(255, 136, 0);

        self::assertTrue($rgb->equals(Color::fromRgbObject($rgb)->toRgb()));
    }

    #[Test]
    public function toRgbReturnsCanonicalChannels(): void
    {
        self::assertSame([51, 102, 204], Color::fromHex('#3366cc')->toRgb()->toArray());
    }

    #[Test]
    public function handlesGrayscaleInBothDirections(): void
    {
        // toHsl with max === min (achromatic)
        $gray = Color::fromHex('#808080');
        self::assertSame(0.0, $gray->toHsl()->saturation);
        self::assertSame(0.0, $gray->toHsl()->hue);

        // fromHsl with saturation 0 (achromatic)
        self::assertSame('#808080', Color::fromHsl(0.0, 0.0, 50.196078431)->toHex());
        self::assertSame('#ffffff', Color::fromHsl(120.0, 0.0, 100.0)->toHex());
    }

    #[Test]
    public function fromRgbRejectsOutOfRangeChannel(): void
    {
        $this->expectException(InvalidColorValue::class);

        Color::fromRgb(256, 0, 0);
    }

    #[Test]
    public function hslRoundTripIsStable(): void
    {
        $color = Color::fromHex('#3366cc');
        $hsl = $color->toHsl();

        self::assertSame($color->toHex(), Color::fromHsl($hsl->hue, $hsl->saturation, $hsl->lightness)->toHex());
    }

    #[Test]
    public function fromHslNormalizesHue(): void
    {
        self::assertTrue(Color::fromHsl(360.0, 50.0, 50.0)->equals(Color::fromHsl(0.0, 50.0, 50.0)));
        self::assertTrue(Color::fromHsl(-120.0, 50.0, 50.0)->equals(Color::fromHsl(240.0, 50.0, 50.0)));
    }

    #[Test]
    public function relativeLuminanceMatchesReferenceExtremes(): void
    {
        self::assertEqualsWithDelta(0.0, Color::fromHex('#000000')->relativeLuminance(), 0.0001);
        self::assertEqualsWithDelta(1.0, Color::fromHex('#ffffff')->relativeLuminance(), 0.0001);
    }

    #[Test]
    public function contrastRatioOfBlackAndWhiteIsMaximal(): void
    {
        self::assertEqualsWithDelta(
            21.0,
            Color::fromHex('#000')->contrastRatio(Color::fromHex('#fff')),
            0.01,
        );
    }

    #[Test]
    public function optimalTextColorPicksReadableColor(): void
    {
        self::assertSame('#ffffff', Color::fromHex('#222222')->optimalTextColor()->toHex());
        self::assertSame('#000000', Color::fromHex('#eeeeee')->optimalTextColor()->toHex());
    }

    #[Test]
    public function isDarkAndIsLightAreComplementary(): void
    {
        $dark = Color::fromHex('#101010');
        self::assertTrue($dark->isDark());
        self::assertFalse($dark->isLight());

        $light = Color::fromHex('#f0f0f0');
        self::assertTrue($light->isLight());
        self::assertFalse($light->isDark());
    }

    #[Test]
    public function withLightnessReturnsNewInstance(): void
    {
        $color = Color::fromHex('#3366cc');
        $lighter = $color->withLightness(90.0);

        self::assertNotSame($color->toHex(), $lighter->toHex());
        self::assertGreaterThan($color->relativeLuminance(), $lighter->relativeLuminance());
    }

    #[Test]
    public function stringableReturnsHex(): void
    {
        self::assertSame('#abcdef', (string) Color::fromHex('#abcdef'));
    }
}
