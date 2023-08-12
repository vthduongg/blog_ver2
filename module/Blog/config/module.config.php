<?php

namespace Blog;

use Zend\Router\Http\Segment;

return [

    'router' => [
        'routes' => [
            'blog' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/blog[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
        ],
        'template_path_stack' => [
            'blog' => __DIR__ . '/../view',
        ],
    ],

];

?>