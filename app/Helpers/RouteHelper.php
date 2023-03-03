<?php

namespace App\Helpers;

class RouteHelper
{
    public static function includeRouteFiles(string $directory): void
    {
        $dirIterator = new \RecursiveDirectoryIterator($directory);

        /** @var  \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);

        while ($it->valid()) {
            if (!$it->isDot()
                && $it->isFile()
                && $it->isReadable()
                && $it->current()->getExtension() === 'php')
            {
                require_once $it->key();
            }
            $it->next();
        }
    }
}
