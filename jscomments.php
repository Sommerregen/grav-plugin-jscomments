<?php
/**
 * JSComments v1.2.0
 *
 * This plugin allows Grav to integrate comments into individual pages
 * from Disqus / IntenseDebate / Facebook and Muut comments system.
 *
 * Dual licensed under the MIT or GPL Version 3 licenses, see LICENSE.
 * http://benjamin-regler.de/license/
 *
 * @package     Themer
 * @version     1.2.0
 * @link        <https://github.com/sommerregen/grav-plugin-jscomments>
 * @author      Benjamin Regler <sommerregen@benjamin-regler.de>
 * @copyright   2015, Benjamin Regler
 * @license     <http://opensource.org/licenses/MIT>        MIT
 * @license     <http://opensource.org/licenses/GPL-3.0>    GPLv3
 */

namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Page\Page;

class JSCommentsPlugin extends Plugin
{
    public static function getSubscribedEvents() {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized'   => ['onPageInitialized', 0]
        ]);
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onPageInitialized()
    {
        $this->mergePluginConfig($this->grav['page']);

        $options = $this->grav['config']->get('plugins.jscomments');
        $providers = $options['providers'];

        if (! $this->validateProvider($options['provider'])) {
            $this->grav['config']->set('plugins.jscomments.enabled', false);
            return;
      }
    }

    private function validateProvider($provider)
    {
        $options = $this->grav['config']->get('plugins.jscomments');

        return ( isset($options['provider']) && array_key_exists($options['provider'], $options['providers']) ) ? true : false;
    }

    private function mergePluginConfig(Page $page)
    {
        $defaults = (array) $this->grav['config']->get('plugins.jscomments');
        if ( isset($page->header()->jscomments) ) {
            if array($page->header()->jscomments) ) {
                $this->grav['config']->set('plugins.jscomments', array_replace_recursive($defaults, $page->header()->jscomments));
        }
    }
    }
}
