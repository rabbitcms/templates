<?php
declare(strict_types=1);

namespace RabbitCMS\Templates\Events;

use Twig_Environment;

/**
 * Class TwigInitEvent
 *
 * @package RabbitCMS\Templates\Events
 */
class TwigInitEvent
{
    protected $env;

    /**
     * TwigInitEvent constructor.
     *
     * @param Twig_Environment $env
     */
    public function __construct(Twig_Environment $env)
    {
        $this->env = $env;
    }

    /**
     * @return Twig_Environment
     */
    public function getEnv(): Twig_Environment
    {
        return $this->env;
    }
}
