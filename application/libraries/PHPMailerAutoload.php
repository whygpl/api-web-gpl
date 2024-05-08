<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PHPMailerAutoload {
 
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        require_once('PHPMailer/src/PHPMailer.php');
        require_once('PHPMailer/src/SMTP.php');
        require_once('PHPMailer/src/Exception.php');
        require_once('PHPMailer/src/OAuth.php');
        require_once('PHPMailer/src/POP3.php');

        $objMail = new PHPMailer\PHPMailer\PHPMailer();
        return $objMail;
    }
}