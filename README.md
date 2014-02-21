Kinetoscope
===========


About:
------

...is a slideshow plugin for WordPress that uses the custom post type "slide" and taxonomy "slideshow" to create sortable slideshows for use in a theme. The concept was originally derived from "Featured Links" plugin written by Taylor Gorman & Chris Roche.

The concept was to use as much WordPress functionality as possible. The only data written outside of WP's native functions is for the positions related to slides in a slideshow. The table name "wp_kinetoscope" (where 'wp_' is the prefix) is where that data is written and retreived.



Usage:
------

Once the plugin is installed and activated you simply need to populate some slides, create slideshows, and add slides to those slideshows using the newly added "slides" post type menu in the navigation bar. For incorporation in your WordPress theme use the following function:

$slides = get_slideshow($slideshow)

- The above function returns the $post array that WordPress uses; only ordered by the order set on the "Sort Order" page. If no order is defined, an unsorted array will be returned.
- $slideshow is the slug of the slideshow you wish to display (for example: 'good-movies').



Development:
------------

The plugin is currently in it's infancy (version 1.0) but will be regularly updated. It has currently been tested in WordPress 3.8.1, but should be compatible with all versions 3 & >.


