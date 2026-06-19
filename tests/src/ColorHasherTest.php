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

namespace KonradMichalik\Color\Tests;

use KonradMichalik\Color\{ColorHasher};
use KonradMichalik\Color\Hashing\{Crc32Strategy, Sha256HslStrategy};
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

        self::assertMatchesRegularExpression('/^#[0-9a-f]{6}$/', $color->toHex());
    }
}
