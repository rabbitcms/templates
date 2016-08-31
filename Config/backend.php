<?php
use Illuminate\Routing\Router;
use RabbitCMS\Backend\Support\Backend;

return [
    'boot' => function (Backend $backend) {
        $backend->addMenuResolver(
            function (Backend $backend) {
                $backend->addMenu(
                    'system',
                    'templates',
                    trans('templates::common.Templates'),
                    null,
                    'fa fa-newspaper-o',
                    null,
                    40
                );

                $backend->addMenu(
                    'system.templates',
                    'edit',
                    trans('templates::common.Manage'),
                    route('backend.templates.index'),
                    'fa fa-newspaper-o',
                    ['templates.edit'],
                    1
                );
//                $backend->addMenu(
//                    'system.templates',
//                    'edit',
//                    trans('templates::common.Manage'),
//                    route('backend.templates.history.index'),
//                    'fa fa-newspaper-o',
//                    ['templates.story'],
//                    2
//                );
            }
        );
        $backend->addAclResolver(
            function (Backend $backend) {
                $backend->addAclGroup('templates', trans('templates::common.Templates'));
                $backend->addAcl('templates', 'edit', trans('templates::common.AclTemplates'));
                $backend->addAcl('templates', 'history', trans('templates::common.AclHistory'));
            }
        );
    },
    'routes' => function (Router $router) {
        $router->post('grid', ['uses' => 'TemplateController@grid', 'as' => 'grid']);
        $router->get('', ['uses' => 'TemplateController@index', 'as' => 'index']);
        $router->post('', ['uses' => 'TemplateController@store', 'as' => 'store']);
        $router->get('create', ['uses' => 'TemplateController@create', 'as' => 'create']);
        $router->put('{id}', ['uses' => 'TemplateController@update', 'as' => 'update']);
        $router->get('{id}', ['uses' => 'TemplateController@edit', 'as' => 'edit']);
        $router->delete('{id}', ['uses' => 'TemplateController@destroy', 'as' => 'delete']);
    },
    'requirejs' => [
        'packages' => [
            'rabbitcms/templates' => [
                'location' => 'js',
                'main' => 'templates'
            ]
        ],
    ],
    'handlers' => [
        '' => [
            'module' => 'rabbitcms/templates',
            'exec' => 'table',
            'permanent' => true,
            'menuPath' => 'system.templates.edit',
        ],
    ]
];