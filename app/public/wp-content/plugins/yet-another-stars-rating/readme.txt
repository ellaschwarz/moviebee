=== Yasr - Yet Another Stars Rating ===
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AXE284FYMNWDC
Tags: rating, rate post, rate page, star rating, google rating, votes
Requires at least: 4.3.0
Contributors: Dudo
Tested up to: 5.4
Requires PHP: 5.3
Stable tag: 2.2.1
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Boost the way people interact with your site with an easy WordPress stars rating system! With schema.org rich snippets YASR will improve your SEO

== Description ==
Improving the user experience with your website is a top priority for everyone who cares about their online activity,
as it promotes familiarity and loyalty with your brand, and enhances visibility of your activity.

Yasr - Yet Another Stars Rating is a powerful way to add SEO-friendly user-generated reviews and testimonials to your
website posts, pages and CPT, without affecting its speed.

= How To use =

= Reviewer Vote =
With the classic editor, when you create or update a page or a post, a box (metabox) will be available in the upper right corner where you'll
be able to insert the overall rating.
With the new Guteneberg editor, just click on the "+" icon to add a block and search for Yasr Overall Rating.
You can either place the overall rating automatically at the beginning or the end of a post (look in "Settings"
-> "Yet Another Stars Rating: Settings"), or wherever you want in the page using the shortcode [yasr_overall_rating] (easily added through the visual editor).

