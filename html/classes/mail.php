<?php
/**
 * Basic PHP Class - Includes basic functions.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2018 iCarey Computer Services.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'includes/phpmailer/Exception.php'; //Classes called by file from the root directory, so the path needs to be set as if calling from root directory for all includes.
require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';

$config = parse_ini_file('includes/config.ini.php', 1, true);

class mail
{
    public static function userActivation($email, $username, $discord, $key)
    {
        global $config;
        $owner = $config['general']['owner'];
        $siteurl = $config['general']['siteurl'];
        $whitelist_channel = $config['general']['whitelist_channel'];
        $discord_server = $config['general']['discord'];
        $discord_bot = $config['addons']['discord_bot'];

        $message = "Welcome $username, <br>";
        $message .= "Thank you for registering.<br>";
        $message .= "Once your account is activated we can process your whitelist application.<br>";
        $message .= "<h4>Please click the following link to activate your account.</h4><br>";
        $message .= "<a href=\"$siteurl/activate.php?key=$key\">Activation Link</a><br>";
        $message .= "<br>";
        if ($discord_bot = 'true') {
            $message .= "<h4>This key is also used to verify your account with our discord bot. Copy and paste the following into #$whitelist_channel.</h4><br>";
            $message .= "!verify @$discord $key<br>";
            $message .= "https://discord.gg/$discord_server<br>";
            $message .= "Thank You, <br><strong>$owner</strong>";
        } else {
            $message .= "https://discord.gg/$discord_server<br>";
            $message .= "Thank You, <br><strong>$owner</strong>";
        }


        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $config['email']['host']; // SMTP server
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['activation']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['activation']['subject'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function userAppeal($email, $username, $comment)
    {
        global $config;
        $owner = $config['general']['owner'];
        $siteurl = $config['general']['siteurl'];

        //Send Email to Admins
        $message = "Admins, <br>";
        $message .= "A banned player has requested an appeal!<br>";
        $message .= "<h4>Please visit $siteurl to review application.</h4><br>";
        $message .= "There is also a copy of the user's appeal attached at the end of this message.<br>";
        $message .= "<br>";
        $message .= "Thank You, <br> $owner<br>";
        $message .= "<br>";
        $message .= "<Strong>Appeal Information:</strong>";
        $message .= "<hr>";
        $message .= "Minecraft User - $username<br>";
        $message .= "Email - $email<br>";
        $message .= "Appeal - $comment<hr>";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $config['email']['host']; // SMTP server
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['appeal']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['appeal']['subject'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($config['email']['primaryemail'], $config['email']['primaryname']);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function forgotPass($email, $username, $key)
    {
        global $config;
        $owner = $config['general']['owner'];
        $siteurl = $config['general']['siteurl'];
        $discord_server = $config['general']['discord'];


        $message = "Hi $username, <br>";
        $message .= "We see you have lost your password.<br>";
        $message .= "<h4>Please click the following link to reset your password.</h4><br>";
        $message .= "<a href=\"$siteurl/forgot.php?key=$key&email=$email\">Password Recovery Link</a><br>";
        $message .= "<br>";
        $message .= "<h4>For further help join our discord.</h4><br>";
        $message .= "https://discord.gg/$discord_server<br>";
        $message .= "Thank You, <br><strong>$owner</strong>";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $config['email']['host']; // SMTP server
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['forgot']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['forgot']['subject'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function adminReg($email, $username, $age, $comment, $discord)
    {
        global $config;
        $owner = $config['general']['owner'];
        $siteurl = $config['general']['siteurl'];

        //Send Ban Email
        $message = "Admins, <br>";
        $message .= "A new user has registered on the site!<br>";
        $message .= "<h4>Please visit $siteurl and login to review application.</h4><br>";
        $message .= "There is also a copy of the user's application attached at the end of this message.<br>";
        $message .= "<br>";
        $message .= "Thank You, <br> $owner<br>";
        $message .= "<br>";
        $message .= "<Strong> User Information:</strong>";
        $message .= "<hr>";
        $message .= "Minecraft User - $username<br>";
        $message .= "Email - $email<br>";
        $message .= "Age - $age<br>";
        $message .= "Discord User - $discord<br>";
        $message .= "Description - $comment<hr>";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $config['email']['host']; // SMTP server
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['registration']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['registration']['subject'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($config['email']['primaryemail'], $config['email']['primaryname']);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function sendApprove($email, $username, $approver)
    {
        global $config;
        $siteurl = $config['general']['siteurl'];
        $discord = $config['general']['discord'];
        $connectaddress = $config['minecraft']['connectaddress'];

        //Send Whitelist Approval Email
        $message = "You have been <strong>Approved</strong> and added to the our whitelist.<br>";
        $message .= "Welcome to the community!<br><br><strong>Connection Information:</strong>";
        $message .= "<hr>";
        $message .= "<strong>Connect To: </strong>$connectaddress <br>";
        $message .= "<strong>Discord: </strong>https://discord.gg/$discord<br>";
        $message .= "<br>";
        $message .= "Thank You for your Registration,<br>";
        $message .= "$approver<br>";
        $message .= "$siteurl";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['whitelist']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['whitelist']['approvalsub'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function sendDeny($email, $username, $denier, $reason)
    {
        global $config;
        $siteurl = $config['general']['siteurl'];
        $discord = $config['general']['discord'];
        $owner = $config['general']['owner'];

        //Send Whitelist Denial Email
        $message = "Sorry, but you have been <strong>Denied</strong> for our whitelist.<br>";
        $message .= "We have sent you the reason below.";
        $message .= "<hr>";
        $message .= "$reason";
        $message .= "<hr>";
        $message .= "If you feel this is not a fair judgement and would like to appeal.";
        $message .= "You may join our discord channel and discuss with us. <br>";
        $message .= "<strong>Discord: </strong>https://discord.gg/$discord<br>";
        $message .= "Thank You for your Registration,<br>";
        $message .= "$denier<br>";
        $message .= "$siteurl";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['whitelist']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['whitelist']['denysub'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function sendBan($email, $username, $banner, $reason)
    {
        global $config;
        $siteurl = $config['general']['siteurl'];
        $discord = $config['general']['discord'];

        //Send Ban Email
        $message = "Sorry, but you have been <strong>Banned</strong> from the server.<br>";
        $message .= "We have sent you the reason below.";
        $message .= "<hr>";
        $message .= "$reason";
        $message .= "<hr>";
        $message .= "If you feel this is not a fair judgement and would like to appeal.";
        $message .= "You may join our discord channel and discuss with us or visit out site and submit a ban appeal.<br>";
        $message .= "<strong>Website: </strong>$siteurl<br>";
        $message .= "<strong>Discord: </strong>https://discord.gg/$discord<br>";
        $message .= "Thank You for your Understanding,<br>";
        $message .= "$banner<br>";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['whitelist']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['ban']['bansub'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }

    public static function sendunBan($email, $username, $pardoner)
    {
        global $config;
        $siteurl = $config['general']['siteurl'];
        $discord = $config['general']['discord'];

        //Send Ban Email
        $message = "You have been <strong>unbanned</strong> from the server.<br>";
        $message .= "<hr>";
        $message .= "If you have futher questions. You may join our discord channel and discuss with us.<br>";
        $message .= "<strong>Website: </strong>$siteurl<br>";
        $message .= "<strong>Discord: </strong>https://discord.gg/$discord<br>";
        $message .= "Thank You for your Understanding,<br>";
        $message .= "$pardoner<br>";

        $mail = new PHPMailer(true);
        $body = $message;
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = 0;                 // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Host = $config['email']['host']; // sets the SMTP server
        $mail->Port = $config['email']['port'];                    // set the SMTP port for the server
        $mail->Username = $config['email']['username']; // SMTP account username
        $mail->Password = $config['email']['password'];        // SMTP account password
        $mail->SetFrom($config['email']['primaryemail'], $config['whitelist']['from']); //FROM
        $mail->AddReplyTo($config['email']['reply'], $config['email']['replyname']); //REPLY TO
        $mail->Subject = $config['ban']['unbansub'];
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, Alternate Body for those who can't view HTML mail.
        $mail->MsgHTML($body);
        $mail->AddAddress($email, $username);
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }

    }
}
