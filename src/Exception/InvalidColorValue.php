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

namespace KonradMichalik\Color\Exception;

use InvalidArgumentException;

use function sprintf;

/**
 * Thrown when a color value cannot be parsed or is out of range.
 */
final class InvalidColorValue extends InvalidArgumentException implements Exception
{
    public static function forHex(string $hex): self
    {
        return new self(sprintf('The value "%s" is not a valid hexadecimal color.', $hex), 1718000001);
    }

    public static function forRgbChannel(string $channel, int $value): self
    {
        return new self(
            sprintf('The %s channel value "%d" is out of range (expected 0-255).', $channel, $value),
            1718000002,
        );
    }

    public static function forHslComponent(string $component, float $value, float $min, float $max): self
    {
        return new self(
            sprintf(
                'The HSL %s value "%s" is out of range (expected %s-%s).',
                $component,
                $value,
                $min,
                $max,
            ),
            1718000003,
        );
    }

    public static function forString(string $value): self
    {
        return new self(sprintf('The value "%s" is not a valid color string.', $value), 1718000004);
    }

    public static function forAlpha(float $alpha): self
    {
        return new self(
            sprintf('The alpha value "%s" is out of range (expected 0.0-1.0).', $alpha),
            1718000005,
        );
    }
}
