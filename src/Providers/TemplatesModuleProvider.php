<?php
declare(strict_types=1);
namespace RabbitCMS\Templates\Providers;

use RabbitCMS\Modules\ModuleProvider;
use RabbitCMS\Templates\Templates;

/**
 * Class TemplatesModuleProvider
 *
 * @package RabbitCMS\Templates\Providers
 */
class TemplatesModuleProvider extends ModuleProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(Templates::class, function () {
            return new Templates($this->app->make('mailer'));
        });
    }

    /**
     * @inheritdoc
     */
    protected function name(): string
    {
        return 'templates';
    }
}