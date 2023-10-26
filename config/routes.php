<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config;

use Dontdrinkandroot\ActivityPubCoreBundle\Controller\WebfingerAction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\RouteName;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('.', 'ddr_activitypub_core');
};
