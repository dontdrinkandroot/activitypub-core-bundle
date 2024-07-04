<?php

namespace Dontdrinkandroot\ActivityPubCoreBundle\DependencyInjection;

use Dontdrinkandroot\ActivityPubCoreBundle\Config\Container\ParamName;
use Dontdrinkandroot\ActivityPubCoreBundle\Config\Container\TagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowResponseMode;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;
use Override;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrActivityPubCoreExtension extends Extension implements PrependExtensionInterface
{
    #[Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->registerForAutoconfiguration(ObjectProviderInterface::class)
            ->addTag(TagName::OBJECT_PROVIDER);

        $container->setParameter(ParamName::HOST, $config['host']);
        $container->setParameter(ParamName::ACTOR_PATH_PREFIX, $config['actor_path_prefix']);
        $container->setParameter(
            ParamName::FOLLOW_RESPONSE_MODE,
            FollowResponseMode::from($config['follow_response_mode'])
        );

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');
        $loader->load('types.php');
        $loader->load('serializer.php');
        $loader->load('inbox_listeners.php');
    }

    #[Override]
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
