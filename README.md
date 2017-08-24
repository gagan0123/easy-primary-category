# Easy Primary Category #
**Contributors:** [gagan0123](https://profiles.wordpress.org/gagan0123)  
**Tags:** category, primary  
**Requires at least:** 4.5  
**Tested up to:** 4.8.1  
**Stable tag:** 1.0  
**License:** GPLv2  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Allows you to choose primary category for posts and custom post types

## Description ##

Allows you to choose primary category for posts and custom post types.

When the permalink structure includes category, the category marked as Primary, will be used for generating the permalink of the post.

Works with custom post types and taxonomies as long as the taxonomy supports hierarchical structure. 

### Developers' Notes ###

If you want to fetch posts belonging to a particular term you can use this:
```
if ( function_exists( 'epc_get_primary_term_posts' ) ) {
	/**
	 * Assuming you want to fetch 10 published posts which have category ID 2
	 * marked as primary category
	 */
	$posts = epc_get_primary_term_posts( 2, array(
		'post_status'	 => 'publish',
		'posts_per_page' => 10,
		'post_type'		 => 'post',
	) );
}
```

## Installation ##
1. Add the plugin's folder in the WordPress' plugin directory.
1. Activate the plugin.
1. Now you'll have the ability to make any category a primary category for the post

## Frequently Asked Questions ##

### Why I can't see the "Make Primary" button ###
The button is displayed only when more than one category is selected, try assigning other categories to the post, as soon as there's more than one category selected, the "Make Primary" button will appear.

## Screenshots ##
1. Click the "Make Primary" button to mark the category as primary.

## Changelog ##

### 1.0 ###
* Initial public release

### 0.1 ###
* Initial Development