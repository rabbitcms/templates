<?php

use Illuminate\Routing\Router;
use RabbitCMS\Backend\Support\Backend;

return [
    'boot'      => function (Backend $backend) {
        $backend->addMenuResolver(
            function (Backend $backend) {
                $backend->addMenu('system', 'templates', trans('templates::menu.templates'), route('backend.templates.index'), 'fa-angle-double-right', ['templates.read'], 40);
            }
        );
        $backend->addAclResolver(
            function (Backend $backend) {
                $backend->addAclGroup('templates', trans('templates::acl.templates'));
                $backend->addAcl('templates', 'create', trans('templates::acl.create'));
                $backend->addAcl('templates', 'read', trans('templates::acl.read'));
                $backend->addAcl('templates', 'update', trans('templates::acl.update'));
                $backend->addAcl('templates', 'write', trans('templates::acl.write'));
            }
        );
    },
    'requirejs' => [
        'packages' => [
            'rabbitcms/templates' => [
                'location' => 'js',
                'main'     => 'templates',
            ],
        ],
    ],
    'handlers'  => [
        '' => [
            'module'    => 'rabbitcms/templates',
            'exec'      => 'table',
            'permanent' => true,
            'menuPath'  => 'system.templates',
        ],
        'create' => [
            'module'    => 'rabbitcms/templates',
            'exec'      => 'form',
            'menuPath'  => 'system.templates',
        ],
        'edit\/(\d+)' => [
            'module'    => 'rabbitcms/templates',
            'exec'      => 'form',
            'menuPath'  => 'system.templates',
        ]
    ],
];