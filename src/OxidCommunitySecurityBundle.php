<?php

namespace OxidCommunity\SecurityBundle;

use OxidCommunity\SecurityBundle\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle AS BaseBundle;
use Sioweb\Oxid\Kernel\Bundle\BundleRoutesInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;
use Sioweb\Oxid\Kernel\DependencyInjection\ContainerBuilder;
use Sioweb\Oxid\Kernel\Bundle\BundleConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Sioweb\Oxid\Kernel\DependencyInjection\Loader\YamlFileLoader;

class OxidCommunitySecurityBundle extends BaseBundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }
}
