<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\DependencyInjection;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Param;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrActivityPubCoreExtension extends Extension implements PrependExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->registerForAutoconfiguration(InboxHandlerInterface::class)
            ->addTag(Tag::INBOX_HANDLER);

        $container->setParameter(Param::HOST, $config['host']);
        $container->setParameter(Param::ACTOR_PATH_PREFIX, $config['actor_path_prefix']);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');
        $loader->load('types.php');
        $loader->load('serializer.php');
        $loader->load('handlers.php');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'framework',
            [
                'request' => [
                    'formats' => [
                        'jsonld' => ['application/activity+json', 'application/ld+json'],
                    ],
                ],
            ]
        );
    }
}
