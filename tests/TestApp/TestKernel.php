<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Tests\TestApp;

use Override;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Override]
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    #[Override]
    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/ddr_activitypub_core_bundle/cache/';
    }

    #[Override]
    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/ddr_activitypub_core_bundle/logs/';
    }
}
