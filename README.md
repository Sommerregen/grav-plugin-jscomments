# [![Grav JSComments Plugin](assets/logo.png)][project]

[![Release](https://img.shields.io/github/release/sommerregen/grav-plugin-jscomments.svg)][project] [![Issues](https://img.shields.io/github/issues/sommerregen/grav-plugin-jscomments.svg)][issues] [![Dual license](https://img.shields.io/badge/dual%20license-MIT%2FGPL-blue.svg)](LICENSE "License")

> **JSComments** is a [Grav](http://github.com/getgrav/grav) plugin which allows Grav to integrate comments into individual pages from Discourse, Disqus, Facebook, Google+, HyperComments, IntenseDebate, and Muut comment systems.

##### Table of Contents:

* [Installation and Updates](#installation-and-updates)
* [Usage](#usage)
    * [Config Defaults](#config-defaults)
    * [Twig Filter](#twig-filter)
* [Contributing](#contributing)
* [License](#license)

## Installation and Updates

Installing or updating the `JSComments` plugin can be done in one of two ways. Using the GPM (Grav Package Manager) installation update method (i.e. `bin/gpm install jscomments`) or a manual install by downloading [this plugin](https://github.com/sommerregen/grav-plugin-jscomments) and extracting all plugin files to

    user/plugins/jscomments

For more informations, please check the [Installation and update guide](docs/INSTALL.md).

## Usage

### Admin Plugin Setup

If you use the **Admin plugin** you can choose the provider directly from the plugin settings page or from the page edit tab called "Options".

> **NOTE:** Before you can use the plugin, it is necessary setup the provider settings in the plugin settings page.

### Initial Setup

Place the following line of code in the theme file or page (__you need to enable the Twig processing in the configuration settings before Twig functions can be used in the page__) you wish to add `JSComments` for:

```twig
{{ jscomments()|raw }}
```

This code works best when placed within the content block of the page, just below the main `{{ page.content }}` tag. Alternatively, place this line anywhere you wish in your theme. Usually it is wrapped by an if condition, like

```twig
{% if config.plugins.jscomments.enabled %}
  {{ jscomments()|raw }}
{% endif %}
```

to take care of disabling any output, when the plugin is being disabled.

>> NOTE: Any time you are making alterations to a theme's files, you will likely want to duplicate the theme folder in the `user/themes/` directory, rename it, and set the new name as your active theme. This will ensure that you don't lose your customizations in the event that a theme is updated.

### Config Defaults

You have the ability to set a number of variables that affect the `JSComments` plugin. These variables include the `provider` you want use. You can also define the page id and url and disable comments for a specific page.

These options can exist in two places. Primarily, your user defaults will be set within the **jscomments.yaml** file in the `user/config/plugins/` directory. If you do not have a `user/config/plugins/` already, create the folder as it will enable you to change the default settings of the plugin without losing them during an update or re-installation of the plugin.

Here are the available variables:

```yaml
# Global plugin configurations

enabled: true               # Set to false to disable this plugin completely

# Global and page specific configurations

active: true                # Option to (de-)activate this plugin on a per-page basis
provider: ''                # Comment provider (one of the providers below)

# Default options for JSComments configuration

providers:
  discourse:
    host: ''                # The URL to the Discourse commenting system

  disqus:
    shortname: ''           # Forum shortname
    show_count: false       # Show comment count
    language: ''            # Default language (e.g. 'en')

  facebook:
    app_id: ''              # App ID
    num_posts: 5            # Number of visible posts
    colorscheme: 'light'    # Color scheme (can be "light" or "dark")
    order_by: 'social'      # The order to use when displaying comments.
                            # ('social', 'reverse_time', 'time')
    language: ''            # Language (e.g. 'en_US')
    width: '100%'           # Width (px or %)

  googleplus:
    show_count: false       # Show comment count
    language: ''            # Default language (e.g. 'en')
    width: '100%'           # Width (px or %)

  intensedebate:
    account_number: ''      # Account number

  isso:
    host: ''                # The URL to the Isso commenting system
    builtin_css: true       # Use provided stylesheet
    language: ''            # Default language (e.g. 'en')
    reply_to_self: false    # Reply to self
    require:
      author: true          # Require author name
      email: true           # Require author email
    comments:
      number: 10            # Number of top level comments to show by default
      nested_number: 5      # Number of nested level comments to show by default
      reveal: 5             # Number of comments to reveal on clicking the "X Hidden" link
    avatar:
      enabled: true         # Enable or disable avatar generation.
      background: ''        # Set avatar background color.
      foreground: ''        # Set avatar foreground color. Up to 8 colors are possible.
    vote:
      enabled: true         # Enable or disable voting feature on the client side.
      levels: '-5,5'        # List of vote levels used to customize comment appearance based on score.

  muut:
    forum: ''               # Forum name
    channel: 'General'      # The name of the muut channel
    widget: false           # Embed muut comments as a widget
    show_online: false      # Show online users
    show_title: false       # Show comment titles
    upload: false           # Allow uploads
    share: true             # Show share buttons
    language: ''            # Default language (e.g. 'en')

  hypercomments:
    widget_id:              # The ID of your widget
    social:                 # The order and set of social networks.
      - 'vk'
      - 'odnoklassniki'
      - 'yandex'
      - 'mailru'
      - 'google'
      - 'livejournal'
      - 'facebook'
      - 'twitter'
      - 'tumblr'
```

If you need to change any value, then the best process is to copy the [jscomments.yaml](jscomments.yaml) file into your `user/config/plugins/` folder (create it if it doesn't exist) and modify it there. This will override the default settings.

If you want to change any of these settings for a specific page you can do so via the page header. Below is an example of how these settings can be used.

```yaml
jscomments:
  provider: disqus

  title: "Different title page"
  id: "page-slug-example"
  url: "new-page-url"
```

`JSComments` can be enabled or disabled globally and on a per-page basis. The option for this is the `active` option. Provided `active` is set to `false` you can enable `JSComments` for one page (or a page collection) with

```yaml
jscomments:
  active: true
```

From Twig you can call `JSComments` as follows:

```twig
{{ jscomments('provider_name', { param: value })|raw }}
```

An example for **Disqus** with minimum parameters is shown below:

```twig
{{ jscomments('disqus', { shortname: example })|raw }}
```

For most users, only the `provider` option will need to be set. This will pull the comments settings from your account and the pull information (such as the page title) from the page.

Furthermore, `JSComments` allows you to use different commenting systems in one Grav install. You only have to set the `provider` option like

```yaml
jscomments:
  provider: disqus
```

for a page or better a collection and all child pages will inherit this option.

>> NOTE: Any time you are making alterations to a theme's files, you will want to duplicate the theme folder in the `user/themes/` directory, rename it, and set the new name as your active theme. This will ensure that you don't lose your customizations in the event that a theme is updated. Once you have tested the change thoroughly, you can delete or back up that folder elsewhere.

### Twig Filter

In addition, `JSComments` provides two more useful Twig functions. First, you can check whether a specific provider exists via

```twig
{% if jscomments_has_provider('provider') %}
  ...
{% endif %}
```

and what options are set for a specific provider,

```twig
{% set provider_options = jscomments_get_provider('provider') %}
```

If `jscomments_get_provider` is called with an unknown provider or with an empty parameter, then it returns a list of all available providers instead.

## Contributing

You can contribute at any time! Before opening any issue, please search for existing issues and review the [guidelines for contributing](docs/CONTRIBUTING.md).

After that please note:

* If you find a bug, would like to make a feature request or suggest an improvement, [please open a new issue][issues]. If you have any interesting ideas for additions to the syntax please do suggest them as well!
* Feature requests are more likely to get attention if you include a clearly described use case.
* If you wish to submit a pull request, please make again sure that your request match the [guidelines for contributing](docs/CONTRIBUTING.md) and that you keep track of adding unit tests for any new or changed functionality.

## License

Copyright (c) 2017+ [Benjamin Regler][github]. See also the list of [contributors] who participated in this project.

[Dual-licensed](LICENSE) for use under the terms of the [MIT][mit-license] or [GPLv3][gpl-license] licenses.

![GNU license - Some rights reserved][gnu]

[github]: https://github.com/sommerregen/ "GitHub account from Benjamin Regler"
[gpl-license]: http://opensource.org/licenses/GPL-3.0 "GPLv3 license"
[mit-license]: http://www.opensource.org/licenses/mit-license.php "MIT license"
[gnu]: https://upload.wikimedia.org/wikipedia/commons/thumb/3/33/License_icon-gpl-88x31.svg/88px-License_icon-gpl-88x31.svg.png "GNU license - Some rights reserved"

[project]: https://github.com/sommerregen/grav-plugin-jscomments
[issues]: https://github.com/sommerregen/grav-plugin-jscomments/issues "GitHub Issues for Grav JSComments Plugin"
[contributors]: https://github.com/sommerregen/grav-plugin-jscomments/graphs/contributors "List of contributors of the project"
