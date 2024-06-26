<?php # -*- coding: utf-8 -*-
/*
 * This file is part of the plugin-templating-utils package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde;

if ( function_exists( __NAMESPACE__ . '\\plugin_file_base_dir' ) ) {
	return;
}

/**
 * Given a file a file inside a plugin directory, no matter how deep in the directory three, returns the absolute path
 * to root directory of the plugin.
 *
 * @param string $plugin_file
 *
 * @return string
 *
 * @since 0.1.0
 */
function plugin_file_base_dir( $plugin_file ) {

	global $wp_plugin_paths;
	$plugin_dir  = untrailingslashit( wp_normalize_path( WP_PLUGIN_DIR ) );
	$plugin_file = wp_normalize_path( $plugin_file );

    foreach ( $wp_plugin_paths as $dir => $real_dir ) {
        if ( strpos( $plugin_file, $real_dir ) === 0 ) {
            return $dir;
        }
    }

	if ( ! preg_match( '#^' . preg_quote( $plugin_dir, '#' ) . '/([^/]+)/.+?\.php$#', $plugin_file, $matches ) ) {
		return '';
	}

	return $plugin_dir . '/'. $matches[ 1 ];
}

/**
 * Look for a template from a plugin directory.
 * The plugin folder where to search is obtained from first argument, that can be a file in the target plugin directory,
 * no matter how deep in the directory three.
 *
 * @param string $plugin_file
 * @param string $slug
 * @param null   $name
 * @param array  $args
 *
 * @return string
 *
 * @since 0.2.0
 */
function find_plugin_template_part( $plugin_file, $slug, $name = null, array $args = [] ) {

	$base = plugin_file_base_dir( $plugin_file );

	if ( ! $base ) {
		return '';
	}

	$templates = [];
	$name      = (string) $name;
	$name and $templates[] = "{$slug}-{$name}.php";
	$templates[] = "{$slug}.php";
	$templates   = apply_filters( "plugin_template_part_templates", $templates, $base, $slug, $name, $plugin_file, $args );

	$base = trailingslashit( $base );

	foreach ( (array) $templates as $template ) {
		if ( is_string( $template ) && file_exists( $base . $template ) ) {
			return $base . $template;
		}
	}

	return '';
}

/**
 * Similar to `get_template_part()` loads a template from a plugin directory.
 * The plugin folder where to search is obtained from first argument, that can be a file in the target plugin directory,
 * no matter how deep in the directory three.
 *
 * @param string $plugin_file
 * @param string $slug
 * @param null   $name
 * @param array  $args
 *
 * @return bool
 *
 * @since 0.1.0
 */
function plugin_template_part( $plugin_file, $slug, $name = null, array $args = [] ) {

	$template = find_plugin_template_part( $plugin_file, $slug, $name, $args );

	if ( $template ) {
		/** @noinspection PhpIncludeInspection */
		include $template;

		return TRUE;
	}

	return FALSE;
}

/**
 * Similar to `get_theme_file_path()` returns the path of a file inside a plugin directory.
 * The target plugin is obtained from first argument, that can be a file in the target plugin directory,
 * no matter how deep in the directory three.
 *
 * @param string $plugin_file
 * @param string $file
 *
 * @return string
 *
 * @since 0.1.0
 */
function plugin_file_path( $plugin_file, $file ) {

	$base = plugin_file_base_dir( $plugin_file );

	if ( ! $base ) {
		return '';
	}

	$base = trailingslashit( $base );
	$file = (string) apply_filters( 'plugin_file_path', $base . $file, $file, $base );

	return file_exists( $file ) ? $file : '';
}

/**
 * Similar to `get_theme_file_uri()` returns the URL of a file inside a plugin directory.
 * The target plugin is obtained from first argument, that can be a file in the target plugin directory,
 * no matter how deep in the directory three.
 *
 * @param string $plugin_file
 * @param string $file
 *
 * @return string
 *
 * @since 0.1.0
 */
function plugin_file_uri( $plugin_file, $file ) {

	$base = plugin_file_base_dir( $plugin_file );
	$base and $base = trailingslashit( $base );

	if ( ! $base || ! file_exists( $base . $file ) ) {
		return '';
	}

	return (string) apply_filters( 'plugin_file_uri', plugins_url( $file, $base . 'plugin.php' ), $file, $base );
}

/**
 * Like `plugin_template_part()`, but fallbacks to theme (or child theme) if file is not found in plugin.
 *
 * @param string      $plugin_file
 * @param string      $slug
 * @param string|null $name
 * @param string|null $theme_slug
 * @param array       $args
 *
 * @since 0.1.0
 */
function plugin_template_part_fallback( $plugin_file, $slug, $name = null, $theme_slug = null, array $args = [] ) {

	if ( ! plugin_template_part( $plugin_file, $slug, $name, $args ) ) {
		get_template_part( $theme_slug ? : $slug, $name, $args );
	}
}

/**
 * Like `plugin_file_path()`, but fallbacks to theme (or child theme) if file is not found in plugin.
 *
 * @param string      $plugin_file
 * @param string      $file
 * @param string|null $theme_file
 *
 * @return string
 *
 * @since 0.1.0
 */
function plugin_file_path_fallback( $plugin_file, $file, $theme_file = null ) {

	$path = plugin_file_path( $plugin_file, $file );
	$path or $path = get_theme_file_path( $theme_file ? : $file );

	return $path;
}

/**
 * Like `plugin_file_uri()`, but fallbacks to theme (or child theme) if file is not found in plugin.
 *
 * @param string      $plugin_file
 * @param string      $file
 * @param string|null $theme_file
 *
 * @return string
 *
 * @since 0.1.0
 */
function plugin_file_uri_fallback( $plugin_file, $file, $theme_file = null ) {

	$url = plugin_file_uri( $plugin_file, $file );
	$url or $url = get_theme_file_uri( $theme_file ? : $file );

	return $url;
}

