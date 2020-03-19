=== AutoListicle: Automatically Update Numbered List Articles ===
Contributors: someguy9
Donate link: https://smartwp.com/
Tags: listicle, auto number, numbered list, shortcode
Requires at least: 4.0.0
Tested up to: 5.2.2
Stable tag: 1.1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Automatically keep your numbered lists in articles displaying the correct number by using this shortcode [auto-list-number].

== Description ==

Easily keep your listicles with numbered lists updated correctly by using this plugin's shortcode [auto-list-number]. This will display the number 1 and increment with ever use. Perfect if you write blog posts with steps or "top 10 lists". This will allow you to easily add items to your lists or move elements around without worrying about updating headings with the correct numbers.

= Shortcode Options =

* **name [auto-list-number name="new-list"]** (Default: "default") If your article has multiple lists this will keep track of multiple numbers. If a name isn't set it will just use the list name of "default". But if you have multiple numbered lists you want to keep track of you can use a unique name for each list in your article.
* **wrapper [auto-list-number wrapper="span"]** (Default: null) Great if you want to wrap each number with a span, div or any html tag. By default the wrapper will include the class "auto-list-number". This can be helpful if you want to style list numbers separately from your headings.
* **after [auto-list-number after=":"]** (Default: ".") After a number is displayed a period and space will be displayed by default. This option is great if you want to change this to a colon or remove it all together. Note that you will have to do this each time the shortcode is displayed, you currently can't set a new default globally. Additionally the default value does not have white space at the end so you'll need to include a space between the shortcode and the rest of your title by default.
* **display [auto-list-number display="total"]** (Default: "increase") This allows you to display a total number from a specific list in your article. For example using [auto-list-number display="total" list="new-list"] will display the total number of times [auto-list-number list="new-list"] is used. Great for adding something like "Here are our 10 tips:" at the top of your article.

== Installation ==

To install this plugin:

1. Download the plugin
2. Upload the plugin to the wp-content/plugins directory,
3. Go to "plugins" in your WordPress admin, then click activate.
4. Add the shortcode [auto-list-number] where you want auto incrementing numbers in your articles (recommended use in headings).

== Frequently Asked Questions ==


== Changelog ==

= 1.1.1 =
* Bug fix from previous release

= 1.1.0 =
* Ability to display the total of a specific number in your list using [auto-list-number display="total"].

= 1.0.0 =
* Initial Release.
