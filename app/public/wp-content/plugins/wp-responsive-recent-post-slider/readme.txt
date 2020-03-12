=== WP Responsive Recent Post Slider/Carousel  ===
Contributors: wponlinesupport, anoopranawat, pratik-jain
Tags: wponlinesupport, post slider, posts slider, recent post slider, recent posts slider, slider, responsive post slider, responsive posts slider, responsive recent post slider, responsive recent posts slider, wordpress posts slider, post slideshow, posts slideshow, recent posts slideshow, shortcodes
Requires at least: 4.0
Tested up to: 5.3.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add and display Responsive WordPresss Recent Post Slider and Carousel on your website with 4 designs (Slider) and 1 designs (Carousel) using a shortcode. Also work with Gutenberg shortcode block. 

== Description ==

Attractive & eye-catching WP Responsive Recent Post Slider and Carousel.

This plugin displays your recent WordPress posts using :

* Posts Slider (4 designs), 
* Posts Carousel (1 designs)

Check [Demo and Features](https://demo.wponlinesupport.com/recent-post-slider-demo/) for additional information.

When you want to makeover your WordPress website theme with something extraordinary and creative, you must consider WP responsive recent post slider. Help your website to get a slide-wise display to show the recent posts/news. Not just eye appealing, it is also loved by the visitors as they find it quite easy to locate the recent posts. 

You can also try our FREE plugin [Recent Posts Widget Designer](https://wordpress.org/plugins/recent-posts-widget-designer/) along with "Responsive Recent Post Slider/Carousel"

Display unlimited number of WordPresss posts slider and carousel in a single page or post with different sets of options like category, limit, navigation type. 

**Also work with Gutenberg shortcode block.** 

= Here is the plugin shortcode example =

**Post Slider** 

<code>[recent_post_slider]</code>

**Post Carousel** 

<code>[recent_post_carousel]</code>

**To display only latest 4 post:**

<code>[recent_post_slider limit="4"]</code>
Where limit define the number of posts to display. You can use same parameter with Carousel shortcode.

**If you want to display Recent Post Slider by category then use this short code:** 

<code>[recent_post_slider category="category_ID"]</code>
You can use same parameter with Carousel shortcode.

**We have given 4 designs. For designs use the following shortcode:** 

<code>[recent_post_slider design="design-1"]</code> 
Where designs are : design-1, design-2, design-3, design-4. You can use same parameter with Carousel shortcode but in Carousel we have given only 1 design ie design-1.

= Here is Template code =
<code><?php echo do_shortcode('[recent_post_slider]'); ?> </code>
<code><?php echo do_shortcode('[recent_post_carousel]'); ?> </code>

= Use Following Recent Post Slider parameters with shortcode =
<code>[recent_post_slider]</code>

* **limit** : [recent_post_slider limit="8"] (Display only 8 latest post. By default it display 8 latest posts with shortcode [recent_post_slider]. If you want to display all posts then use limit="-1").
* **design** : [recent_post_slider design="design-1"] (You can select 4 design( design-1, design-2, design-3, design-4 ) for your recent post slider. ).
* **category**: [recent_post_slider category="category_ID"] ( ie Display recent post slider by their category ID ).
* **show_category_name** : [recent_post_slider show_category_name="true" ] (Display category name OR not. By default value is "True". Options are "ture OR false").
* **show_date** : [recent_post_slider show_date="false"] (Display post date OR not. By default value is "True". Options are "ture OR false")
* **show_content** : [recent_post_slider show_content="true" ] (Display post Short content OR not. By default value is "True". Options are "ture OR false").
* **media_size** : [recent_post_slider  media_size="full"] (where you can use values : thumbnail, medium, medium_large, large and full)
* **Pagination and arrows** : [recent_post_slider dots="false" arrows="false"]
* **Autoplay and Autoplay Interval**: [recent_post_slider autoplay="true" autoplay_interval="100"]
* **Slide Speed**: [recent_post_slider speed="3000"]
* **content_words_limit** : [recent_post_slider content_words_limit="30" ] (Control post short content Words limit. By default limit is 20 words).
* **post_type:** [recent_post_slider post_type="post"] (ie added custom post type support where you add custom post. By default value is "post")
* **taxonomy:** [recent_post_slider taxonomy="category"] (ie added custom taxonomy support where you add custom taxonomy. By default value is "category")
* **posts:** [recent_post_slider posts="1,2,3"] (ie include some posts with their post-id that you want to display)
* **hide_post:** [recent_post_slider hide_post="1,2,3"] (ie exclude some posts with their post-id that you do not want to display)
* **show_author:** [recent_post_slider show_author="false"] (ie Display author name OR not. By default value is "true". Values are "true OR false")
* **show_read_more:** [recent_post_slider show_read_more="true"] (Display read more button. Values are "true OR false")
* **rtl:** [recent_post_slider rtl="true"] (for rtl mode. By default value is “false”. Options are “true OR false”).

= Use Following Recent Post Carousel parameters with shortcode =
<code>[recent_post_carousel]</code>

* **limit** : [recent_post_carousel limit="8"] (Display only 8 latest post. By default it display 8 latest posts with shortcode [recent_post_carousel]. If you want to display all posts then use limit="-1").
* **design** : [recent_post_carousel design="design-1"] (You can select 1 design for your recent post carousel. ).
* **category**: [recent_post_carousel category="category_ID"] ( ie Display recent post carousel by their category ID ).
* **show_category_name** : [recent_post_carousel show_category_name="true" ] (Display category name OR not. By default value is "True". Options are "ture OR false").
* **show_date** : [recent_post_carousel show_date="false"] (Display post date OR not. By default value is "True". Options are "ture OR false")
* **show_content** : [recent_post_carousel show_content="true" ] (Display post Short content OR not. By default value is "True". Options are "ture OR false").
* **media_size** : [recent_post_carousel  media_size="full"] (where you can use values : thumbnail, medium, medium_large, large and full)
* **slides_to_show** : [recent_post_carousel slides_to_show="3" ] (Display number of posts at a time. By default value is "3").
* **slides_to_scroll** : [recent_post_carousel slides_to_scroll="1" ] (Scroll number of posts at a time. By default value is "1").
* **Pagination and arrows** : [recent_post_carousel dots="false" arrows="false"]
* **Autoplay and Autoplay Interval**: [recent_post_carousel autoplay="true" autoplay_interval="100"]
* **Slide Speed**: [recent_post_carousel speed="3000"]
* **content_words_limit** : [recent_post_carousel content_words_limit="30" ] (Control post short content Words limit. By default limit is 20 words).
* **post_type:** [recent_post_carousel post_type="post"] (ie added custom post type support where you add custom post. By default value is "post")
* **taxonomy:** [recent_post_carousel taxonomy="category"] (ie added custom taxonomy support where you add custom taxonomy. By default value is "category")
* **posts:** [recent_post_carousel posts="1,2,3"] (ie include some posts with their post-id that you want to display)
* **hide_post:** [recent_post_carousel hide_post="1,2,3"] (ie exclude some posts with their post-id that you do not want to display)
* **show_author:** [recent_post_carousel show_author="false"] (ie Display author name OR not. By default value is "true". Values are "true OR false")
* **show_read_more:** [recent_post_carousel show_read_more="true"] (Display read more button. Values are "true OR false")
* **rtl:** [recent_post_carousel rtl="true"] (for rtl mode. By default value is “false”. Options are “true OR false”).

= Pro Features include: =
> <strong>Premium Version</strong><br>
>
> * Recent Post Slider with 25 designs.
> * Recent Post Carousel with 30 designs.
> * Recent Post gridbox slider with 8 designs.
> * Shortcode <code>[recent_post_carousel]</code> and <code>[gridbox_post_slider]</code>
> * 3 Widgets (Post slider, Post List/Slider-1, Post List/Slider-2).
> * V1.3.4 Support WordPress default post as well as any custom post-type and taxonomy .
> * Gutenberg Block Supports.
> * Visual Composer Support.
> * WP Templating Features.
>
> View [PRO DEMO and Features](https://www.wponlinesupport.com/wp-plugin/wp-responsive-recent-post-slider/) for additional information.
>

= Features include: =
* Post slider
* Post carousel
* Easy to add.
* Also work with Gutenberg shortcode block. 
* Given 4 designs.
* Media size ie  thumbnail, medium, medium_large, large and full
* Responsive.
* Responsive touch slider.
* Mouse Draggable.
* You can create multiple post slider with different options at single page or post.
* Custom post type support.
* Exclude Post with their ID's that you do not want to display.

= How to install : =
[youtube https://www.youtube.com/watch?v=Bpi8f1JEBzc] 

= Privacy & Policy =
* We have also opt-in e-mail selection, once you download the plugin , so that we can inform you and nurture you about products and its features.

== Installation ==

1. Upload the 'wp-recent-post-slider' folder to the '/wp-content/plugins/' directory.
2. Activate the "wp-recent-post-slider with Widget" list plugin through the 'Plugins' menu in WordPress.
3. Add this short code where you want to display recent post slider
<code>[recent_post_slider]</code>

= How to install : =
[youtube https://www.youtube.com/watch?v=Bpi8f1JEBzc] 

== Screenshots ==

1. Design-1
2. Design-2
3. Design-3
4. Design-4
5. Also work with Gutenberg shortcode block. 
6. Category shortcode for post

== Changelog ==

= 2.2.5 (24, Jan 2020) =
* [*] New : Added new shortcode parameter "posts" for both shortcode.

= 2.2.4 (26, Dec 2019) =
* [*] Tested : Tested with Gutenberg blocks.
* [*] Updated features list.
* [*] Added unique prefix to all classes to avoid the conflict with any theme and third-party plugins.  
* [*] Fixed some css issues.

= 2.2.3 (09, Sept 2019) =
* [*] Fixed title line height issue reported by a user.

= 2.2.2 (20, August 2019) =
* [+] Added : Added esc_url() for featured image in a design file.
* [*] Fixed title duplicate issue reported by a user.

= 2.2.1 (2, August 2019) =
* [+] Added : Added a new shortcode for post carousel with a design.
* [*] New Shortcode : [recent_post_carousel]
* [+] Added : wp_reset_postdata();

= 2.1 (22, June 2019) =
* [*] Fixed : Fixed readmore button hover issue in all designs.

= 2.0.2 (28, Jan 2019) =
* [*] Fixed an error (Erro: Undifined variable: content) when contant not added in the editor and excerpt is also empty. Thanks @franfellipe2 for showing us this issue.

= 2.0.1 (20, Dec 2018) =
* [*] Update Opt-in flow.

= 2.0 (06-12-2018) =
* [*] Tested with WordPress 5.0 and Gutenberg.
* [*] Tested with Twenty Nineteen theme.
* [*] Fixed some CSS issues.

= 1.5 (22-09-2018) =
* [*] Fixed readmore button issue.
* [*] Fixed some design related issue.

= 1.4.5 (23-08-2018) =
* [+] Added ... at the end of the content limit.

= 1.4.4 (04-06-2018) =
* [*] Fixed some minor issues.

= 1.4.3 (31-05-2018) =
* [*] Follow some WordPress Detailed Plugin Guidelines.

= 1.4.2 (08-05-2018) =
* [*] Fixed issues when featured image not added.

= 1.4.1 (10-3-2018) =
* [*] Created plugin seperate menu 'Recent Post Slider' as per user's feedback.

= 1.4 (9-3-2018) =
* [+] Added the_permalink() for image under design-1, design-2 and design-3

= 1.3.5 (1-3-2018) =
* [+] Added the_permalink() for image under design-4 and taken care if image not added.

= 1.3.4 (12-01-2018) =
* [+] Added new shortcode parameter ie media_size="full" where you can use values : thumbnail, medium, medium_large, large and full

= 1.3.3 (09-10-2017) =
* [+] Thanks to @ciroiodice for halping to imporove our plugin and we have added <code>strip_shortcodes()</code> function if user has not added content in Excerpt section and want to diaplay content form Editor section only.
* [+] <code>strip_shortcodes()</code> finction deletes all shortcode tags from the short content that are going to display in slider. 

= 1.3.2 (04-10-2017) =
* [*] Updated slick.min.js latest version
* [*] Fixed Firefox Reader View

= 1.3.1 (26-09-2017) =
* [*] Thanks to @cruisertech showing us a bug in the version 1.3 ie category parameter was missing.

= 1.3 (26-09-2017) =
* [+] Now use same design multiple time
* [+] Added custom taxonomy support with custom post type
* [*] Plugin files structure modified
* [*] If you are using any cache plugin, kinldy clear all cache after plugin update

= 1.2.8 =
* [+] RTL Supported.

= 1.2.7 =
* [*] Fixed some CSS issue.

= 1.2.6 =
* [+] Added "Post Slider - How it work" tab.
* [-] Removed Por design tab.

= 1.2.5 =
* Added 'show_read_more' shortcode parameter for read more button.
* Updated plugin design page.
* Updated slider js to newer version.
* Fixed some css issues.

= 1.2.4 =
* Fixed some css issues.

= 1.2.3 =
* Fixed some css issues.

= 1.2.2 =
* Fixed some bugs
* Added 3 New shortcode parameters ie **post_type="post", hide_post="1,2,3", show_author="false"**

= 1.2.1 =
* Fixed some bugs

= 1.2 =
* Fixed some bugs
* Added More designs in Pro version and fixed some bug

= 1.1 =
* Fixed some bugs
* Added More designs in Pro version and fixed some bug

= 1.0 =
* Initial release.