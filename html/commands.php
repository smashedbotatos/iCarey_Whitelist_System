<?php
/**
 * Commands Page
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

include_once 'classes/adminFunctions.php';
include_once 'header.php';

if (!isset($_SESSION['icarey']['email'])) {
    header("Location:index.php");

} else if ($_SESSION['icarey']['role'] != $config['groups']['administrator']) {
    header("url=index.php");
} else if (isset($_POST['sendCmd'])) {
    echo '<div class="container well well-icarey">';
    echo '<div class="row">';
    echo '<div class="col col-12">';
    $command = $_POST{'command'};
    $send = adminFunctions::sendCmd($command);
    if (!$send) {
        echo '<div class="alert alert-success" role="alert">Failed to send command.</div>';
    } else {
        echo '<div class="alert alert-success" role="alert">Command Sent</div>';
        header("refresh:3;url=commands.php");
    }
} else { ?>
    <div class="container well well-icarey">
    <div class="row">
    <div class="col col-12">
    <?php
    echo '<h1 class="wltitle icarey-orange-text">Command Center</h1>';
    echo '<p>Enter a console command with out the /</p>';
    echo '<form class="comform" id="comform" action="commands.php" method="post">';
    echo 'Command:<br>';
    echo '<input type="text" name="command"><br>';
    echo '<button type="submit" name="sendCmd" value="sendCmd" class="btn icarey-orange">Send Command</button>';
    echo '</form>';

} ?>

    </div>
    </div>
    </div>

<?php
include_once 'footer.php';
?>