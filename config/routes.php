<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('.', 'ddr_activitypub_core');
};
