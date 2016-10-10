<?php

namespace RabbitCMS\Templates\Providers;

use RabbitCMS\Modules\ModuleProvider;
use RabbitCMS\Templates\Templates;

class TemplatesModuleProvider extends ModuleProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(
            [Templates::class => 'templates'],
            function () {
                return new Templates(
                    $this->app->make('mailer')
                );
            }
        );
    }

    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'templates';
    }
}