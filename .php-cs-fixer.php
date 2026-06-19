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

use KonradMichalik\PhpCsFixerPreset\Config;
use KonradMichalik\PhpCsFixerPreset\Rules\Header;
use Symfony\Component\Finder\Finder;

return Config::create()
    ->withRule(
        Header::fromComposer(),
    )
    ->withFinder(static fn (Finder $finder) => $finder->in(__DIR__))
;
