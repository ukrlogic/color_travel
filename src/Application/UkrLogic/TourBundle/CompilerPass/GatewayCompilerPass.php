<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 11/26/14
 * Time: 2:51 PM
 */

namespace Application\UkrLogic\TourBundle\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GatewayCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (! $container->hasDefinition('application_ukrlogic_tourbundle.service.gatewaycontainer')) {
            return;
        }

        $definition = $container->getDefinition('application_ukrlogic_tourbundle.service.gatewaycontainer');

        $taggedServices = $container->findTaggedServiceIds('application_ukrlogic_tourbundle.service.gateway');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('add', [new Reference($id), $attributes["alias"]]);
            }
        }
    }

}