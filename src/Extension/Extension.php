<?php

/**
 * Contao Open Source CMS
 */

declare (strict_types = 1);

namespace OxidCommunity\SecurityBundle\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder AS BaseContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension AS BaseExtension;

class Extension extends BaseExtension implements PrependExtensionInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getAlias()
	{
		return 'oxidcommunity-symfonysecuritybundle';
    }
  
    public function load(array $configs, BaseContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
        $loader->load('listener.yml');
    }

    public function prepend(BaseContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
        $loader->load('security.yml');
        $loader->load('listener.yml');
    }
}
