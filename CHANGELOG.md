# v2.2.1
## 06/04/2018

3. [](#bugfix)
  * Fixed issues in Isso template [#26](https://github.com/Sommerregen/grav-plugin-jscomments/pull/26)

# v2.2.0
## 01/29/2018

1. [](#new)
  * Added [Isso commenting system](https://posativ.org/isso/) [#12](https://github.com/Sommerregen/grav-plugin-jscomments/issues/12)

# v2.1.4
## 01/13/2018

3. [](#bugfix)
  * Fixed `CHANGELOG.md` and `README.md` [#22](https://github.com/Sommerregen/grav-plugin-jscomments/pull/22)

# v2.1.3
## 01/13/2018

3. [](#bugfix)
  * Fixed issue with HyperComments service provider: No login solution except anonymous [#23](https://github.com/Sommerregen/grav-plugin-jscomments/issues/23) & [#24](https://github.com/Sommerregen/grav-plugin-jscomments/pull/24) (Thanks to [@foufrix](https://github.com/foufrix))

# v2.1.2
## 08/20/2017

3. [](#bugfix)
  * Made IntenseDebate service provider protocol agnostic [#21](https://github.com/Sommerregen/grav-plugin-jscomments/issues/21)

# v2.1.1
## 07/21/2017

3. [](#bugfix)
  * Fixed broken language string in Muut comments

# v2.1.0
## 05/07/2017

1. [](#new)
  * Added Russian language and support for HyperComments comments [#18](https://github.com/Sommerregen/grav-plugin-jscomments/pull/18) & [#19](https://github.com/Sommerregen/grav-plugin-jscomments/pull/19) (Thanks to [@Neiromaster](https://github.com/Neiromaster))
2. [](#improved)
  * Added some comment provider placeholder values
  * Refactored comment providers into sections again

# v2.0.2
## 02/06/2017

2. [](#improved)
  * Use `raw` filter for translation strings (see [#15](https://github.com/Sommerregen/grav-plugin-jscomments/issues/15), Wrong output code)
  * Updated `README.md` and corrected grammar

# v2.0.1
## 09/05/2016

3. [](#bugfix)
  * Fixed [#9](https://github.com/Sommerregen/grav-plugin-jscomments/issues/9) (Uncaught Error: invalid version specified) with [#10](https://github.com/Sommerregen/grav-plugin-jscomments/pull/10) (Thanks to [@Perlkonig](https://github.com/Perlkonig))

# v2.0.0
## 07/20/2016

2. [](#improved)
  * Released stable version (requires **Grav v1.1.0+**)

# v2.0.0-beta.5
## 06/09/2016

2. [](#improved)
  * Add missing Romanian language strings [#8](https://github.com/Sommerregen/grav-plugin-jscomments/pull/8) (Thanks to [JohnMica](https://github.com/JohnMica))

# v2.0.0-beta.4
## 06/08/2016

1. [](#new)
  * Added support for Google+ comments.
  * Added Romanian language [#7](https://github.com/Sommerregen/grav-plugin-jscomments/pull/7) (Thanks to [JohnMica](https://github.com/JohnMica))

# v2.0.0-beta.3
## 05/16/2016

2. [](#improved)
  * Added `rel="nofollow"` to comment provider links
  * Use canonical links as page URL and raw route as page ID for comment providers (**BC**!!!)
3. [](#bugfix)
  * Fixed `jscomments` Twig function (passing variables is working now)
  * Fixed broken comment pages by exposing the current page to Twig

# v2.0.0-beta.2
## 05/14/2016

2. [](#improved)
  * Minor code improvements
3. [](#bugfix)
  * Fixed broken translation in admin plugin settings [#4](https://github.com/Sommerregen/grav-plugin-jscomments/issues/4) (Beta Grav and Admin issues with translating Discourse Title tab)
  * Fixed `jscomments_get_provider` Twig function

# v2.0.0-beta.1
## 05/12/2016

1. [](#new)
  * Added `active` settings
  * Added German translations
  * Added more JSComments page settings
  * Add support for Discourse [#2](https://github.com/Sommerregen/grav-plugin-jscomments/issues/2) & [#3](https://github.com/Sommerregen/grav-plugin-jscomments/pull/3) (Thanks to @openscript)
2. [](#improved)
  * Fixed strings, added help texts and broke out provider settings into tabs
  * Update JS comment providers APIs according to their docs (**BC** !!!)
  * Moved Disqus Counts settings into main Disqus admin page settings
  * Refactored code (requires **Grav v1.1.0+**)
  * Updated `README.md`
3. [](#bugfix)
  * Fixed [#1](https://github.com/Sommerregen/grav-plugin-jscomments/issues/1) (JScomments Not Working with Grav Beta)

# v1.2.10
## 09/13/2015

2. [](#improved)
  * Added translations for the admin plugin form fields.
3. [](#bugfix)
  * Change the main translation key from JSCOMMENTS to PLUGIN_JSCOMMENTS for following the Grav design.

# v1.2.9
## 09/13/2015

3. [](#bugfix)
  * Remove useless field `provider` from configuration. Now this field used only for call the provider based on provider plugin options saved.

# v1.2.8
## 09/13/2015

1. [](#new)
  * Added multi language support for Facebook template, use this format (`{grav_lang}_{grav_lang|upper}`). I'm not sure if this is the best way, but in most case working fine.
  * Added new parameter for Facebook comments "order_by" configurable from page options or into page header. Default is "Social".
2. [](#improved)
  * Update Facebook widget to v2.4.
  * Change Facebook parameter from `numposts` to `num_posts` for following the Facebook comments parameter.
  * Change twig translation function to twig filter.
3. [](#bugfix)
  * Fixed `CHANGELOG.md` format.
  * Clear code.
  * Added JS escape into Disqus template.
  * Added translation for Muut template.
  * Added multi languages support into Muut template.
  * Added JS escape into IntenseDebate template.

# v1.2.7
## 09/12/2015

1. [](#new)
  * Added `languages.yaml` for translations (_some useless but nice to have it <3_).
2. [](#improved)
  * Change how to working code into the header page for use into admin plugin options tab, read the `README.md`.
  * Refactoring plugin settings page into admin plugin.
3. [](#bugfix)
  * Enabled plugin by default.
  * Fixed small bugs into admin plugin integration.

# v1.2.6
## 09/12/2015

3. [](#bugfix)
  * Change from `onPageContentRaw` event to `onPageInitialized` because not working very well with the cache system.

# v1.2.5
## 09/12/2015

2. [](#improved)
  * Update to working fine with the latest version of Grav.
  * Prepare for the admin plugin the possible to setup the plugin directly with form settings.
  * Added the validation of jscomments header, if setup to true or with data array the plugin parsing, otherwise no.
  * Improve Grav cache for parsing jscomments when setup to page header.

# v1.2.4
## 06/28/2015

3. [](#improved)
  * Added multilang support for Disqus.
3. [](#bugfix)
  * Fixed Twig parsing bug.

# v1.2.3
## 02/04/2015

3. [](#bugfix)
  * Update `Plugin::mergeConfig()` problem.

# v1.2.2
## 02/04/2015

2. [](#improved)
  * Update `blueprints.yaml`.

# v1.2.1
## 01/10/2015

2. [](#improved)
  * Add complete support for plugin configuration on Admin Plugin.

# v1.2.0
## 01/01/2015

1. [](#new)
  * Rewrite how to add jscomments to page/template. Now working with Twig function. Check the [README.md](README.md) for update.

# v1.1.2
## 12/31/2014

3. [](#bugfix)
  * Fix config typo.

# v1.1.1
## 12/23/2014

1. [](#new)
  * Added CHANGELOG.md with partial changelog update, I update in the next release.
3. [](#bugfix)
  * Update the merge config with PHP function `array_replace_recursive` instead `merge_array`.

# v1.1.0
## 12/06/2014

3. [](#bugfix)
  * Correct version number

# v1.0.9
## 12/05/2014

1. [](#new)
  * Add methods for reading/writing/merge configuration with page header.
2. [](#improved)
  * Remove $page variable, not need.
  * Improve plugin events.
  * Update README.md with new configuration values.

# v1.0.8
## 11/01/2014

1. [](#new)
  * Added Admin Plugin check.
  * Added demo link into the `blueprints.yaml`.

# v1.0.7
## 10/24/2014

1. [](#new)
  * ChangeLog started...
