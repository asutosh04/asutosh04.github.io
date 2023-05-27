<?php

// Replace contact@example.com with your real receiving email address
$receiving_email_address = 'asutoshbehera666@gmail.com';

// Include the PHP_Email_Form class
include('php-email-form.php');

// Create a new PHP_Email_Form object
$contact = new PHP_Email_Form;

// Set the form data
$contact->set_to($receiving_email_address);
$contact->set_from_name($_POST['name']);
$contact->set_from_email($_POST['email']);
$contact->set_subject($_POST['subject']);

// Add the messages from the form
$contact->add_message($_POST['name'], 'From');
$contact->add_message($_POST['email'], 'Email');
$contact->add_message($_POST['message'], 'Message', 10);

// Send the email
if ($contact->send()) {
  echo 'Your message has been sent. Thank you!';
} else {
  echo 'There was an error sending your message. Please try again later.';
}

?>