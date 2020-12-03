<?php

declare(strict_types=1);

namespace App\Component\Api;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ModelContainerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ModelChain::class)) {
            return;
        }

        $definition     = $container->findDefinition(ModelChain::class);
        $taggedServices = $container->findTaggedServiceIds('api.model');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'registerModel',
                [
                    $id
                ]
            );
        }
    }
}