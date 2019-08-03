<?php
/**
 * Bans Administration Page
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
    header('Location:index.php');

} else if (isset($_POST['unban'])) {
    $unban = adminFunctions::unBan($_POST['id'], $_POST['username'], $_POST['email'], $_SESSION['icarey']['username']);
    if ($unban == 0) {
        echo '<div class="alert alert-danger" role="alert">Unban Failed</div>';
        header("refresh:1;url=bans.php");
    } else {
        echo '<div class="alert alert-success" role="alert">Unban Success</div>';
        header("refresh:1;url=bans.php");
    }

} else if (isset($_POST['ban'])) {
    $banner = $_SESSION['icarey']['username'];
    $ban = adminFunctions::banUser($_POST['id'], $_POST['username'], $_POST['email'], $banner, $_POST['reason']);
    if ($ban == 0) {
        echo '<div class="alert alert-danger" role="alert">Ban Failed</div>';
        header("refresh:1;url=bans.php");
    } else {
        echo '<div class="alert alert-success" role="alert">Ban Success</div>';
        header("refresh:1;url=bans.php");
    }

} else {

    ?>
    <div class="container well well-icarey">
    <div class="row">
    <div class="col col-12">
    <?php
    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $resultbanned = $database->query("SELECT * FROM website_users WHERE banned = '1'");
    echo '<h1 class="wltitle icarey-orange-text">Ban Management</h1>';;
    echo '<a href="bans.php" class="btn btn-danger icarey-btn-space btn-sm">Active Bans</a>';
    if ($database->num_rows($resultbanned) == 0) {
        echo '<div class="alert alert-success" role="alert">No Banned Users.</div>';
    } else if ($database->num_rows($resultbanned) > 0) {
        while ($row = $database->fetch($resultbanned)) {
            $id = $row['id'];
            $uuid = $row['uuid'];
            $username = $row['username'];
            $email = $row['email'];
            $age = $row['age'];
            $banned = $row['banned'];
            $banned_by = $row['banned_by'];
            $ban_reason = $row['ban_reason'];
            $ban_appeal = $row['appeal'];
            if ($banned == 0) {
                $banresult = 'false';
            } else if ($banned == 1) {
                $banresult = 'true';
            } else {
                $banresult = 'unknown';
            }
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo '<th>Username</th>';
            echo '<th>Email</th>';
            echo '<th>Ban Status</th>';
            echo '<th>Banned By</th>';
            echo '</tr>';
            echo "<tr>";
            echo "<td>" . $username . "</td>";
            echo "<td>" . $email . "</td>";
            echo "<td>" . $banresult . "</td>";
            echo "<td>" . $banned_by . "</td>";
            echo "</tr>";
            echo '</table>';
            echo "<h4>Ban Reason</h4>";
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo "<td>" . $ban_reason . "</td>";
            echo '</tr>';
            echo '</table>';
            echo "<h4>Appeal</h4>";
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo "<td>" . $ban_appeal . "</td>";
            echo '</tr>';
            echo '</table>';
            echo '<form class="ubanform" id="ubanform" action="bans.php" method="post">';
            echo '<input type="hidden" name="id" id="hiddenField1" value="' . $id . '" />';
            echo '<input type="hidden" name="username" id="hiddenField3" value="' . $username . '">';
            echo '<input type="hidden" name="email" id="hiddenField2" value="' . $email . '" />';
            echo '<button type="submit" name="unban" value="unban" class="btn btn-outline-danger">Unban</button>';
            echo '</form>';
        }
    }
}
?>

    </div>
    </div>
    </div>
<?php include_once 'footer.php'; ?>