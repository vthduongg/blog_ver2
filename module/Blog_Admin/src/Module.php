<?php

namespace Blog_Admin;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

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
                    $tableGateway = $container->get(Model\Blog_AdminTableGateway::class);
                    return new Model\PostTable($tableGateway);
                },
                Model\Blog_AdminTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Post());
                    //'post' - ten bang
                    return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
                },

            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\Blog_AdminController::class => function ($container) {
                    return new Controller\Blog_AdminController(
                        $container->get(Model\PostTable::class)
                    );
                },
            ],
        ];
    }
}

?>