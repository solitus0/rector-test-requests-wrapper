<?php

declare(strict_types=1);

namespace Rector\Rector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        PostAuthorizedRequestRector::class,
        PostAuthorizedRequestWithContentRector::class,
        PatchAuthorizedRequestRector::class,
        PatchAuthorizedRequestWithContentRector::class,
        DeleteAuthorizedRequestRector::class,
        RequestPostWithFilesRector::class,
        GetAuthorizedRequestRector::class,
        PutAuthorizedRequestWithContentRector::class,
    ]);
};
