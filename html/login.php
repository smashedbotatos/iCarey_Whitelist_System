<?php
/**
 * Login Function for the main site
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2010 iCarey Computer Services.
 *
 */

include_once('header.php'); ?>
<div class="container well well-icarey">
    <div class="row">
        <div class="col col-12">
            <?php
            $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);

            if (!isset($_SESSION['icarey']['email'])) {
                $email = $database->clean(strtolower($_POST['email']));
                $pass = $database->clean($_POST['password']);

                if (isset($_POST['g-recaptcha-response'])) {

                    $result = $database->query("SELECT * FROM website_users WHERE email='$email' LIMIT 1");

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

                        if ($database->num_rows($result) == 0) {
                            throw new Exception('Invalid Email or Password!');
                        }

                        $user = $database->fetch($result);
                        $passwordcheck = password_verify($pass, $user['password']);

                        if ($passwordcheck === false) {
                            throw new Exception('Invalid Email or Password!');
                        }

                        $uuid = $user['uuid'];

                        if (!$user['verified'] === '1') {
                            throw new Exception('Please verify your email address.');
                        }

                        if ($config['addons']['luckperms'] == 'true') {
                            $groupres = $database->query("SELECT primary_group FROM luckperms_players WHERE uuid = '$uuid'");
                            $group = $database->fetch($groupres);
                            try {

                                if ($group['primary_group'] != $user['role']) {
                                    Basic::updateGroup($uuid, $group['primary_group']);
                                    throw new Exception('Updating group, log in again.');
                                }

                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                                header("refresh:3;url=index.php");
                            }
                        }

                        $_SESSION['icarey']['id'] = $user['id'];
                        $_SESSION['icarey']['email'] = $user['email'];
                        $_SESSION['icarey']['username'] = $user['username'];
                        $_SESSION['icarey']['uuid'] = $user['uuid'];
                        $_SESSION['icarey']['role'] = $user['role'];
                        $_SESSION['icarey']['subscriber'] = $user['subscriber'];
                        $_SESSION['icarey']['approved'] = $user['approved'];
                        $_SESSION['icarey']['verification'] = $user['verification'];
                        $_SESSION['icarey']['discorduser'] = $user['discord_username'];

                        header('Location:index.php');

                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                        header("refresh:3;url=index.php");
                    }

                } else {
                    echo '<div class="alert alert-danger" role="alert"> Captcha Not Set</div>';
                    header("refresh:3;url=index.php");
                }

            } else {
                header('Location:index.php');
            }
            ?>
        </div>
    </div>
</div>
<?php include_once('footer.php'); ?>
