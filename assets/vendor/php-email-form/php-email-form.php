<?php

class PHP_Email_Form
{
    private $to;
    private $from_name;
    private $from_email;
    private $subject;
    private $message;
    private $headers;
    private $ajax = false;

    public function __construct()
    {
        $this->headers = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    }

    public function send()
    {
        $this->message = wordwrap($this->message, 70);

        if ($this->ajax) {
            $response = array('status' => 'error', 'message' => '');

            if ($this->validate()) {
                $mail = mail($this->to, $this->subject, $this->message, $this->headers);
                if ($mail) {
                    $response['status'] = 'success';
                    $response['message'] = 'Message sent successfully';
                } else {
                    $response['message'] = 'Failed to send message';
                }
            } else {
                $response['message'] = 'Please fill in all required fields';
            }

            return json_encode($response);
        } else {
            if ($this->validate()) {
                return mail($this->to, $this->subject, $this->message, $this->headers);
            } else {
                return false;
            }
        }
    }

    public function add_message($content, $label, $maxlength = 0)
    {
        $content = htmlspecialchars(stripslashes(trim($content)));
        $label = ucfirst($label);

        if ($maxlength > 0 && strlen($content) > $maxlength) {
            $content = substr($content, 0, $maxlength);
        }

        $this->message .= "<p><strong>$label:</strong> $content</p>";
    }

    private function validate()
    {
        return !empty($this->to) && !empty($this->from_name) && !empty($this->from_email) && !empty($this->subject) && !empty($this->message);
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setFromName($name)
    {
        $this->from_name = $name;
    }

    public function setFromEmail($email)
    {
        $this->from_email = $email;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function enableAjax()
    {
        $this->ajax = true;
    }
}

// Include the PHP_Email_Form class
include('php-email-form.php');

$receiving_email_address = 'asutoshbehera666@gmail.com';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact = new PHP_Email_Form();
    $contact->setTo($receiving_email_address);
    $contact->setFromName($_POST['name']);
    $contact->setFromEmail($_POST['email']);
    $contact->setSubject($_POST['subject']);
    $contact->add_message($_POST['name'], 'From');
    $contact->add_message($_POST['email'], 'Email');
    $contact->add_message($_POST['message'], 'Message', 10);

    if ($contact->send()) {
        echo json_encode(array('status' => 'success', 'message' => 'Your message has been sent. Thank you!'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to send message. Please try again.'));
    }
    exit;
}
