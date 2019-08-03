<?php
/**
 * Server Utilities for the admin panel.
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */
?>

<!-- Header Import -->
<?php include_once('header.php');
include_once 'classes/adminFunctions.php';

if (!isset($_SESSION['icarey']['email'])) {
    header("Location:index.php");


} else if ($_SESSION['icarey']['role'] != $config['groups']['administrator']) {
    header("url=index.php");

} else if (isset($_POST['changegroup'])) {
    $username = $_POST['username'];
    $change = Basic::updateGroup($_POST['uuid'], $_POST['group']);
    if ($change == 1) {
        $rchange = adminFunctions::changeGroup($username, $_POST['group']);
        if ($rchange == 1) {
            echo '<div class="alert alert-success" role="alert">Changed Users Group</div>';
            header("refresh:1;url=lookup.php?user=$username");
        } elseif ($rchange == 0){
            echo '<div class="alert alert-danger" role="alert">Group Unchanged</div>';
            header("refresh:2;url=lookup.php?user=$username");
        }
    } else if ($change == 0) {
        echo '<div class="alert alert-danger" role="alert">Group Unchanged</div>';
        header("refresh:2;url=lookup.php?user=$username");
    }
} else { ?>
    <!-- Page Content -->
    <div class="container well well-icarey">
        <div class="row">
            <div class="col col-12">
                <h1 class="wltitle icarey-orange-text">Server Utilities</h1>
                <br>
                <h4>Work in Progress, Do not Delete</h4>
            </div>
        </div>
    </div>

    <!-- Footer Import -->
<?php }
include_once('footer.php'); ?>
