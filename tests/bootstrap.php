<?php

declare(strict_types=1);

use Koriym\Attributes\AttributeReader;
use Ray\ServiceLocator\ServiceLocator;

$_ENV['TMP_DIR'] = __DIR__ . '/tmp';
$unlink = static function ($path) use (&$unlink): void {
    foreach ((array) glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*') as $f) {
        $file = (string) $f;
        is_dir($file) ? $unlink($file) : unlink($file);
        @rmdir($file);
    }
};
$unlink($_ENV['TMP_DIR']);

ServiceLocator::setReader(new AttributeReader());
