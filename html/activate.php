<?php
/**
 * Account Activation Page.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */


include_once('header.php');
?>
    <div class="container well well-icarey">
        <div class="row">
            <div class="col col-12">
                <?php
                if (!isset($_GET['key'])) {
                    echo '<div class="alert alert-danger" role="alert">Verification Failed! Please try again.</div>';
                    header("refresh:1;url=index.php");
                } else {
                    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
                    $activationkey = $database->clean($_GET['key']);

                    $resultactivate = $database->query("SELECT * FROM website_users WHERE `verification`='$activationkey' LIMIT 1");

                    if ($database->num_rows($resultactivate) == 1) {
                        $database->query("UPDATE website_users SET verified = '1' WHERE `verification` = '$activationkey' LIMIT 1");
                        if ($database->affected_rows() > 0) {
                            echo '<div class="alert alert-success" role="alert">Verification Success! You can now login.</div>';
                            header("refresh:1;url=index.php");
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Verification Failed! Please try again.</div>';
                            header("refresh:1;url=index.php");
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Verification Failed! Please try again.</div>';
                        header("refresh:1;url=index.php");
                    }
                } ?>
            </div>
        </div>
    </div>
<?php include_once('footer.php'); ?>