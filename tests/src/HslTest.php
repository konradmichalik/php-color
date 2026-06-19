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
