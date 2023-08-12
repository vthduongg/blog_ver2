<?php

namespace Blog;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PostTable::class => function ($container) {
                    $tableGateway = $container->get(Model\BlogTableGateway::class);
                    return new Model\PostTable($tableGateway);
                },
                Model\BlogTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Post());
                    //'post o day la ten bang trong db'
                    return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\BlogController::class => function ($container) {
                    return new Controller\BlogController(
                        $container->get(Model\PostTable::class)
                    );
                },
            ],
        ];
    }
}