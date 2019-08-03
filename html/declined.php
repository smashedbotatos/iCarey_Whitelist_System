<?php
/**
 * Approved Users List Page
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */


include_once 'classes/adminFunctions.php';
include_once 'header.php';

if (!isset($_SESSION['icarey']['email'])) {
    header("Location:index.php");;


} else if ($_SESSION['icarey']['role'] != $config['groups']['moderator'] && $_SESSION['icarey']['role'] != $config['groups']['administrator']) {
    header("Location:index.php");;
} else {

    ?>
    <div class="container well well-icarey">
    <div class="row">
    <div class="col col-12">
    <?php
    echo '<h1 class="wltitle icarey-orange-text">Whitelist Management</h1>';
    echo '<a href="approved.php"><button class="btn btn-outline-success btn-sm">Approved Users</button></a>';
    echo '<a href="declined.php"><button class="btn btn-danger btn-sm">Declined Users</button></a>';
    echo '<a href="whitelist.php"><button class="btn btn-outline-warning btn-sm">Waiting Users</button></a>';
    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $resultdeclined = $database->query("SELECT * FROM website_users WHERE denied = '1' ");

    if ($database->num_rows($resultdeclined) == 0) {
        echo '<div class="alert alert-danger" role="alert">No Declined Users.</div>';
    } else if ($database->num_rows($resultdeclined) > 0) {
        while ($row = $database->fetch($resultdeclined)) {
            if ($row > 0) {
                echo '<div class="well well-icarey">';
            }
            $id = $row['id'];
            $email = $row['email'];
            $username = $row['username'];
            $uuid = $row['uuid'];
            $uuidshort = MojangAPI::minifyUuid($uuid);

            echo '<img height="50" width="50" src="img/skins/head/' . $uuid . '.png"> &nbsp ' . $username . '';
            echo '<br>';
            echo '<table class="table table-responsive table-dark">';
            echo '<thead> <tr> </th><th scope="col">UUID</th><th scope="col">Email</th><th scope="col">Whitelist Approved</th><th scope="col">Whitelist Declined</th><th scope="col">Declined By</th></tr></thead>';
            echo '<tbody> <tr> <td>' . $row['uuid'] . '</td><td>' . $row['email'] . '</td><td>' . $row['approved'] . '</td><td>' . $row['denied'] . '</td><td>' . $row['denied_by'] . '</td></tr> </tbody>';
            echo '</table>';
            echo 'Reason Declined';
            echo '<div class="well well-text">' . $row['denial_reason'] . '</div><br />';
            echo '<a href="whitelist.php?approve=1&id=' . $id . '&email=' . $email . '&username=' . $username . '"><button class="btn btn-outline-success icarey-btn-space btn-sm">Approve</button></a>';
            echo '<a href="https://www.mcbans.com/player/' . $uuidshort . '" target="_blank"><button class="btn btn-outline-warning icarey-btn-space btn-sm">Check Bans</button></a>';
            echo '</div>';
        }
    }
} ?>

    </div>
    </div>
    </div>
<?php include_once 'footer.php'; ?>