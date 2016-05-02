<?php
/**
 * JSComments v1.2.10
 *
 * This plugin allows Grav to integrate comments into individual pages
 * from Disqus / IntenseDebate / Facebook and Muut comments system.
 *
 * Dual licensed under the MIT or GPL Version 3 licenses, see LICENSE.
 * http://benjamin-regler.de/license/
 *
 * @package     JSComments
 * @version     1.2.10
 * @link        <https://github.com/sommerregen/grav-plugin-jscomments>
 * @author      Benjamin Regler <sommerregen@benjamin-regler.de>
 * @copyright   2016, Benjamin Regler
 * @license     <http://opensource.org/licenses/MIT>        MIT
 * @license     <http://opensource.org/licenses/GPL-3.0>    GPLv3
 */
 
namespace Grav\Plugin;

use Grav\Common\Data\Blueprints;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class JSCommentsPlugin
 * @package Grav\Plugin
 */
class JSCommentsPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized'  => ['onPluginsInitialized', 0],
            'onBlueprintCreated'    => ['onBlueprintCreated', 0]
        ];
    }

    /**
     *
     */
    public function onPluginsInitialized()
    {
        if (true === $this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
            'onTwigInitialized'     => ['onTwigInitialized', 0]
        ]);
    }

    /**
     *
     */
    public function onTwigInitialized()
    {
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('jscomments', [$this, 'twigFunctionJSComments'], ['is_safe' => ['html']])
        );

        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('jscomments_count', [$this, 'twigFunctionJSCommentsCount'], ['is_safe' => ['html']])
        );

        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('jscomments_validate_provider', [$this, 'twigFunctionJSCommentsValidateProvider'], ['is_safe' => ['html']])
        );
    }

    /**
     *
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * @param Event $event
     */
    public function onBlueprintCreated(Event $event)
    {
        static $inEvent = false;

        /** @var \Grav\Common\Data\Blueprint $blueprint */
        $blueprint = $event['blueprint'];

        if (false === $inEvent and $blueprint->get('form.fields.tabs')) {
            $inEvent = true;
            $blueprints = new Blueprints(__DIR__ . '/blueprints/');
            $extends = $blueprints->get('jscomments');
            $blueprint->extend($extends, true);
            $inEvent = false;
        }
    }

    /**
     * @param null|string $provider
     * @param array $params
     * @return string
     */
    public function twigFunctionJSComments($provider = null, $params = [])
    {
        // Load page object.
        $page = $this->grav['page'];

        // Validate presence of header value
        if (false === $this->validateHeader($page->header()) and null === $provider) {
            return '';
        }

        // Load config.
        $config = $this->mergeConfig($page);

        $provider = (null === $provider) ? $config->get('provider') : $provider;

        if (false === $this->validateProvider($provider, $page)) {
            return '';
        }

        $template_file = $this->getTemplatePath($provider);
        $template_vars = array_merge($this->getProviderOptions($provider), $params, [
            'page' => $page
        ]);

        return $this->grav['twig']->processTemplate($template_file, $template_vars);
    }

    /**
     * @param null $shortname
     * @return string
     */
    public function twigFunctionJSCommentsCount($shortname = null)
    {
        $page = $this->grav['page'];

        // Validate presence of header value
        if (false === $this->validateHeader($page->header())) {
            return '';
        }

        // Merge site config with page config.
        $config = $this->mergeConfig($page);

        // Break if the provider is not disqus
        if ('disqus' !== $config->get('provider')) {
            return '';
        }

        $shortname = (null === $shortname) ? $config->get('providers.disqus.shortname') : $shortname;

        $template_file = $this->getTemplatePath('disqus_count');
        $template_vars = [
            'shortname' => $shortname
        ];

        $output = $this->grav['twig']->processTemplate($template_file, $template_vars);

        return $output;
    }

    /**
     * @param string $provider
     * @return bool
     */
    public function twigFunctionJSCommentsValidateProvider($provider)
    {
        // Validate presence of header value
        if (false === $this->validateHeader($this->grav['page']->header())) {
            return '';
        }

        return $this->validateProvider($provider, $this->grav['page']);
    }

    /**
     * @param object $header
     * @return bool
     */
    private function validateHeader($header)
    {
        return isset($header->jscomments);
    }

    /**
     * @param string $provider
     * @param Page $page
     * @return bool
     */
    private function validateProvider($provider, Page $page)
    {
        $config = $this->mergeConfig($page);

        return array_key_exists($provider, $config->get('providers'));
    }

    /**
     * @param string $name
     * @return string
     */
    private function getTemplatePath($name)
    {
        return sprintf('plugins/jscomments/%s.html.twig', $name);
    }

    /**
     * @param string $provider
     * @param Page|null $page
     * @return mixed
     */
    private function getProviderOptions($provider, Page $page = null)
    {
        $provider_key = sprintf('providers.%s', $provider);

        $page = (null === $page) ? $this->grav['page'] : $page;

        $config = $this->mergeConfig($page);

        return $config->get($provider_key);
    }
}
