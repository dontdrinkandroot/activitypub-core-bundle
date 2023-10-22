<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrActivityPubCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
