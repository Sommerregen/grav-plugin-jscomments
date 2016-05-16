<?php
/**
 * JSComments
 *
 * This file is part of Grav JSComments plugin.
 *
 * Dual licensed under the MIT or GPL Version 3 licenses, see LICENSE.
 * http://benjamin-regler.de/license/
 */

namespace Grav\Plugin\JSComments\Twig;

use Grav\Common\Grav;
use Grav\Common\Page\Page;
use Grav\Plugin\JSCommentsPlugin;

/**
 * JSCommentsTwigExtension
 *
 * Helper to provide Twig filters and functions for the JSComments plugin.
 */
class JSCommentsTwigExtension extends \Twig_Extension
{
    /**
     * Returns extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'JSCommentsTwigExtension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('jscomments', [$this, 'funcJSComments']),
            new \Twig_SimpleFunction('jscomments_has_provider', [$this, 'funcJSCommentsHasProvider']),
            new \Twig_SimpleFunction('jscomments_get_provider', [$this, 'funcJSCommentsGetProvider'])
        ];
    }

    /**
     * Embeds a JSComment provider into the page.
     *
     * @param  null|string $provider   The provider. Leave empty to use default one.
     * @param  array       $params     An array with options for the selected
     *                                 provider (see `jscomments.yaml`).
     *
     * @return string
     */
    public function funcJSComments($provider = null, $params = [])
    {
        /** @var \Grav\Common\Grav $grav */
        $grav = Grav::instance();

        /** @var $config */
        $config = $grav['config']->get('plugins.jscomments', []);

        /** @var \Grav\Common\Page\Page $page */
        $page = $grav['page'];

        $config['enabled'] = isset($params['enabled'])
            ? $params['enabled']
            : ($params ? true: $this->mergeConfig($page, 'enabled', $config['enabled']));

        $config['active'] = isset($params['active'])
            ? $params['active']
            : ($params ? true : $this->mergeConfig($page, 'active', $config['active']));

        // Skip if page disables JSComments plugin
        if (!$config['enabled'] || !$config['active']) {
            return '';
        }

        // Populate JScomments settings
        $vars = isset($page->header()->jscomments) ? $page->header()->jscomments : [];
        $vars = array_replace_recursive($vars, $params, ['page' => $page]);

        // Validate presence of header value
        if (!$provider) {
            $provider = $this->mergeConfig($page, 'provider', $config['provider']);
        }

        // Check against valid providers
        $provider = strtolower($provider);
        if (!$this->funcJSCommentsHasProvider($provider)) {
            return '';
        }

        // Embed JSComment provider
        $template = 'plugins/jscomments/' . $provider . TEMPLATE_EXT;
        $vars    += $grav['config']->get('plugins.jscomments.providers.' . $provider, []);
        unset($vars['provider']);

        $output = $grav['twig']->processTemplate($template, $vars);
        return $output;
    }

    /**
     * Check if a JSComments provider exists.
     *
     * @param  string $provider   The name of the provider.
     *
     * @return bool
     */
    public function funcJSCommentsHasProvider($provider = null)
    {
        $exists = false;
        if ($provider) {
            $provider  = strtolower($provider);
            $providers = JSCommentsPlugin::getProviders();
            $exists    = isset($providers[$provider]) ? true : false;
        }

        return $exists;
    }

    /**
     * Get all options for a given provider.
     *
     * @param  string $provider   The name of the provider. Returns an array of
     *                            all provider names in case the actual provider
     *                            was not found.
     *
     * @return array
     */
    public function funcJSCommentsGetProvider($provider = null)
    {
        if ($this->funcJSCommentsHasProvider($provider)) {
            $provider = strtolower($provider);
            $data = Grav::instance()['config']->get('plugins.jscomments.providers.' . $provider, []);
        } else {
            $data = JSCommentsPlugin::getProviders();
        }

        return $data;
    }

    /**
     * Merge global and page JSComments settings
     *
     * @param Page    $page     The page to merge the page JSComments
     *                          configurations
     *                          with the JSComments settings.
     * @param string  $key      The name of the JSComments option.
     * @param bool    $default  The default value in case no JSComments
     *                          setting was found.
     *
     * @return array
     */
    protected function mergeConfig(Page $page, $key, $default = null)
    {
        while ($page && !$page->root()) {
            if (isset($page->header()->jscomments)) {
                $config = $page->header()->jscomments;
                if (is_bool($config) && !$config) {
                    return $default;
                }

                if (isset($config[$key])) {
                    $value = $config[$key];
                    if ($value === '@default') {
                        $value = $default;
                    }
                    return $value;
                }
            }

            $page = $page->parent();
        }

        return $default;
    }
}
