<?php
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('../wy_setting.php');

if ( isset( $_POST['email'] ) && empty( $_POST['s_check'] ) ) :

	$email = email;

	$strings = array(
		'default_subject' => 'Message on your website',
		'default_name'    => 'Anonymous',
		'error_message'   => '<strong>Error:</strong> %s cannot be left blank', // %s is the error name
		'invalid_email'   => '<strong>Error:</strong> Email address is invalid',
		'email_success'   => 'Thank you! Your email has been sent',
		'email_error'     => 'There was a problem sending your email. Please try again'
	);

	$required = array(
		'email'   => 'Email',
		'message' => 'Message'
	);

	$errors = array();

	$name    = sanitize_string( $_POST['name'] );
	$from    = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL );
	$subject = sanitize_string( $_POST['subject'] );
	$message = sanitize_string( $_POST['message'] );

	if ( ! filter_var( $from, FILTER_VALIDATE_EMAIL ) )
		   $errors[] = $strings['invalid_email'];

	if ( empty( $name ) )
		$name = $strings['default_name'];

	if ( empty( $subject ) )
		$subject = $strings['default_subject'];

	foreach ( $required as $key => $value ) :
		if ( isset( $_POST[$key] ) && ! empty( $_POST[$key] ) )
			continue;
		else
			$errors[] = sprintf( $strings['error_message'], $value );
	endforeach;

	if ( empty( $errors ) ) {

		if ( mail( $email, $subject, utf8_decode( $message ), "From: ".$name." <".$from.">\r X-Mailer: PHP/".phpversion()))
			echo '<p class="alert alert-success">' . $strings['email_success'] . '</p>';
		else
			echo '<p class="alert alert-error">' . $strings['email_error'] . '</p>';

	} else {

		echo '<div class="alert alert-warning">';
			echo implode( '<br />', $errors );
		echo '</div>';
	}

else :
	die("You're not allowed to access this page directly");

endif;
function sanitize_string( $string ) {
	return stripslashes( filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES ) );
}
?>