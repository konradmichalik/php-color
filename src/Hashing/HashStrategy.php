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

namespace KonradMichalik\Color\Hashing;

use KonradMichalik\Color\Color;

/**
 * Maps an arbitrary string deterministically to a color.
 */
interface HashStrategy
{
    public function hash(string $input): Color;
}
