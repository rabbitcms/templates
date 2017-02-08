<?php
declare(strict_types=1);
namespace RabbitCMS\Templates;

use DateTime;
use Illuminate\Foundation\Bus\DispatchesJobs;
use RabbitCMS\Contracts\Templates\MailRecipient;
use RabbitCMS\Templates\Entities\Send;
use RabbitCMS\Templates\Jobs\SendMail;
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

    protected $loader;

    /**
     * Templates constructor.
     */
    public function __construct()
    {
        $this->loader = new MailLoader;
        $this->twig = new Twig_Environment(
            $this->loader,
            [
                'cache' => storage_path('app/mail_templates'),
                'auto_reload' => true,
                'strict_variables' => false,
                'optimizations' => -1,
            ]
        );
        $this->twig->addTokenParser(new MailContent());
    }

    /**
     * Send template now.
     * @param MailRecipient $recipient
     * @param string $view
     * @param array $data [optional]
     * @param callable $callback [optional]
     */
    public function send(MailRecipient $recipient, $view, array $data = [], $callback = null)
    {
        $this->dispatchNow($this->makeJob($recipient, $view, $data, $callback));
    }

    protected function makeJob(MailRecipient $recipient, $view, array $data = [], $callback = null)
    {
        $template = $this->loader->findTemplate($view);

        $send = new Send([
            'subject' => $this->twig->render("subject:$view", $data),
            'html' => $this->twig->render("html:$view", $data),
            'plain' => $this->twig->render("plain:$view", $data),
            'callback' => $callback
        ]);

        $send->template()->associate($template);
        $send->recipient()->associate($recipient);

        $send->save();
        return new SendMail($send);
    }

    /**
     * @param MailRecipient $recipient
     * @param string $view
     * @param array $data [optional]
     * @param callable $callback [optional]
     * @param string $queue [optional]
     */
    public function queue(MailRecipient $recipient, $view, array $data = [], $callback = null, $queue = null)
    {
        $this->dispatch($this->makeJob($recipient, $view, $data, $callback)->onQueue($queue));
    }

    /**
     * @param int|DateTime $delay
     * @param MailRecipient $recipient
     * @param string $view
     * @param array $data [optional]
     * @param callable $callback [optional]
     * @param string $queue [optional]
     */
    public function later($delay, MailRecipient $recipient, $view, array $data = [], $callback = null, $queue = null)
    {
        $this->dispatch($this->makeJob($recipient, $view, $data, $callback)->onQueue($queue)->delay($delay));
    }
}