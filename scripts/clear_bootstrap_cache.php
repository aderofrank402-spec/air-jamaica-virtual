<?php

declare(strict_types=1);

$cacheDirectory = dirname(__DIR__).DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache';

foreach (glob($cacheDirectory.DIRECTORY_SEPARATOR.'*') ?: [] as $path) {
    if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        @unlink($path);
    }
}
