<?php
/**
 * JSComments v2.2.1
 *
 * This plugin allows Grav to integrate comments into individual pages
 * from Discourse / Disqus / Facebook / Google+ / IntenseDebate / Isso
 * and Muut comment systems.
 *
 * Dual licensed under the MIT or GPL Version 3 licenses, see LICENSE.
 * http://benjamin-regler.de/license/
 *
 * @package     JSComments
 * @version     2.2.1
 * @link        <https://github.com/sommerregen/grav-plugin-jscomments>
 * @author      Benjamin Regler <sommerregen@benjamin-regler.de>
 * @copyright   2017+, Benjamin Regler
 * @license     <http://opensource.org/licenses/MIT>        MIT
 * @license     <http://opensource.org/licenses/GPL-3.0>    GPLv3
 */

namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Data\Blueprints;
use RocketTheme\Toolbox\Event\Event;
use Grav\Plugin\JSComments\Twig\JSCommentsTwigExtension;

/**
 * Class JSCommentsPlugin
 *
 * This plugin allows Grav to integrate comments into individual pages
 * from Discourse / Disqus / Facebook / Google+ / IntenseDebate and Muut
 * comment systems.
 *
 * @package Grav\Plugin
 */
class JSCommentsPlugin extends Plugin
{
    /**
     * Return a list of subscribed events.
     *
     * @return array    The list of events of the plugin of the form
     *                      'name' => ['method_name', priority].
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized'  => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->config->get('plugins.jscomments.enabled')) {
            $events = [
                'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
                'onTwigExtensions'      => ['onTwigExtensions', 0]
            ];

            if ($this->isAdmin()) {
                $this->active = false;
                $events = [
                    'onBlueprintCreated' => ['onBlueprintCreated', 0]
                ];
            }

            $this->enable($events);
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Add Twig Extensions
     */
    public function onTwigExtensions()
    {
        require_once(__DIR__.'/classes/Twig/JSCommentsTwigExtension.php');
        $this->grav['twig']->twig->addExtension(new JSCommentsTwigExtension());
    }

    /**
     * Admin
     */

    /**
     * Extend page blueprints with JSComments configuration options.
     *
     * @param Event $event
     */
    public function onBlueprintCreated(Event $event)
    {
        /** @var \Grav\Common\Data\Blueprint $blueprint */
        $blueprint = $event['blueprint'];

        if ($blueprint->get('form/fields/tabs')) {
            $blueprints = new Blueprints(__DIR__ . '/blueprints/');
            $extends = $blueprints->get('jscomments');
            $blueprint->extend($extends, true);
        }
    }

    /**
     * Get installed JSComments provider
     *
     * @return array An array of installed and available providers.
     */
    static public function getProviders()
    {
        /** @var \Grav\common\Grav $grav */
        $grav = Grav::instance();

        $providers = [];
        // Load all comment providers
        $iterator = new \FilesystemIterator(__DIR__ . '/templates/plugins/jscomments');
        foreach ($iterator as $object) {
          if ($object->isFile()) {
            $provider = $object->getBasename(TEMPLATE_EXT);
            $providers[$provider] = 'PLUGINS.JS_COMMENTS.PROVIDERS.' . strtoupper($provider) . '.TITLE';
          }
        }

        // Sort alphabetically using PHP's strnatcasecmp function
        uksort($providers, 'strnatcasecmp');
        return $providers;
    }
}