= Visitor Votes =
You can give your users the ability to vote, pasting the shortcode [yasr_visitor_votes] where you want the stars to appear.
If you're using the new Gutenberg editor, just click on the "+" icon to add a block and search for Yasr Visitor Votes
Again, this can be placed automatically at the beginning or the end of each post; the option is in "Settings" -> "Yet Another Stars Rating: Settings".
[See the supported caching plugins](https://wordpress.org/plugins/yet-another-stars-rating/#does%20it%20work%20with%20caching%20plugins%3F)

= Multi Set =
Multisets give the opportunity to score different aspects for each review: for example, if you're reviewing a videogame, you can create the aspects "Graphics",
"Gameplay", "Story", etc.

= Migration tools =
You can easily migrate from WP-PostRatings, kk Star Ratings, Rate My Post and Multi Rating
A tab will appear in the settings if one of these plugin is detected.

= Supported Languages =

Check [here](https://translate.wordpress.org/projects/wp-plugins/yet-another-stars-rating/dev) to see if your translation is up to date.
Write on the [forum](https://wordpress.org/support/plugin/yet-another-stars-rating) to ask to become a validator :)

In this video I'll show you the "Auto Insert" feature and manual placement of YASR basic shortcodes.
[youtube https://www.youtube.com/watch?v=M47xsJMQJ1E]

= Related Link =
* Documentation at [Yasr Official Site](https://yetanotherstarsrating.com/docs/)
* [Demo page for Overall Rating and Vistor Rating](https://yetanotherstarsrating.com/yasr-basics-shortcode/)
* [Demo page for Multi Sets](https://yetanotherstarsrating.com/yasr-multi-sets/)
* [Demo page for Rankings](https://yetanotherstarsrating.com/yasr-rankings/)

Do you want more feature? [Check out Yasr Pro!](https://yetanotherstarsrating.com/#yasr-pro)

== Installation ==
1. Install Yet Another Stars Rating either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Yet Another Star Rating menu in Settings and set your options.

== Frequently Asked Questions ==

= What is "Overall Rating"? =
It is the vote given by who writes the review: readers are able to see this vote in read-only mode. Reviewer can vote using the box on the top rigth when writing a new article or post (he or she must have at least the "Author" role). Remember to insert this shortcode **[yasr_overall_rating]** to make it appear where you like. You can choose to make it appear just in a single post/page or in archive pages too (e.g. default Index, category pages, etc).

= What is "Visitor Rating"? =
It is the vote that allows your visitors to vote: just paste this shortcode **[yasr_visitor_votes]** where you want the stars to appear.
[See the supported caching plugins](https://wordpress.org/plugins/yet-another-stars-rating/#does%20it%20work%20with%20caching%20plugins%3F)

[Demo page for Overall Rating and Vistor Rating](https://yetanotherstarsrating.com/yasr-basics-shortcode/)


= What is "Multi Set"? =
It is the feature that makes YASR awesome. Multisets give the opportunity to score different aspects for each review: for example, if you're reviewing a videogame, you can create the aspects "Graphics", "Gameplay", "Story", etc. and give a vote for each one. To create a set, just go in "Settings" -> "Yet Another Stars Rating: Settings" and click on the "Multi Sets" tab. To insert it into a post, just paste the shortcode that YASR will create for you.

[Demo page for Multi Sets](https://yetanotherstarsrating.com/yasr-multi-sets/)

= What is "Ranking reviews" ? =
It is the 10 highest rated item chart by reviewer. In order to insert it into a post or page, just paste this shortcode **[yasr_top_ten_highest_rated]**

= Wht is "Users' ranking" ? =
This is 2 charts in 1. Infact, this chart shows both the most rated posts/pages or the highest rated posts/pages.
For an item to appear in this chart, it has to be rated twice at least.
Paste this shortcode to make it appear where you want **[yasr_most_or_highest_rated_posts]**

= What is "Most active reviewers" ? =
If in your site there are more than 1 person writing reviews, this chart will show the 5 most active reviewers. Shortcode is **[yasr_top_5_reviewers]**

= What is "Most active users" ? =
When a visitor (logged in or not) rates a post/page, his rating is stored in the database. This chart will show the 10 most active users, displaying the login name if logged in or "Anonymous" otherwise. The shortcode : **[yasr_top_ten_active_users]**

[Demo page for Rankings](https://yetanotherstarsrating.com/yasr-rankings/)

= Wait, wait! Do I need to keep in mind all this shortcode? =
If you're using the new Gutenberg editor, you don't need at all: just use the blocks.
If, instead, you're using the classic editor, in the visual tab just click the "Yasr Shortcode" button above the editor

= Does it work with caching plugins? =
Most def! Yasr supports these caching plugins:
* [Wp Super Cache](https://wordpress.org/plugins/wp-super-cache/)
* [LiteSpeed Cache](https://wordpress.org/plugins/litespeed-cache/)
* [Wp Fastest Cache](https://wordpress.org/plugins/wp-fastest-cache/)
* [Wp Rocket](https://wp-rocket.me)

= Why I don't see stars in google? =
Please be sure that if you use mostly the "yasr_visitor_votes" shortcode, you've to select "Aggregate Rating" to the question "Which rich snippet do you want to use?" in the General Settings.
If, instead, your website use mostly the "yasr_overall_rating" shortcode, you've to select "Review Rating".
Google will need some days to index the stars.
You can use the [Structured Data Testing Tool](https://search.google.com/structured-data/testing-tool/u/0/) to validate your page.
If you set up everythings fine, in 99% of cases your stars will appear in a week.
If doesn't, it's suggested to ask in a SEO oriented forum.


== Screenshots ==
1. Example of yasr in a videogame review
2. Another example of a restaurant review
3. User's ranking showing most rated posts
4. User's ranking showing highest rated posts
5. Ranking reviews

== Changelog ==

The full changelog can be found in the plugin's directory. Recent entries:

= 2.2.1 =
* FIXED: MultiSet returns "undefined" in editor screen
* FIXED: MultiSet average always shows
* FIXED: Users who cannot publish posts can't access the editor screen
* FIXED: Password reset display an error page in some circumstances

= 2.2.0 =
* TWEAKED: most of code of the main shortcodes has been rewritten; YASR is faster than ever.
* FIXED: gutenberg shortcode preview yasr_top_ten_highest_rated shortcode


= 2.1.4 =
* FIXED: undefined variable when saving/updating post or page
* TWEAKED: changed delete_post_meta with delete_metadata
* TWEAKED: Updated support link in settings pages


= 2.1.3 =
* FIXED: in some older (and rare) mysql or mariadb versions, the function JSON_OBJECT is not supported.
This was causing the import of multiset data failing since version 2.0.9
* FIXED: in backed, multiset rating didn't get saved if set_id = 0
* Minor changes

= 2.1.2 =
* NEW FEATURE: is now possible to import rating from Multi Rating
* TWEAKED: updated freemius sdk to version 2.3.2
* FIXED: MultiSet fix for users that enabled it on version prior of 2.1.0 and had setid=0

= 2.1.1 =
* FIXED: on new installations, tables yasr_multi_set and yasr_multi_set_fields didn't get created

= 2.1.0 =
* TWEAKED: This is the second important release after 2.0.9. YASR Database structure is now more efficent if you're
using MultiSets. If everything works, it is possible to remove the table _yasr_multi_values.
* TWEAKED: "logs" page is now called "stats"
* NEW FEATURE: inside the "stats" page is now possible to see, and delete, stats for yasr_visitor_multiset shortcode
* TWEAKED: Code cleanup

= 2.0.9 =
* TWEAKED: This is the first of two important releases for YASR. Some changes in the database has been done.
**BACKUP YOUR DATABASE BEFORE UPDATING**
* FIXED: RTL support in admin area

= 2.0.8 =
* NEW FEATURE: Added a box to the top right, that will allow to select if the post is a review or not.
More info here [here](https://yetanotherstarsrating.com/yasr-rich-snippets/)
* FIXED: warning that in some rare circumstances may appear in yasr_schema function

= 2.0.7 =
* TWEAKED: changed a define to support php version <7

= 2.0.6 =
* NEW FEATURE: in Gutenberg, is now possible to rate "overall Rating" in both blocks and sidebar
* IMPORTANT CHANGES ON RICH SNIPPETS: due to [this google announcement] (https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html)
BlogPosting data type doesn't have ratings anymore. These types have been added: Book, Movie, Game.
Many other support types will comes.

= 2.0.5 =
* FIXED:  Warning: array_merge(): on line 552 in yasr-functions.php

= 2.0.4 =
* NEW FEATURE: is now possible align to left, center or right if auto insert is used
* TWEAKED: check tidy version before use it: this will fix some enconding issue if an old version in server is installed
* TWEAKED: esc_html used instead of htmlspecialchars in the settings page
* TWEAKED: added a new filter yasr_filter_existing_schema: useful if there is the need to add new rich snippets
* TWEAKED: minor changes on schema function (thanks to Olivier)


= 2.0.3 =
* TWEAKED: some changes in settings page
* TWEAKED: new default values for new installations.

= 2.0.2 =
* TWEAKED: Multisite support!!
* TWEAKED: In gutenberg editor, now a link for Yasr Sidebar is available
* FIXED: gutenberg showing "updated failed" in some rare circumstances

= 2.0.1 =
* TWEAKED: changed code in yasr-shortcode-functions.php to support php version < 7

= Additional Info =
External Libraries: [Rater](https://github.com/fredolss/rater-js)
[tippy](https://atomiks.github.io/tippyjs/)

Flat star icon made by[Freepik](http://www.freepik.com)
from [www.flaticon.com](https://www.flaticon.com/) is licensed by [CC 3.0 BY](http://creativecommons.org/licenses/by/3.0/)

