=== Prelaunchr for WordPress ===
Contributors: raftco, jconroy
Tags: prelaunchr, launch, coming soon, referrals, social
Requires at least: 4.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Useful plugin for setting up a pre-launch site.

== Description ==

Based on [Harry's Prelaunchr](https://github.com/harrystech/prelaunchr) and discussed in greater detail on [Tim Ferriss' blog](http://fourhourworkweek.com/2014/07/21/harrys-prelaunchr-email/) Prelaunchr for WordPress helps you quickly start a pre-launch campaign for new companies, products and services. 

Campaigns are conducive to social sharing and rewards / prize groups can be set based on the number of people each person refers. The groups, amounts, and rewards are completely up to you to set. 

Manually add a shortcode `[prelaunchr]` to any page or post to insert the prelaunchr email sign up form.

At the end of the campaign export your results to csv for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

= Features =

* Referral tracking
* Set and manage referral rewards / prizes
* View submissions within the WP dashboard
* Export submission to CSV
* Anti Spam honeypot field
* Akismet Integration
* Developer Friendly
* Theme integration - with templates that can be overridden

= Add-ons =

Paid Support, themes and add-ons, such as the __mailchimp addon__ can be [found here](http://wp-prelaunchr.com). Take a look!

= Contributing and reporting bugs =

You can contribute code to this plugin via GitHub: [https://github.com/FindingSimple/wp-prelaunchr](https://github.com/FindingSimple/wp-prelaunchr)

Contributions like translations/localizations are extremely grateful.

= Support =

Use the WordPress.org forums for community support (note we do not personally monitor these forums).

We cannot offer support directly for free but if you'd like paid support or to provide a donation we can work something out. 

If you spot a bug or want to contribute some code, you can of course log it on [Github](https://github.com/FindingSimple/wp-prelaunchr) instead where I can act upon it more efficiently.

If you want help with a customisation, please hire a developer!

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "Prelaunchr" and click Search Plugins. Once you've found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by clicking _Install Now_.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application.
* Download the plugin file to your computer and unzip it
* Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
* Activate the plugin from the Plugins menu within the WordPress admin.

== Frequently Asked Questions ==

= Can we add additional fields beside email address? =

No, sorry not by default and we don't plan on adding default support for more in the near future.

It's be proven elsewhere that more fields dramatically kills conversion rate. Causing people to stop and think will not help your giveaway to be shared.

= Can we make the rest of the site members only? =

We have purposely not included any members only functionality within this plugin as there are plenty of existing plugins with this functionality available and we are trying to keep the core functionality as succinct as possible.

We'd recommend trying something like [Members](https://wordpress.org/plugins/members/).

= I’m concerned about duplicate pages for SEO =

We don’t create new pages, just add a parameter to your URLS. 

Advanced users can reduce the risk of duplicate content erros by:
* Making sure you have canonical URLs setup on your WordPress website. Many SEO plugin tools will have this available out of the box, with the popular tool being Yoast’s WordPress SEO Plugin. More details on canonical URLs usage scenarios can be found at the Google documentation [here](https://support.google.com/webmasters/answer/139066?hl=en).
* Utilize Google's [Paramter Tool](https://www.google.com/webmasters/tools/crawl-url-parameters) to give Google information about how to handle URLs containing the Prelauncher parameters.

== Requires ==

* PHP 5.2.X+

== Changelog ==

= 1.0 =
* Initial release.