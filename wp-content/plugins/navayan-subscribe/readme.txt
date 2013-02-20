=== Navayan Subscribe ===
Contributors: Amol Nirmala Waman
Tags: navayan, subscribe, subscribers, register, registration, mailing, email, email template, wp subscribe, subscribe plugin, mailing list, subscribe email, notify, notification, widget, unsubscribe, double optin
Requires at least: 3.3+
Tested up to: 3.6-alpha-23200
Stable tag: 1.12
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name=NavayanSubscribe

Allows visitors to easily and quickly subscribe to your website with double optin, email templates, notifications.

== Description ==
[Navayan Subscribe](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/) allows your visitors to easily and quickly subscribe to your website with double optin process, custom email templates, post/page notifications. Can be used as sidebar widget.

**FEATURES**

* Double opt-in Subscribe and Un-Subscribe
* Default form field - Email
* Extended form fields - Name and Custom field
* Custom form heading, field labels
* Custom Email Templates
* Custom Error/Success/Information messages
* Option to hide form after success
* Send new subscriber notification to admin
* Send new post notification to subscribers
* Sidebar Widget
* Option to send post content in email
* Translate text support
* User gets removed if Un-Subscribed
* Checks whether user logged in or not
* jQuery form validation
* HTML5 Placeholder
* HTML5 Validation with jQuery fallback
* Displays various users count
* Works with Custom Post Types too
* Featured image in post notification

**Links**

* [Plugin's Homepage](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/)
* [FAQs & Discussion](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/#comments)
* [Similar Topics](http://blog.navayan.com/wordpress/)
* [Navayan CSV Export](http://blog.navayan.com/navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/)


== Installation ==
**REQUIRED**: WordPress 3.3 or newest

1. Download 'Navayan Subscribe' wordpress plugin
2. Upload and Extract in `wp-content/plugins/` directory. Make sure 'wp-content' has write permission
3. Go to 'Dashboard -> Plugins' and activate it
4. Go to 'Dashboard -> Tools -> Navayan Subscribe'
5. For more details please visit [http://blog.navayan.com/](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/)

**Links**

* [Plugin's Homepage](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/)
* [FAQs & Discussion](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/#comments)
* [Similar Topics](http://blog.navayan.com/wordpress/)
* [CSV Export](http://blog.navayan.com/navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/)


== Usage ==
1. Activate the plugin
2. Go to Dashboard -> Tools -> Navayan Subscribe
3. Customize the form settings and email templates as you want
4. Add this code:

`<?php
   if ( function_exists('navayan_subscribe') ){
      echo navayan_subscribe();
   }
?>`

to your PHP template page. Mostly it goes to `footer.php` file or put this shortcode `[navayan_subscribe]` in your post/page

And if you want to change the form UI (if needed) tweak your style.css as follows:
`#ny_subscribe_wrapper label { width: 100px !important}
#ny_subscribe_wrapper input[type="text"] { width: 120px !important}`

If you want to display Navayan Subscribe form in sidebar then go to 'Appearance -> Widgets', drag and drop 'Navayan Subscribe' widget in your sidebar.

If you feel this plugin saved your efforts, it would be great if you Rate it!

**Links**

* [Plugin's Homepage](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/)
* [FAQs & Discussion](http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/#comments)
* [Similar Topics](http://blog.navayan.com/wordpress/)
* [Export WP to CSV](http://blog.navayan.com/navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/)


== Screenshots ==
1. Plugin listed under 'Dashboard -> Tools' tab
2. Send notification checkbox in 'Publish' metabox
3. Subscribe form in sidebar widget
4. UnSubscribe form
5. Subscribe and UnSubscribe form and messages settings
6. Custom Email Templates
7. Subscribe confirmation email
8. Post/page notification email to subscribers


== Changelog ==

= 1.12 (20121225) =
* NEW: Can use featured image in notification template
* NEW: Show subscribers count to users - optional
* NEW: New role 'unconfirmed' for Un-confirmed subscribers
* FIX: Un-confirmed subscribers count

= 1.11 (20121118) =
* NEW: Code Refactored
* FIX: Removing older 'Navayan Unsubscribe' page entries during deactivation

= 1.10 (20121114) =
* NEW: Double opt-in
* NEW: HTML5 Validation with jQuery fallback
* NEW: Displays various users count
* NEW: More Email Templates
* NEW: Enhanced post/page notifications
* NEW: Fallback for post publish/update actions
* FIX: Multiple notifications

= 1.1.9 (20121019) =
* FIX: Removed post meta dependency

= 1.1.8 (20120926) =
* NEW: Enhanced email template for tags, categories, readability
* NEW: HTML5 Placeholder
* NEW: Compatibility with theme sidebar
* FIX: Clean Uninstall
* FIX: W3C validated CSS form class

= 1.1.7.5 (20120823) =
* NEW: Better CSS control on submit buttons for Front-End developers
* NEW: Deleting 'Navayan Unsubscribe' page if plugin gets uninstalled

= 1.1.7.4 (20120822) =
* NEW: Custom email template
* NEW: Custom messages and labels
* NEW: Admin control over notification
* NEW: Custom post type notification
* NEW: Option to send post content or excerpt
* NEW: Easy Un-Subscribe form
* NEW: jQuery form validation

= 1.1.7.3 (20120710) =
* NEW: Checks subscribe email send subject - New or Updated post
* FIX: Condition which was preventing email to deliver in some cases

= 1.1.7.2 (20120709) =
* NEW: Version check as subscribe form class

= 1.1.7 (20120706) =
* FIX: Avoided various filters
* FIX: Optimized the code

= 1.1.6 (20120605) =
* NEW: Custom message if user is logged in
* FIX: Checks if post is not already published
* FIX: Two typos

= 1.1.5 (20120519) =
* NEW: Translate text support
* NEW: Checks whether user logged in or not
* NEW: User can Un-Subscribe
* NEW: Subscriber gets Un-Subscribe link
* NEW: User gets removed if Un-Subscribed
* NEW: Custom Un-Subscribe Message
* FIX: Enhanced post notifications
* FIX: Widget title support

= 1.1.4.3 (20120408) =
* NEW: Option to send post content in email
* FIX: Filtered Username

= 1.1.4.2 (20120330) =
* NEW: Option to use default style
* FIX: Sidebar widget form field width

= 1.1.4.1 (20120317) =
* NEW: Altered post fetch criteria

= 1.1.4 (20120316) =
* NEW: Sidebar Widget

= 1.1.3 (20120315) =
* NEW: Added Feature list

= 1.1.2 (20120314) =
* NEW: New published post notification email to subscribers

= 1.1.1 (20120303) =
* NEW: Added support for subscribe notification email to admin

= 1.1 (20120302) =
* NEW: Added shortcode `[navayan_subscribe]` support

= 1.0 (20120229) =
* Compatible with wordpress 3.3+
* Minimum subscribe form
* Extended subscribe form
* Wordpress admin itself can add subscribers
* Form settings to customize the subscribe form


== Donate ==

We call 'Donation' as 'Dhammadana'. It help us to contribute more to open source community.
[Donate ie. Dhammadana](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name=NavayanSubscribe)