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
    header("url=index.php");


} else if ($_SESSION['icarey']['role'] != $config['groups']['moderator'] && $_SESSION['icarey']['role'] != $config['groups']['administrator']) {
    header("url=index.php");
} else {

    ?>
    <div class="container well well-icarey">
    <div class="row">
    <div class="col col-12">
    <?php
    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $resultapproved = $database->query("SELECT * FROM website_users WHERE approved = '1' AND banned = '0' ORDER BY username");
    echo '<h1 class="wltitle icarey-orange-text">Whitelist Management</h1>';
    echo '<a href="approved.php"><button class="btn btn-success btn-sm">Approved Users</button></a>';
    echo '<a href="declined.php"><button class="btn btn-outline-danger btn-sm">Declined Users</button></a>';
    echo '<a href="whitelist.php"><button class="btn btn-outline-warning btn-sm">Waiting Users</button></a>';

    if ($database->num_rows($resultapproved) == 0) {
        echo '<div class="alert alert-success" role="alert">No Approved Users.</div>';
    } else if ($database->num_rows($resultapproved) > 0) {
        while ($row = $database->fetch($resultapproved)) {
            $id = $row['id'];
            $email = $row['email'];
            $age = $row['age'];
            $username = $row['username'];
            $uuid = $row['uuid'];
            $approved = $row['approved'];
            $uuidshort = MojangAPI::minifyUuid($uuid);

            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo '<td><img height="30%" width="30%" src="img/skins/head/' . $uuid . '.png"></td>';
            echo '<td><a href="lookup.php?user=' . $row['username'] . '"</a>' . $row['username'] . '</td>';
            echo '</tr>';
            echo '</table>';

        }
    }
} ?>

    </div>
    </div>
    </div>
<?php include_once 'footer.php'; ?>