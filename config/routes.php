<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config;

use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('.', 'ddr_activitypub_core');

    $routes->add('ddr.activitypub.core.webfinger', '/.well-known/webfinger')
        ->controller(WebfingerAction::class)
        ->methods(['GET']);
};
