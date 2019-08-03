<?php
/**
 * Whitelist Administration Page
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

include_once 'classes/adminFunctions.php';
include_once 'header.php';

if (!isset($_SESSION['icarey']['email'])) {
    header('Location:index.php');


} else if ($_SESSION['icarey']['role'] != $config['groups']['moderator'] && $_SESSION['icarey']['role'] != $config['groups']['administrator']) {
    header("Location:index.php");

} else if (isset($_GET['approve'])) {

    $id = $_GET['id'];
    $username = $_GET['username'];
    $email = $_GET['email'];
    $approver = $_SESSION['icarey']['username'];

    $approve = adminFunctions::whitelistApprove($id, $username, $email, $approver);
    if ($approve) {
        echo '<div class="container well well-icarey">';
        echo '<div class="row">';
        echo '<div class="col col-12">';
        echo '<div class="alert alert-success" role="alert">User ' . $username . ' added to whitelist.</div>';
        include_once 'footer.php';
        header("refresh:3;url=whitelist.php");
    } else {
        echo '<div class="container well well-icarey">';
        echo '<div class="row">';
        echo '<div class="col col-12">';
        echo '<div class="alert alert-danger" role="alert">Failed to add User ' . $username . ' to whitelist.</div>';
    }

} else if (isset($_POST['decline'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $denier = $_SESSION['icarey']['username'];
    $reason = $_POST['reason'];

    $deny = adminFunctions::whitelistDeny($id, $username, $email, $denier, $reason);
    if ($deny) {
        echo '<div class="container well well-icarey">';
        echo '<div class="row">';
        echo '<div class="col col-12">';
        echo '<h1 class="wltitle icarey-orange-text">Whitelist Management</h1>';
        echo '<a href="approved.php"><button class="btn btn-outline-success btn-sm">Approved Users</button></a>';
        echo '<a href="declined.php"><button class="btn btn-outline-danger btn-sm">Declined Users</button></a>';
        echo '<a href="whitelist.php"><button class="btn btn-warning btn-sm">Waiting Users</button></a>';
        echo '<div class="alert alert-success" role="alert">User ' . $username . ' removed from whitelist and denied.</div>';
        include_once 'footer.php';
        header("refresh:60;url=whitelist.php");
    } else {
        echo '<div class="container well well-icarey">';
        echo '<div class="row">';
        echo '<div class="col col-12">';
        echo '<h1 class="wltitle icarey-orange-text">Whitelist Management</h1>';
        echo '<br>';
        echo '<a href="approved.php"><button class="btn btn-outline-success btn-sm">Approved Users</button></a>';
        echo '<a href="declined.php"><button class="btn btn-outline-danger btn-sm">Declined Users</button></a>';
        echo '<a href="whitelist.php"><button class="btn btn-warning btn-sm">Waiting Users</button></a>';
        echo '<div class="alert alert-danger" role="alert">Failed to remove User ' . $username . ' to whitelist.</div>';
    }

} else {
    echo '<div class="container well well-icarey">';
    echo '<div class="row">';
    echo '<div class="col col-12">';
    echo '<h1 class="wltitle icarey-orange-text">Whitelist Management</h1>';
    echo '<a href="approved.php"><button class="btn btn-outline-success btn-sm">Approved Users</button></a>';
    echo '<a href="declined.php"><button class="btn btn-outline-danger btn-sm">Declined Users</button></a>';
    echo '<a  href="whitelist.php"><button class="btn btn-warning btn-sm">Waiting Users</button></a>';
    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $resultwaiting = $database->query("SELECT * FROM website_users WHERE approved = '0' AND denied = '0' AND verified = '1'");

    if ($database->num_rows($resultwaiting) == 0) {
        echo '<div class="alert alert-warning" role="alert">No Users waiting for approval!</div>';
        header("refresh:60;url=whitelist.php");
    } else if ($database->num_rows($resultwaiting) > 0) {
        while ($row = $database->fetch($resultwaiting)) {
            if ($row > 0) {
                echo '<div class="well well-icarey">';
            }

            $id = $row['id'];
            $email = $row['email'];
            $age = $row['age'];
            $username = $row['username'];
            $uuid = $row['uuid'];
            $verified = $row['verified'];
            $application = $row['application'];
            $approved = $row['approved'];
            $uuidshort = MojangAPI::minifyUuid($uuid);

            echo '<img height="50" width="50" src="img/skins/head/' . $uuid . '.png"> &nbsp ' . $username . '';
            echo '<br>';
            echo '<table class="table table-responsive table-dark ">';
            echo '<thead> <tr> </th><th scope="col">UUID</th><th scope="col">Age</th><th scope="col">Email</th></tr></thead>';
            echo '<tbody> <tr> <td>' . $row['uuid'] . '</td><td>' . $row['age'] . '</td><td>' . $row['email'] . '</td></tr> </tbody>';
            echo '</table>';
            echo 'Application';
            echo '<div class="well well-text">' . $row['application'] . '</div><br />';
            echo '<a href="whitelist.php?approve=1&id=' . $id . '&email=' . $email . '&username=' . $username . '"><button class="btn icarey-btn-space btn-outline-success btn-sm">Approve</button></a>';
            echo '<a href="https://www.mcbans.com/player/' . $uuidshort . '" target="_blank"><button class="btn icarey-btn-space btn-outline-warning btn-sm">Check McBans</button></a>';
            echo '<button class="btn btn-outline-danger btn-sm" type="button" onclick="div_show_deny()">Decline</button>';
            echo '</div>';

            echo '<div id="DenyWindow">';
            echo '<div id="popupDeny">';
            echo '<div class="container">';
            echo '<h2>Deny ' . $username . ' </h2>';
            echo '<form class="denyform" id="denyform" action="whitelist.php" method="post">';
            echo '<i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4" onclick ="div_hide_deny()" title="Close" ></i>';
            echo ' <input type="hidden" name="id" id="hiddenField1" value="' . $id . '" />';
            echo ' <input type="hidden" name="username" id="hiddenField3" value="' . $username . '">';
            echo ' <input type="hidden" name="email" id="hiddenField2" value="' . $email . '" />';
            echo '<div class="form-group">';
            echo '<label for="denialreason">Denial Reason</label>';
            echo '<textarea name="reason" class="form-control" id="denialreason" placeholder="Why are you declining this user?" cols="50%" rows="4"></textarea>';
            echo '</div>';
            echo '<button type="submit" name="decline" value="decline" class="btn btn-outline-danger">Decline</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

        }

    }
    ?>

    </div>
    </div>
    </div>


<?php }
include_once 'footer.php'; ?>
