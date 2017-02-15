<?php
declare(strict_types = 1);
namespace RabbitCMS\Templates;

use Illuminate\Container\Container;
use Illuminate\Mail\Mailable as IlluminateMailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Mailable
 *
 * @package RabbitCMS\Templates
 */
abstract class Mailable extends IlluminateMailable
{
    use SerializesModels;

    public function build()
    {
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
            'plain' => $engine->render("plain:$template", $data),
        ];
    }

    /**
     * @return string
     */
    abstract protected function getTemplate(): string;
}
