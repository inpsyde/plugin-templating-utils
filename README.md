# Plugin Templating Utils

> A Composer package that provides utility functions to templating functionality in plugins..

---

## Functions


### `Inpsyde\plugin_file_base_dir()`

Given a file a file inside a plugin directory, no matter how deep in the directory three, returns the absolute path
to root directory of the plugin.


### `Inpsyde\plugin_template_part()`

Similar to `get_template_part()` loads a template from a plugin directory.
The plugin to load template from is obtained from first argument, that can be a file in the target plugin directory,
no matter how deep in the directory three.

Templates to be searched pass through `'plugin_template_part_templates'` filter.


### `Inpsyde\plugin_file_path()`

Similar to `get_theme_file_path()` returns the path of a file inside a plugin directory.
The target plugin is obtained from first argument, that can be a file in the target plugin directory,
no matter how deep in the directory three.

Returns empty string if the file does not exists.

Returned value passes through `'plugin_file_path'` filter.


### `Inpsyde\plugin_file_uri()`

Similar to `get_theme_file_uri()` returns the URL of a file inside a plugin directory.
The target plugin is obtained from first argument, that can be a file in the target plugin directory,
no matter how deep in the directory three.

Returns empty string if the file does not exists.

Returned value passes through `'plugin_file_uri'` filter.  


### `Inpsyde\plugin_template_part_fallback()`

Like `Inpsyde\plugin_template_part()` but fallbacks to theme (or child theme) if file is not found in plugin.


### `Inpsyde\plugin_file_path_fallback()`

Like `Inpsyde\plugin_file_path()` but fallbacks to theme (or child theme) if file is not found in plugin.


### `Inpsyde\plugin_file_uri_fallback()`

Like `Inpsyde\plugin_file_uri()` but fallbacks to theme (or child theme) if file is not found in plugin.

---

## Requirements

- PHP 5.6+
- Composer to install

---

## Installation

Via Composer, package name is **`inpsyde/plugin-templating-utils`**.

---

## License and Copyright

Copyright (c) 2017 Inpsyde GmbH.

"CPT Archives" code is licensed under [MIT license](https://opensource.org/licenses/MIT).

The team at [Inpsyde](https://inpsyde.com) is engineering the Web since 2006.
