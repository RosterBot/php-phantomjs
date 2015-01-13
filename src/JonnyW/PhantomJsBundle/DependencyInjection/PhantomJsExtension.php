<?php
/**
 * Created for php-phantomjs.
 * User: Ed Epstein
 * Date: 2015-01-09
 * Time: 5:29 PM
 */

namespace JonnyW\PhantomJSBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class JonnyWPhantomJSExtension extends Extension
{

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        $container->setParameter('phantomjs.cache_dir', sys_get_temp_dir());
        $container->setParameter('phantomjs.resource_dir', __DIR__.'/../Resources');
    }

} 
