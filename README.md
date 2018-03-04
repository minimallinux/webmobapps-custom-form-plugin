Simple Lightweight Custom Contact Form Plugin for Wordpress with shortcode for templates or theme areas, and functions/validations to submit to WP Admin (or other) Email Address. Fairly easy to customize.

Sample form uses UIKit CSS but it can be anything.

Add your form code into the function "shortcode_handler()" in the return field as in demo see steps below.
Be sure to add the submit button AFTER the wp_nonce_field at the end.

Can be used in Blade templates if using Sage 9 or others with shortcode
<?php echo do_shortcode("[webmobapps_custom_form]"); ?> 

or in other areas via WP Admin etc. [webmobapps_custom_form]

Taken from an original class by Tahir at http://www.scriptbaker.com/author/tahir/

and extended a bit with extra validation and submission functions, submit success/error message with echo or redirect.

Error messages are made with WP_Error class but can be changed to an alert or something else.

4 Step procedure.

1. Add your <form> elements to shortcode_handler() function as demoed and if using template <?php echo do_shortcode("[webmobapps_custom_form]"); ?> in the required place. Make sure submit button is placed below wp-nonce_field.

2. Change the entries in init() function (They are All in one else statement) to match the html form in in shortcode_handler(); Delete or add more to suit.  deliverMail() runs at the end.

3. Adjust your error messages which are initially set to null and remove or add more as required  ($nameErr = $phoneErr = $emailErr = $messageErr = null) these are matched to functions at bottom of page.

4. Alter the variables in deliverMail() to suit your form fields and the "to" email address which is currently wp-admin email.

Validation functions cover regular validations of text, string, phone and email (havent gone overboard with phone) so will cover much of the usual stuff.

Not WYSIWYG but easy enough to customise.

testfiles folder is just some reasearch items.
