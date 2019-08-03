<?php
/**
 * Ban Appeal PHP
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

require_once 'header.php'; ?>

    <div class="container well well-icarey">
        <div class="row">
            <div class="col col-12">
                <?php
                if (isset($_POST['appeal'], $_POST['email'], $_POST['username'], $_POST['comment'])) {
                    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
                    if (isset($_POST['g-recaptcha-response'])) {
                        try {
                            $captcha = $_POST['g-recaptcha-response'];

                            if (!$captcha) {
                                throw new Exception('Please Retry The Captcha');
                            }
                            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $config['captcha']['secretkey'] . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
                            $captcharesult = json_decode($response);

                            if (!$captcharesult->success) {
                                throw new Exception('You are a SPAMMER Go Away!');
                            }

                            $email = $database->clean(strtolower($_POST['email']));
                            $username = $database->clean(strtolower($_POST['username']));
                            $comment = $database->clean($_POST['comment']);

                            $resultuser = $database->query("SELECT * FROM website_users WHERE `email`='$email' AND `username`='$username' LIMIT 1");

                            if ($database->num_rows($resultuser) == 0) {
                                throw new Exception("User is not registered or invalid email/username.");
                            }

                            $updateappeal = $database->query("UPDATE website_users SET appeal = '$comment' WHERE `email` = '$email' LIMIT 1");

                            if ($database->affected_rows() == 0) {
                                throw new Exception("Database Error.");
                            }

                            $user = $database->fetch($resultuser);
                            $owner = $config['general']['owner'];
                            $siteurl = $config['general']['siteurl'];

                            $userAppeal = mail::userAppeal($email, $username, $comment);

                            if (!$userAppeal) {
                                throw new Exception("Appeal email failed to send to admins.");
                            }

                            echo '<div class="alert alert-success" role="alert">Your appeal request will be reviewed, and we will get back to you soon!</div>';
                            header("refresh:1;url=index.php");

                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                            header("refresh:3;url=index.php");
                        }

                    } else {
                        echo '<div class="alert alert-danger" role="alert">Empty Request</div>';
                        header("refresh:1;url=index.php");
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Empty Request</div>';
                    header("refresh:1;url=index.php");
                } ?>
            </div>
        </div>
    </div>
<?php require_once 'footer.php'; ?>