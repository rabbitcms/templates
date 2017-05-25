<?php
declare(strict_types=1);

namespace RabbitCMS\Templates\Twig;

use Lang;
use RabbitCMS\Templates\Entities\Template;
use Twig_Error_Loader;
use Twig_LoaderInterface;

/**
 * Class MailLoader
 *
 * @package RabbitCMS\Templates\Twig
 */
class MailLoader implements Twig_LoaderInterface
{
    /**
     * Templates cache.
     * @var array|Template[]
     */
    protected $cache = [];

    /**
     * @inheritdoc
     */
    public function getCacheKey($name)
    {
        return $name . '_' . Lang::getLocale();
    }

    /**
     * @inheritdoc
     */
    public function getSourceContext($name)
    {
        $names = $this->getNames($name);
        $template = $this->findTemplate($name);

        switch ($names[0]) {
            case 'subject':
                return new \Twig_Source($template->subject, $name);
            case 'plain':
                $body = $template->plain;
                break;
            default:
                $body = $template->template;
        }

        if ($template->extends) {
            $body = "{% extends \"{$template->extends}\" %}{% block content%}{$body}{% endblock %}";
        }

        return new \Twig_Source($body, $name);
    }

    /**
     * Get template names.
     * @param string $name
     * @return string[]
     */
    protected function getNames($name)
    {
        $names = explode(':', $name, 2);

        return count($names) === 1 ? ['html', $name] : $names;
    }

    /**
     * Find template.
     *
     * @param string $name
     *
     * @return Template
     * @throws Twig_Error_Loader
     */
    public function findTemplate($name)
    {
        list(, $name) = $this->getNames($name);
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        $query = Template::query();
        $locales = [Lang::getLocale(), Lang::getFallback()];

        /* @var Template $template */
        $template = $query->whereIn('locale', $locales)
            ->where('name', $name)
            ->orderByRaw('locale = ? desc', [$locales[0]])->first();

        if ($template === null) {
            throw new Twig_Error_Loader(
                sprintf('Unable to find template "%s" (looked for locales: %s).', $name, implode(', ', $locales))
            );
        }

        return $this->cache[$name] = $template;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param int $time Timestamp of the last modification time of the
     *                     cached template
     *
     * @return bool true if the template is fresh, false otherwise
     * @throws Twig_Error_Loader When $name is not found
     */
    public function isFresh($name, $time)
    {
        $updated = $this->findTemplate($name)->updated_at;
        return $updated && $updated->timestamp <= $time;
    }

    /**
     * @inheritdoc
     */
    public function exists($name)
    {
        try {
            $this->findTemplate($name);
            return true;
        } catch (Twig_Error_Loader $e) {
            return false;
        }
    }
}
