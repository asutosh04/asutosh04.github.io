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
