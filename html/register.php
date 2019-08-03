<?php
/**
 * Registration Function
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

require_once 'header.php';
$options = [
    'cost' => $config['general']['bcryptcost'],
]; ?>

    <div class="container well well-icarey">
        <div class="row">
            <div class="col col-12">

                <?php
                if (!empty($_POST['register'])) {
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

                            if ($_POST['password'] != $_POST['password2']) {
                                throw new Exception('Passwords do not match try again.');
                            }

                            $username = $database->clean(strtolower($_POST['username']));
                            $email = $database->clean(strtolower($_POST['email']));
                            $password = $database->clean($_POST['password']);
                            $age = $database->clean($_POST['age']);
                            $discord = $database->clean($_POST['discord']);
                            $comment = $database->clean($_POST['comment']);
                            $uuidresult = MojangAPI::getUuid($username);
                            $uuidfinal = MojangAPI::formatUuid($uuidresult);
                            $role = $config['groups']['default_group'];


                            if (empty($uuidfinal)) {
                                throw new Exception("UUID Conversion failed.");
                            }

                            $uuid = $uuidfinal;
                            $headresult = Basic::getPlayerHead($uuid);

                            if ($headresult == 0) {
                                throw new Exception("Failed to fetch head skin.");
                            }

                            $bodyresult = Basic::getPlayerBody($uuid);

                            if ($bodyresult == 0) {
                                throw new Exception("Failed to fetch head skin.");
                            }

                            $resultuser = $database->query("SELECT * FROM website_users WHERE `username`='$username' LIMIT 1");
                            $resultemail = $database->query("SELECT * FROM website_users WHERE `email`='$email' LIMIT 1");
                            $resultuuid = $database->query("SELECT * FROM website_users WHERE `uuid`='$uuid' LIMIT 1");


                            if ($database->num_rows($resultuser) >= 1) {
                                throw new Exception("Minecraft username already has an account.");
                            }
                            if ($database->num_rows($resultemail) >= 1) {
                                throw new Exception("Email address already already in use.");
                            }
                            if ($database->num_rows($resultuuid) >= 1) {
                                throw new Exception("UUID already attached to account.");
                            }
                            if (strlen($password) < 8) {
                                throw new Exception('Password must be at least 8 characters!');
                            }

                            //Submit Info To Database
                            $password = password_hash($password, CRYPT_BLOWFISH, $options);
                            $key = md5(uniqid(mt_rand(), true));
                            $database->query("INSERT INTO website_users (email, password, username, uuid, age, application, appeal, role, role_id, subscriber, verification, verified, approved, approved_by, denied, denied_by, denial_reason, banned, banned_by, ban_reason, discord_username, discord_id, hackcheck) VALUES ('$email', '$password', '$username', '$uuid', '$age', '$comment', '0', '$role','0', '1', '$key', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$discord','0','0')");
                            if ($database->affected_rows() == 0) {
                                throw new Exception('Database Error!');
                            }

                            //Send Email to Admins
                            $adminMail = mail::adminReg($email, $username, $age, $comment, $discord);
                            if (!$adminMail) {
                                throw new Exception("Admin email failed to send.");
                            }

                            //Send Email Activation Email To Users
                            $userActivation = mail::userActivation($email, $username, $discord, $key);
                            if (!$userActivation) {
                                throw new Exception("Activation email failed to send.");
                            }

                            echo '<div class="alert alert-success" role="alert">Thank your for registering, check your email for an activation link.</div>';
                            header("refresh:3;url=index.php");

                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                            header("refresh:3;url=index.php");
                        }

                    } else {
                        echo '<div class="alert alert-danger" role="alert"> Captcha Not Set</div>';
                        header("refresh:3;url=index.php");
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert"> Empty Request</div>';
                    header("refresh:3;url=index.php");
                }
                ?>
            </div>
        </div>
    </div>
<?php include_once('footer.php'); ?>