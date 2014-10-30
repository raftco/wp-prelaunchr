# WordPress Prelaunchr

Based on https://github.com/harrystech/prelaunchr and discussed in greater detail on [Tim Ferriss'](http://fourhourworkweek.com/2014/07/21/harrys-prelaunchr-email/) blog - wp-prelaunchr helps you quickly start a viral prelaunch campaign for new companies, products and services. 

The campaign is conducive to social sharing and has prize groups/levels based on the number of people each person refers. 

The groups, amounts, and prizes are completely up to you to set. 

Manually add a shortcode `[prelaunchr]` to any page or post to insert the prelaunchr email sign up form.

At the end of the campaign export your results to csv for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

## Requirements:

* WordPress 4.0 or newer

## Features:

* WordPress 4.0 or newer


### Referral Tracking

Keeps track of who's referred who and how many referrals users have made.

### View Submissions within the Dashboard

View a list of all your entriess/referrals within the WordPress Dashboard.

### Custom Prize Groups/Levels

Setup custom prize levels to entice users to share your campaign amongst their friends. The more referrals someone gets the better the prize!

### CSV Exporter

Download your data as a csv ready for importing into your desired marketing/sales platform such as MailChimp, Campaign Monitor or Salesforce.

### Akismet Integration

If you have Akismet enabled with a valid key, Prelaunchr will automatically check submissions against the Akismet service to help protect your from spam entries.


## Shortcode Attributes

None. Nada. Nothing. Simple insert the `[prelaunchr]` shortcode and you are done.

The campaign is conducive to social sharing and has prize levels based on the number of people each person refers. 

## Template Parts

We've included some initial template parts within the plugin to help with customising the look and feel.

### prelaunchr-form.php

The default sign up form / template displayed to users output by the `[prelaunchr]` shortcode.

### prelaunchr-thankyou.php

The template displayed upon successfully submitting an email address. When revisiting the site this is template that is used to display how many referrals a user has and to get links they can use to refer your company/product/service to others.

### Overridding template parts

Each of prelaunchr template parts can be overridden within your themes/child-themes.

To overide the templateparts simply create a file with the same name as the above within your theme/child themes root directory e.g.

* `/wp-content/themes/{your-theme}/prelaunchr-form.php`
* `/wp-content/themes/{your-theme}/prelaunchr-thankyou.php` 
