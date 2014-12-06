# WordPress Prelaunchr

Based on https://github.com/harrystech/prelaunchr and discussed in greater detail on [Tim Ferriss'](http://fourhourworkweek.com/2014/07/21/harrys-prelaunchr-email/) blog - wp-prelaunchr helps you quickly start a viral prelaunch campaign for new companies, products and services. 

The campaign is conducive to social sharing and has reward groups/levels based on the number of people each person refers. 

The groups, amounts, and rewards are completely up to you to set. 

Manually add a shortcode `[prelaunchr]` to any page or post to insert the prelaunchr email sign up form.

At the end of the campaign export your results to csv for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

## Requirements:

* WordPress 4.0 or newer

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

If you have Akismet enabled with a valid key, Prelaunchr will automatically check submissions against the Akismet service to help protect your from spam entries.


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


## Other Notes

Some other interesting/misc notes about the plugin and its functionality.

### Robots Noindex

The plugin adds a robots meta noindex tag e.g.

```
<meta name='robots' content='noindex,follow' />
```

to the head of the referrer thankyou/list pages.

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