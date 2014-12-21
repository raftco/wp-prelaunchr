# Prelaunchr for WordPress

Based on https://github.com/harrystech/prelaunchr and discussed in greater detail on [Tim Ferriss'](http://fourhourworkweek.com/2014/07/21/harrys-prelaunchr-email/) blog - Prelaunchr for WordPress helps you quickly start a prelaunch campaign for new companies, products and services. 

The campaign is conducive to social sharing and has reward groups/levels based on the number of people each person refers. 

The groups, amounts, and rewards are completely up to you to set. 

Manually add a shortcode `[prelaunchr]` to any page or post to insert the prelaunchr email sign up form.

At the end of the campaign export your results to csv for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

## Requirements:

* WordPress 4.0 or newer
* PHP 5.2.X or newer

## Features:

### Referral Tracking

Keeps track of who's referred who and how many referrals users have made.

### View Submissions within the Dashboard

View a list of all your entriess/referrals within the WordPress Dashboard.

### Custom Reward Groups/Levels

Setup custom reward  groups/levels to entice users to share your campaign amongst their friends. The more referrals someone gets the better the reward!

### CSV Exporter

Download your data as a csv ready for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

### Akismet Integration

If you have Akismet enabled with a valid key, Prelaunchr will automatically check submissions against the Akismet service to help protect you from spam entries.

## Installation

### Automatic installation

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "Prelaunchr" and click Search Plugins. Once you've found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by clicking _Install Now_.

### Manual installation
The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application.

* Download the plugin file to your computer and unzip it
* Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
* Activate the plugin from the Plugins menu within the WordPress admin.

## Usage

Simply insert the `[prelaunchr]` shortcode into a page (or post) and you are done.

In most situations site managers will use the short code on a static front page. 

Details about creating a static front page can be found in [the codex](http://codex.wordpress.org/Creating_a_Static_Front_Page)


## Shortcode Attributes

None. Nada. Nothing. Simply insert the `[prelaunchr]` shortcode and you are done.

The campaign is conducive to social sharing and has prize levels based on the number of people each person refers. 

## Template Parts

We've included some initial templates/template parts within the plugin to help with customising the look and feel.

### prelaunchr-form.php

The default sign up form / template displayed to users output by the `[prelaunchr]` shortcode.

### prelaunchr-thankyou.php

The template displayed upon successfully submitting an email address.

Includes the link users can use to refer your company/product/service to others as well as social integration.

### Overridding template parts

Each of prelaunchr templates/template parts can be overridden within your themes/child-themes.

To override the template parts simply create a prelaunchr folder within yout theme place your modified versions of our templates within being sure to keep the keep same directoy structure and file names.

* `/wp-content/themes/{your-theme}/prelaunchr/prelaunchr-form.php`
* `/wp-content/themes/{your-theme}/prelaunchr/prelaunchr-thankyou.php`

## Hooks and Filters

We've included some hooks and filters for devs to use to further customise/extend some of the plugins functionality.

* `prelaunchr_form` action - located at the beginning of the default prelaunchr form
* `prelaunchr_is_valid_email` filter - filters the results of whether the email is valid on not. Accepts/returns the email address if valid.
* `prelaunchr_record_submission` action - located at the beggining of the record submission method with access to the email address and data and format arrays.
* `prelaunchr_record_submission` filter - allows devs to filter the data to be stored before it is recorded within the database. 
* `prelaunchr_after_record_submission` action - located at the end of the record submission method with access to the database insert results, data and format arrays.
* `prelaunchr_delete_entries` action - located at the end of the list table process_bulk_action method with access to the database delete results and the ids to be deleted.

There are also some jQuery events triggered upon form submission and receipt of ajax responses e.g.

* `prelaunchr_form_submit` - triggered upon a successful form submission
* `prelaunchr_response` - triggered after submission along with response data (success or fail)

## Add-ons (COMING SOON)

Paid Support, themes and add-ons, such as the __mailchimp addon__ can be [found here](http://wp-prelaunchr.com). Take a look!

## Contributing and reporting bugs

You can contribute code to this plugin via GitHub: [https://github.com/raftco/wp-prelaunchr](https://github.com/raftco/wp-prelaunchr)

Contributions like translations/localizations are extremely grateful.

## Prelaunchr Support

Use the WordPress.org forums for community support (note we do not personally monitor these forums).

We cannot offer support directly for free but if you'd like paid support or to provide a donation we can work something out. 

If you spot a bug or want to contribute some code, you can of course log it on [Github](https://github.com/raftco/wp-prelaunchr) instead where I can act upon it more efficiently.

If you want help with a customisation, please hire a developer!

## Other Notes

Some other interesting/misc notes about the plugin and its functionality.

### Honeypot field

The plugin adds a hidden "name" field to the form to assist with catching out spam bots as a [honeypot](http://en.wikipedia.org/wiki/Honeypot_%28computing%29).

If the field is filled out the submission won't work.

The field is hidden using javascript.

### Register theme support

If a theme registers that it has 'prelaunchr' support using

```
add_theme_support('prelaunchr');
```
and if a static front page has been configured (under Settings -> Reading), the plugin assumes that the front page is showing the prelaunchr form/templates using something like:

```
if ( class_exists( 'Prelaunchr' ) ) {
	Prelaunchr()->display();
}
```
and it automatically enqueues the prelaunchr styles and javascript.

## FAQ

Stuff people have asked, you might also like to know:

### Can we add additional fields beside email address?

No, sorry not by default and we don't plan on adding default support for more in the near future.

It's generally accepted that more fields dramatically kills conversion rate. Causing people to stop and think will not help your campaign be shared.

### Can we make the rest of the site members only?

We have purposely not included any members only functionality within this plugin as there are plenty of existing plugins with this functionality available and we are trying to keep the core functionality as succinct as possible.

We'd recommend trying something like [Members](https://wordpress.org/plugins/members/).

### I’m concerned about duplicate pages for SEO

We don’t create new pages, just add a parameter to your URLS.

Advanced users can reduce the risk of duplicate content erros by:

* Making sure you have canonical URLs setup on your WordPress website. Many SEO plugin tools will have this available out of the box, with the popular tool being Yoast’s WordPress SEO Plugin. More details on canonical URLs usage scenarios can be found at the Google documentation [here](https://support.google.com/webmasters/answer/139066?hl=en).
* Utilize Google's [Paramter Tool](https://www.google.com/webmasters/tools/crawl-url-parameters) to give Google information about how to handle URLs containing the Prelauncher parameters.

## Credits

Credit also needs to go to the following (used within this plugin):

* __[jQuery Cookie](https://github.com/carhartl/jquery-cookie)__
* __[Node UUID](https://github.com/broofa/node-uuid)__
* __[parseCSV](https://github.com/parsecsv/parsecsv-for-php)__
* __[Share Button](http://sharebutton.co/)__
