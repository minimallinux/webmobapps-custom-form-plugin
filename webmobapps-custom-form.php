<?php
 /**
 * Plugin Name: WP Contact Details Plugin
 * Plugin URI: https://github.com/minimallinux/webmobapps-custom-form-plugin.git/
 * Description: WordPress Contact Form Plugin with shortcode and submit/validation functions and error messages.
 * Version: 1.0
 * Author: P A McGowan
 * Author URI: https://webmobapps.com/
 * Original Class Author URI: http://www.scriptbaker.com/author/tahir/ (Adapted From)
 * License: GPL2
 */
 class CustomForm
{
  public function __construct()
    {
        add_action('init', array($this, 'init'));
        add_shortcode('webmobapps_custom_form', array($this, 'shortcode_handler'));
    }
 
    public function init()
    {
        if (!empty($_POST['nonce_custom_form'])) {
            if (!wp_verify_nonce($_POST['nonce_custom_form'], 'handle_custom_form')) {
                die('You are not authorized to perform this action.');
            } else {
                $nameErr = $phoneErr = $emailErr = $messageErr = null;
                if (empty($_POST['name'])) {
                   $nameErr = new WP_Error('empty_error', __('Please enter your name.', 'custom-form'));
                    wp_die($nameErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                }
                else
                   $name = filterName($_POST["name"]);
                if($name == FALSE){
                   $nameErr = new WP_Error('empty_error', __('Please enter a valid name.', 'customform'));
                    wp_die($nameErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                   }
                   if (empty($_POST['phone'])) {
                    $phoneErr = new WP_Error('empty_error', __('Please enter your phone no.', 'custom-form'));
                     wp_die($phoneErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                 }
                 else
                    $phone = filterPhone($_POST["phone"]);
                if (!empty($_POST['budget'])) {
                    $budget = ($_POST["budget"]);
                    } 
                 if (!empty($_POST['appchoice'])) {
                    $budget = ($_POST["appchoice"]);
                    }       
                 if (empty($_POST['email'])) {
                        $emailErr = new WP_Error('empty_error', __('Please enter your email address.', 'custom-form'));
                         wp_die($emailErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                    }
                 else
                 $email = filterEmail($_POST["email"]);
                 if($email == FALSE){
                        $emailErr = new WP_Error('empty_error', __('Please enter a valid email address.', 'customform'));
                         wp_die($emailErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                        } 
                 if (empty($_POST['message'])) {
                    $messageErr = new WP_Error('empty_error', __('Please enter your message.', 'custom-form'));
                        wp_die($messageErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                        }
                else
                    $message = filterString($_POST["message"]);
                if($message == FALSE){
                        $messageErr = new WP_Error('empty_error', __('Please enter a valid message.', 'customform'));
                        wp_die($messageErr->get_error_message(), __('CustomForm Error', 'custom-form'));
                        }  
                   deliverMail();        
               }
            }
        }
 
function shortcode_handler($atts) {
    return "<form method='post' action=''>
<fieldset data-uk-margin>
<label class='label'>Name</label>
<div class='uk-form-row'>
<input class='uk-input uk-form-large' name='name' type='text' placeholder='Name' >
</div>
<label class='label'>Phone</label>
<div class='uk-form-row'>
<input class='uk-input uk-form-large' name='phone' type='tel' placeholder='Phone'>
</div>
<label class='label'>Your Budget</label>
<div class='uk-form-row'>
<div class='uk-select'>
    <select name='budget'>
    <option value='Standard Package'>Standard Package</option>
    <option value='&pound;1000 - &pound;10000'>&pound;1000 - &pound;10000</option>
    <option value='Over &pound;10000'>Over &pound;10000</option>
    <option value='Over &pound;20000'>Over &pound;20000</option>
    </select>
</div>
</div>
<label class='label'>Website Or Mobile App</label>
<div class='uk-form-row'>
<div class='uk-select'>
<select name='appchoice'>
<option value='Mobile App'>Mobile App</option>
<option value='Website'>Website</option>
</select>
</div>
</div>
<label class='label'>Email</label>
<div class='uk-form-row'>
<input class='uk-input uk-form-large' name='email' type='email' placeholder='Email'>
</div>
<label class='label'>Message Or Project Details</label>
<div class='uk-form-row'>
<textarea class='uk-textarea' rows='5' name='message' placeholder='Message Or Project Details'></textarea>
</div>
    " . wp_nonce_field('handle_custom_form', 'nonce_custom_form') . "
        <div class='uk-form-row'>
<button class='uk-button tm-button-custom' name='submit'>Send Message</button>
</div>
</fieldset>
</form>";
    }
}
//New Custom Form
$CustomForm = new CustomForm();

// Functions to filter user inputs
function filterName($field){
    // Sanitize user name
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    
    // Validate user name
    if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+/")))){
        return $field;
    }else{
        return FALSE;
    }
} 
 function filterPhone($field){
    // Sanitize user phone
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
}      
function filterEmail($field){
    // Sanitize e-mail address
    $field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);
    
    // Validate e-mail address
    if(filter_var($field, FILTER_VALIDATE_EMAIL)){
        return $field;
    }else{
        return FALSE;
    }
}
function filterString($field){
    // Sanitize string
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);
    if(!empty($field)){
        return $field;
    }else{
        return FALSE;
    }
}
function deliverMail() {
 // if the submit button is clicked, send the email
    if ( isset( $_POST['submit'] ) ) {
       $name = ($_POST["name"]);
       $phone = sanitize_text_field($_POST["phone"]);
       $email = ($_POST["email"]);
       $budget = sanitize_text_field($_POST["budget"]);
       $appchoice = sanitize_text_field($_POST["appchoice"]);
       $message = esc_textarea($_POST["message"]);
       $subject = "An Inquiry from ".$name;
       // get the blog administrator's email address
        $to = get_option( 'admin_email' );
       //Compose the message details
        $body = "From: $name <$email>" . "\r\n" .
                "Phone No: $phone" . "\r\n" .
                "Area Of Interest: $appchoice" . "\r\n" .
                "Approximate Budget: $budget" . "\r\n" .
                "Email Address: $email" . "\r\n" .
                "Message: $message";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $body ) ) {
            echo '<div>';
            echo '<h2>Thanks for contacting us, expect a response soon.</h2>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}


?>  

 