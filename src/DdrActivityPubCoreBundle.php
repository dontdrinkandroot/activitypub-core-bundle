<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle;

use Override;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrActivityPubCoreBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
