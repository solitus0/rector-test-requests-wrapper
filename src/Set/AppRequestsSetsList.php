<?php

declare(strict_types=1);

namespace Rector\Set;

use Rector\Set\Contract\SetListInterface;

class AppRequestsSetsList implements SetListInterface
{
    public const TEST_REQUESTS_WRAPPER = __DIR__ . '/../../config/config.php';
}
