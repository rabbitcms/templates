<?php

namespace RabbitCMS\Templates\Twig;

use Lang;
use RabbitCMS\Templates\Entities\Template;
use Twig_Error_Loader;
use Twig_LoaderInterface;

class MailLoader implements Twig_LoaderInterface
{
    /**
     * Templates cache.
     * @var array|Template[]
     */
    protected $cache = [];

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getCacheKey($name)
    {
        return $this->getSource($name);
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     * @throws Twig_Error_Loader When $name is not found
     */
    public function getSource($name)
    {
        $names = $this->getNames($name);
        $template = $this->findTemplate($name);

        switch ($names[0]) {
            case 'subject':
                return $template->subject;
            case 'plain':
                $body = $template->plain;
                break;
            default:
                $body = $template->template;
        }

        if ($template->extends) {
            $body = "{% extends \"{$template->extends}\" %}{% block content%}{$body}{% endblock %}";
        }

        return $body;
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
        return !$updated || $updated->timestamp  > $time;
    }
}