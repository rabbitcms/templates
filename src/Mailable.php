<?php
declare(strict_types = 1);
namespace RabbitCMS\Templates;

use Illuminate\Container\Container;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Mail\Mailable as IlluminateMailable;
use Illuminate\Mail\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

/**
 * Class Mailable
 *
 * @package RabbitCMS\Templates
 */
abstract class Mailable extends IlluminateMailable
{
    use SerializesModels;

    protected $locale;

    /**
     * Mailable constructor.
     */
    public function __construct()
    {
        $this->locale = App::getLocale();
    }

    public function build()
    {
    }

    /**
     * @param MailerContract $mailer
     */
    public function send(MailerContract $mailer)
    {
        $locale = App::getLocale();
        if ($this->locale !== null) {
            App::setLocale($this->locale);
        }

        Container::getInstance()->call([$this, 'build']);

        $mailer->raw('', function (Message $message) {
            $this->buildFrom($message)
                ->buildRecipients($message)
                ->buildSubject($message)
                ->buildAttachments($message)
                ->runCallbacks($message);

            $view = $this->buildView();

            $message->getSwiftMessage()
                ->setBody($view['html'], 'text/html');

            $message->getSwiftMessage()
                ->addPart($view['text'], 'text/plain');
        });
        App::setLocale($locale);
    }

    /**
     * @return array
     */
    protected function buildView()
    {
        $engine = Container::getInstance()->make(Templates::class)->getEngine();

        $template = $this->getTemplate();
        $data = $this->buildViewData();

        $this->subject($engine->render("subject:$template", $data));

        return [
            'html'  => $engine->render("html:$template", $data),
            'text' => $engine->render("plain:$template", $data),
        ];
    }

    /**
     * @return string
     */
    abstract protected function getTemplate(): string;
}
