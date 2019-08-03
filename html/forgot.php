<?php
/**
 * Basic Template for additional pages.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */
?>

<!-- Header Import -->
<?php include_once('header.php');
$options = [
    'cost' => $config['general']['bcryptcost'],
];
?>
<!-- Page Content -->

<div class="container well well-icarey">
    <div class="row">
        <div class="col col-12">
            <?php if (isset($_GET['key'])) {
                try {
                    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
                    $key = $database->clean($_GET['key']);
                    $email = $database->clean(strtolower($_GET['email']));
                    $result_user = $database->query("SELECT * FROM website_users WHERE `verification`='$key' AND `email` = '$email' LIMIT 1");
                    if ($database->num_rows($result_user) != 1) {
                        throw new Exception("Check your password recovery link.");
                    }

                    echo '<div class="container">
                <form class="forgotform" id="forgotform" action="forgot.php" method="post">
                  <h2>Password Reset</h2>
                  <div class="form-group">
                      <label for="InputPassword">Password</label>
                      <input type="password" name="password" class="form-control" id="InputPassword" placeholder="Password">
                  </div>
                  <div class="form-group">
                      <label for="InputPassword2">Password Again</label>
                      <input type="password" name="password2" class="form-control" id="InputPassword2" placeholder="Password">
                  </div>
                  <input type="hidden" name="email" id="email" value="' . $email . '" />
                  <button type="submit" name="recover" value="recover" class="btn icarey-orange">Submit</button>
              </form>
          </div>';

                } catch (Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                    header("refresh:3;url=index.php");
                }

            } else if (isset($_POST['forgot'])) {
                $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
                $email = $database->clean(strtolower($_POST['email']));
                $result_user = $database->query("SELECT * FROM website_users WHERE `email`='$email' LIMIT 1");
                try {
                    if ($database->num_rows($result_user) != 1) {
                        throw new Exception("Email not registered.");
                    }

                    $user_data = $database->fetch($result_user);


                    $username = $user_data['username'];
                    $key = md5(uniqid(mt_rand(), true));
                    $update_key = $database->query("UPDATE website_users SET verification = '$key' WHERE `email`='$email'");

                    if ($database->affected_rows($update_key) != 1) {
                        throw new Exception("Failed to connect to database and update key");
                    }

                    //Send Email with verification key
                    $userMail = mail::forgotPass($email, $username, $key);
                    if (!$userMail) {
                        throw new Exception("User email failed to send.");
                    }

                    echo '<div class="alert alert-success" role="alert">Check your email for a password reset link.</div>';
                    header("refresh:3;url=index.php");

                } catch (Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                    header("refresh:3;url=index.php");
                }

            } else if (isset($_POST['recover'])) {
                $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
                $email = $database->clean(strtolower($_POST['email']));
                $password = $database->clean($_POST['password']);
                $result_user = $database->query("SELECT * FROM website_users WHERE `email`='$email' LIMIT 1");

                try {
                    if (strlen($_POST['password']) < 8) {
                        throw new Exception('Password must be at least 8 characters!');
                    }

                    if ($_POST['password'] != $_POST['password2']) {
                        throw new Exception('Passwords do not match try again.');
                    }

                    if ($database->num_rows($result_user) != 1) {
                        throw new Exception("User Not Found!");
                    }

                    $password = password_hash($password, CRYPT_BLOWFISH, $options);
                    $key = md5(uniqid(mt_rand(), true));
                    $update_key = $database->query("UPDATE website_users SET verification = '$key', password = '$password' WHERE `email`='$email'");

                    if ($database->affected_rows() == 0) {
                        throw new Exception("Failed to connect to database and update key");
                    }

                    echo '<div class="alert-success alert" role="alert">Password Updated</div>';
                    header("refresh:2;url=index.php");


                } catch (Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                    header("refresh:2;url=index.php");
                }

            } else {

                echo '<h4>Password Recovery</h4>
             <div class="container">
              <form class="forgotform" id="forgotform" action="forgot.php" method="post">
                  <h2>Password Reset</h2>
                  <div class="form-group">
                      <label for="InputEmail">Account Email</label>
                      <input type="email" name="email" class="form-control" id="InputEmail" placeholder="Email">
                  </div>
                  <button type="submit" name="forgot" value="forgot" class="btn icarey-orange">Submit</button>
              </form>
          </div>';
            } ?>

        </div>
    </div>
</div>

<!-- Footer Import -->
<?php include_once('footer.php'); ?>
