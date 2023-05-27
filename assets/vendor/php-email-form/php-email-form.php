<?php

class PHP_Email_Form
{

    private $ajax = false;
    private $to;
    private $from_name;
    private $from_email;
    private $subject;
    private $messages = array();
    private $message_body = '';
    private $errors = array();

    public function __construct()
    {
        $this->ajax = false;
    }

    public function set_ajax($ajax)
    {
        $this->ajax = $ajax;
    }

    public function set_to($to)
    {
        $this->to = $to;
    }

    public function set_from_name($from_name)
    {
        $this->from_name = $from_name;
    }

    public function set_from_email($from_email)
    {
        $this->from_email = $from_email;
    }

    public function set_subject($subject)
    {
        $this->subject = $subject;
    }

    public function add_message($message, $label = null, $maxlength = null)
    {
        $this->messages[] = array(
            'message' => $message,
            'label' => $label,
            'maxlength' => $maxlength
        );
    }

    public function send()
    {
        if ($this->ajax) {
            // Send the email using AJAX
            $response = array(
                'success' => false,
                'message' => ''
            );

            if ($this->validate()) {
                $this->send_email();
                $response['success'] = true;
                $response['message'] = 'Your message has been sent. Thank you!';
            } else {
                $response['message'] = 'Please correct the following errors:';

                foreach ($this->errors as $error) {
                    $response['message'] .= "<br>$error";
                }
            }

            echo json_encode($response);
        } else {
            // Send the email using the regular method
            if ($this->validate()) {
                $this->send_email();
                header("Location: contact.php?success=true");
            } else {
                include('contact.php');
            }
        }
    }

    private function validate()
    {
        $errors = array();

        if (empty($this->to)) {
            $errors[] = 'Please enter a recipient email address.';
        }

        if (empty($this->from_name)) {
            $errors[] = 'Please enter your name.';
        }

        if (empty($this->from_email)) {
            $errors[] = 'Please enter your email address.';
        }

        if (empty($this->subject)) {
            $errors[] = 'Please enter a subject line.';
        }

        foreach ($this->messages as $message) {
            if (empty($message['message'])) {
                $errors[] = 'Please enter a message.';
            }
        }

        $this->errors = $errors;

        return count($errors) == 0;
    }

    private function send_email()
    {
        $headers = array(
            'From: ' . $this->from_name . ' <' . $this->from_email . '>',
            'Subject: ' . $this->subject
        );

        foreach ($this->messages as $message) {
            $this->message_body .= $message['message'] . "\n";
        }

        mail($this->to, $this->subject, $this->message_body, $headers);
    }
}
