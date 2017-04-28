<?php
declare(strict_types = 1);
namespace RabbitCMS\Templates;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Event;
use RabbitCMS\Templates\Events\TwigInitEvent;
use RabbitCMS\Templates\Twig\MailLoader;
use RabbitCMS\Templates\Twig\TokenParser\MailContent;
use Twig_Environment;

/**
 * Class Templates
 *
 * @package RabbitCMS\Templates
 */
class Templates
{
    use DispatchesJobs;

    protected $twig;

    /**
     * Templates constructor.
     */
    public function __construct()
    {
        $this->twig = new Twig_Environment(new MailLoader, [
            'cache'            => storage_path('app/mail_templates'),
            'auto_reload'      => true,
            'strict_variables' => false,
            'optimizations'    => -1,
        ]);
        $this->twig->addTokenParser(new MailContent());

        Event::dispatch(new TwigInitEvent($this->twig));
    }

    /**
     * @return Twig_Environment
     */
    public function getEngine(): Twig_Environment
    {
        return $this->twig;
    }
}
