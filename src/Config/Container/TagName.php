<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\Config\Container;

class TagName
{
    public const string CONTROLLER = 'controller.service_arguments';
    public const string SERIALIZER_ENCODER = 'serializer.encoder';
    public const string ROUTING_LOADER = 'routing.loader';
    public const string SERIALIZER_NORMALIZER = 'serializer.normalizer';
    public const string KERNEL_EVENT_LISTENER = 'kernel.event_listener';
    public const string OBJECT_PROVIDER = 'ddr.activity_pub_core.object_provider';
    public const string MONOLOG_LOGGER = 'monolog.logger';
}
