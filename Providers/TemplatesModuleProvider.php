<?php

namespace RabbitCMS\Templates\Providers;

use RabbitCMS\Modules\ModuleProvider;
use RabbitCMS\Templates\Templates;
use RabbitCMS\Templates\Twig\TokenParser\MailContent;
use Twig_Environment;

class TemplatesModuleProvider extends ModuleProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton('templates', function () {
            return new Templates(
                $this->app->make('mailer')
            );
        });
    }



    /**
     * @inheritdoc
     */
    protected function name()
    {
        return 'templates';
    }
}