<?php

namespace Blog_Admin;

// use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;

return [
    // 'controllers' => [
    //     'factories' => [
    //         Controller\Blog_AdminController::class => InvokableFactory::class,
    //     ],
    // ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'blog_admin' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/blog_admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\Blog_AdminController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],


    'view_manager' => [
        'template_path_stack' => [
            'blog_admin' => __DIR__ . '/../view',
        ],
    ],
];

?>