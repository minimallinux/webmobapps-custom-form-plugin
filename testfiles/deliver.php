<?php
function deliver_mail() {
    // if the submit button is clicked, send the email
    if ( isset( $_POST['submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["name"] );
        $phone    = sanitize_text_field( $_POST["phone"] );
        $budget   = sanitize_text_field( $_POST["budget"] );
        $appchoice   = sanitize_text_field( $_POST["appchoice"] );
        $email   = sanitize_email( $_POST["email"] );
        $message = esc_textarea( $_POST["message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting us, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}
function wpcf_shortcode() {
    ob_start();
    deliver_mail();
    contact_form_code();
    return ob_get_clean();
}
add_shortcode( 'contact_form', 'wpcf_shortcode' );
?>
