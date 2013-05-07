<?php
/**
 * @author     Evgeny Shpilevsky <evgeny.shpilevsky@gmail.com>
 * @license    MIT
 * @date       5/7/13 14:31
 */

namespace ReactZF;

use ReactZF\Service\ApplicationManager;
use ReactZF\Service\ApplicationManagerOptions;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceManager;

class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ConsoleUsageProviderInterface,
    ServiceProviderInterface
{

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . "/../../config/module.config.php";
    }

    /**
     * @inheritdoc
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            )
        );

    }

    /**
     * @inheritdoc
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'Run react application',
            'react start --all' => 'run all exists servers',
            'react start [server]' => 'if no server name specified, "default" will be used',
        );
    }

    /**
     * @inheritdoc
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ReactZFApplicationManagerOptions' => function (ServiceManager $sm) {
                    $configuration = $sm->get('Configuration');
                    $options = isset($configuration['ReactZF']) ? $configuration['ReactZF'] : [];

                    return new ApplicationManagerOptions($options);
                },
                'ReactZFApplicationManager' => function (ServiceManager $sm) {
                    /** @var ApplicationManagerOptions $options */
                    $options = $sm->get('ReactZFApplicationManagerOptions');
                    $configuration = $sm->get('ApplicationConfig');

                    return new ApplicationManager($options, $configuration);
                }
            )
        );
    }

}
