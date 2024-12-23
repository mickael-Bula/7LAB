<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
                    __DIR__ . '/config',
                    __DIR__ . '/public',
                    __DIR__ . '/src',
                    __DIR__ . '/tests',
                ])
    // uncomment to reach your current PHP version
    ->withPreparedSets(
        deadCode:           true,
        codeQuality:        true,
        codingStyle:        true,
        typeDeclarations:   true,
        earlyReturn:        true,
        symfonyCodeQuality: true
    )
    ->withSets([
                   SymfonySetList::SYMFONY_60,
               ]
    );

