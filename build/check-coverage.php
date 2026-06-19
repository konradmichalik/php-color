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

// Fails the build unless line coverage from the Clover report is exactly 100%.

$cloverFile = __DIR__ . '/../.build/clover.xml';

if (!is_file($cloverFile)) {
    fwrite(STDERR, "Coverage report not found at {$cloverFile}. Run \"composer test:coverage\" first.\n");
    exit(1);
}

$xml = new SimpleXMLElement((string) file_get_contents($cloverFile));
$metrics = $xml->project->metrics ?? null;

if ($metrics === null) {
    fwrite(STDERR, "No metrics found in coverage report.\n");
    exit(1);
}

$statements = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];
$percentage = $statements > 0 ? ($covered / $statements) * 100 : 100.0;

printf("Line coverage: %.2f%% (%d/%d)\n", $percentage, $covered, $statements);

if ($covered !== $statements) {
    fwrite(STDERR, "Coverage is below the required 100%.\n");
    exit(1);
}

echo "Coverage requirement met (100%).\n";
