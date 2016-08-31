<?php

namespace RabbitCMS\Templates\Providers;

use RabbitCMS\Modules\ModuleProvider;

class TemplatesModuleProvider extends ModuleProvider
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'templates';
    }
}