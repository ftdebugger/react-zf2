<?php

return array(
    'ReactZF' => array(
        'servers' => array(
            'default' => array(
                'host' => '127.0.0.1',
                'port' => 1337
            )

        )
    ),

    'controllers' => array(
        'invokables' => array(
            'react-zf-index' => 'ReactZF\Controller\IndexController'
        )
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'react-zf-start' => array(
                    'options' => array(
                        'route' => 'react start',
                        'defaults' => array(
                            'controller' => 'react-zf-index',
                            'action' => 'start'
                        )
                    )
                )
            )
        )
    ),
);