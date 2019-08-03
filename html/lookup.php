<?php
/**
 * User Lookup Page for the main site
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

include_once('header.php');
if (isset($_GET['user'])) {
    //Show info for user sent by GET
    echo '<div class="container well well-icarey">';
    echo '<div class="row">';
    echo '<div class="col col-9">';
    echo '<h4 class="wltitle icarey-orange-text">' . $_GET['user'] . '</h4>';

    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $user = $database->clean($_GET['user']);
    $resultuser = $database->query("SELECT * FROM website_users WHERE `username` = '$user'");
    if ($database->num_rows($resultuser) == 0) {
        echo '<div class="alert alert-success" role="alert">No User By That Name.</div>';
    } else if ($database->num_rows($resultuser) > 0) {
        while ($row = $database->fetch($resultuser)) {
            $id = $row['id'];
            $uuid = $row['uuid'];
            $username = $row['username'];
            $email = $row['email'];
            $age = $row['age'];
            $approved = $row['approved'];
            $approvedby = $row['approved_by'];
            $application = $row['application'];
            $denied = $row['denied'];
            $deniedby = $row['denied_by'];
            $deniedreason = $row['denial_reason'];
            $banned = $row['banned'];
            if ($banned == 0) {
                $banresult = 'false';
            } else if ($banned == 1) {
                $banresult = 'true';
            } else {
                $banresult = 'unknown';
            }
            if ($approved == 0) {
                $appresult = 'false';
            } else if ($approved == 1) {
                $appresult = 'true';
            } else {
                $appresult = 'unknown';
            }
            $stats = Basic::getPlayerstats($uuid);
            if (!file_exists("img/skins/body/$uuid.png")) {
                $body = Basic::getPlayerBody($uuid);
            }
            if (!file_exists("img/skins/head/$uuid.png")) {
                $body = Basic::getPlayerHead($uuid);
            }
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            if (isset($_SESSION['icarey']['email'])) {
                if ($_SESSION['icarey']['role'] == $config['groups']['moderator'] || $_SESSION['icarey']['role'] == $config['groups']['administrator']) {
                    echo '<th>Age</th>';
                    echo '<th>Email</th>';
                    echo '<th>Approved</th>';
                    echo '<th>Approved By</th>';
                }
            }
            echo '<th>Role</th>';
            echo '<th>Ban Status</th>';
            echo '</tr>';
            echo "<tr>";
            if (isset($_SESSION['icarey']['email'])) {
                if ($_SESSION['icarey']['role'] == $config['groups']['moderator'] || $_SESSION['icarey']['role'] == $config['groups']['administrator']) {
                    echo "<td>" . $age . "</td>";
                    echo "<td>" . $email . "</td>";
                    echo "<td>" . $appresult . "</td>";
                    echo "<td>" . $approvedby . "</td>";
                }
            }
            echo "<td>" . $row['role'] . "</td>";
            echo "<td>" . $banresult . "</td>";
            echo "</tr>";
            echo '</table>';
            if (isset($_SESSION['icarey']['email'])) {
                if ($_SESSION['icarey']['role'] == $config['groups']['moderator'] || $_SESSION['icarey']['role'] == $config['groups']['administrator']) {
                    echo "<h4>Application</h4>";
                    echo '<table class="table table-responsive table-dark">';
                    echo '<tr>';
                    echo "<td>" . $application . "</td>";
                    echo '</tr>';
                    echo '</table>';

                }
            }
            echo '<h4>Stats</h4>';
            if ($stats == 0) {
                echo 'User has not logged in';
                echo '</br>';
            } else {
                if (!empty($stats['minecraft:custom']['minecraft:deaths'])) {
                    echo 'Deaths: ' . $stats['minecraft:custom']['minecraft:deaths'] . '';

                } else {
                    echo 'Deaths: 0';
                }
            }
            echo '</br>';
            if (isset($_SESSION['icarey']['email'])) {
                if ($_SESSION['icarey']['role'] == $config['groups']['moderator'] || $_SESSION['icarey']['role'] == $config['groups']['administrator']) {

                    $member = $config['groups']['member'];
                    $trusted = $config['groups']['trusted'];
                    $donor = $config['groups']['donor'];
                    $mod = $config['groups']['moderator'];
                    $admin = $config['groups']['administrator'];


                    echo '<form class="groupform" id="groupform" action="utils.php" method="post">';
                    echo '<select name="group">';
                    echo '<option value="' . $member . '">' . $member . '</option>';
                    echo '<option value="' . $trusted . '">' . $trusted . '</option>';
                    echo '<option value="' . $donor . '">' . $donor . '</option>';
                    if ($_SESSION['icarey']['role'] == $config['groups']['administrator']) {
						echo '<option value="' . $mod . '">' . $mod . '</option>';
                        echo '<option value="' . $admin . '">' . $admin . '</option>';
                    }
                    echo '</select>';
                    echo '</br>';
                    echo '<input type="hidden" name="username" id="username" value="' . $username . '">';
                    echo '<input type="hidden" name="uuid" id="uuid" value="' . $uuid . '">';
                    echo '<button type="submit" name="changegroup" value="changegroup" class="btn btn-sm icarey-btn-space btn-outline-success float-left">Change Role</button>';
                    echo '</form>';

                    echo '<button type="button" class="btn icarey-btn-space btn-outline-danger btn-sm float-left" onclick="div_show_deny()">Remove From Whitelist</button>';
                    if ($banned == 0) {
                        echo '<button class="btn icarey-btn-space btn-outline-danger btn-sm float-left" onclick="div_show_ban()">Ban</button>';
                    } elseif ($banned == 1) {
                        echo '<button type="button" class="btn icarey-btn-space btn-outline-warning btn-sm float-left" onclick="div_show_unban()">Unban</button>';
                    }
                    echo '<a class="navlink" href="lookup.php"><button type="button" class="btn icarey-orange btn-sm float-right">Back</button></a>';
                }
            } else {
                echo '<a class="navlink" href="lookup.php"><button type="button" class="btn icarey-orange btn-sm float-right">Back</button></a>';
            }

        }
    }
    echo '</div>';
    echo '<div class="col col-3 text-center">';
    echo '<h4 class="wltitle-hidden"></h4><br>';
    echo '<img src="img/skins/body/' . $uuid . '.png">';
    echo '</div>';
    echo '</div>';
    if (isset($_SESSION['icarey']['email'])) {
        if ($_SESSION['icarey']['role'] == $config['groups']['moderator'] || $_SESSION['icarey']['role'] == $config['groups']['administrator']) {
            echo '<div id="DenyWindow">';
            echo '<div id="popupDeny">';
            echo '<div class="container">';
            echo '<h2>Deny ' . $username . ' </h2>';
            echo ' <form class="denyform" id="denyform" action="whitelist.php" method="post">';
            echo '<i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4" onclick ="div_hide_deny()" title="Close" ></i>';
            echo ' <input type="hidden" name="id" id="hiddenField1" value="' . $id . '" />';
            echo ' <input type="hidden" name="username" id="hiddenField3" value="' . $username . '">';
            echo ' <input type="hidden" name="email" id="hiddenField2" value="' . $email . '" />';
            echo '<div class="form-group">';
            echo '<label for="denialreason">Denial Reason</label>';
            echo '<textarea name="reason" class="form-control" id="denialreason" placeholder="Why are you declining this user?" cols="50%" rows="4"></textarea>';
            echo '</div>';
            echo '<button type="submit" name="decline" value="decline" class="btn btn-danger btn-sm">Decline</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div id="BanWindow">';
            echo '<div id="popupBan">';
            echo '<div class="container">';
            echo '<h2>Ban ' . $username . ' </h2>';
            echo '<form class="denyform" id="banform" action="bans.php" method="post">';
            echo '<i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4" onclick ="div_hide_ban()" title="Close" ></i>';
            echo ' <input type="hidden" name="id" id="hiddenField1" value="' . $id . '" />';
            echo ' <input type="hidden" name="username" id="hiddenField3" value="' . $username . '">';
            echo ' <input type="hidden" name="email" id="hiddenField2" value="' . $email . '" />';
            echo '<div class="form-group">';
            echo '<label for="denialreason">Ban Reason</label>';
            echo '<textarea name="reason" class="form-control" id="banreason" placeholder="Why are you banning this user?" cols="50%" rows="4"></textarea>';
            echo '</div>';
            echo '<button type="submit" name="ban" value="ban" class="btn btn-danger btn-sm">Ban</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div id="UbanWindow">';
            echo '<div id="popupUban">';
            echo '<div class="container">';
            echo '<h4>Unban ' . $username . ' ?</h4>';
            echo '<form class="ubanform" id="ubanform" action="bans.php" method="post">';
            echo '<i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4" onclick ="div_hide_unban()" title="Close" ></i>';
            echo '<input type="hidden" name="id" id="id" value="' . $id . '" />';
            echo '<input type="hidden" name="username" id="username" value="' . $username . '">';
            echo '<input type="hidden" name="email" id="email" value="' . $email . '" />';
            echo '<button type="submit" name="unban" value="unban" class="btn btn-outline-danger">Unban</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }


} else if (isset($_POST['search'])) {
    echo '<div class="container well well-icarey">
            <div class="row">
                <div class="col col-12">
                    <h4 class="wltitle icarey-orange-text">User Lookup</h4>
                    <div class="search-container">
                        <form id="search-form" action="lookup.php" method="POST">
                            <input type="text" placeholder="Search.." name="query">
                            <button type="submit" name=search value=search class="btn icarey-orange"><i class="fa fa-search"></i></button>
                         </form>
                    </div>
                </div>
                ';
    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $search_term = $database->clean(strtolower($_POST['query']));
    $resultsearch = $database->query("SELECT * FROM website_users WHERE username LIKE '%$search_term%' AND approved = '1' ORDER BY `username`");
    if ($database->num_rows($resultsearch) == 0) {
        print_r($search_term);
        echo '<div class="alert alert-success" role="alert">Nothing Found</div>';
    } else if ($database->num_rows($resultsearch) > 0) {
        while ($row = $database->fetch($resultsearch)) {
            $uuid = $row['uuid'];
            $statsfile = $config['minecraft']['worldpath'] . "/stats/$uuid.json";
            if (file_exists($statsfile)) {
                //echo "$filename was last modified: " . date ("F d Y H:i:s.", filemtime($filename));
                $lastlogin = date("F d Y", filemtime($statsfile));
            } else {
                $lastlogin = 'User has not logged in';
            }
            if (!file_exists("img/skins/head/$uuid.png")) {
                $head = Basic::getPlayerHead($uuid);
            }
            if ($row['banned'] == 1) {
                $banned = 'Banned';
            } else {
                $banned = '';
            }
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo '<td style="margin-left: 5%;  width:15%; color: #FF9900;"><img height="20%" width="20%" src="img/skins/head/' . $uuid . '.png"></td>';
            echo '<td style="width:30%; color: #FF9900;"><a href="lookup.php?user=' . $row['username'] . '"</a>' . $row['username'] . '</td>';
            echo '<td style="width:30%; color: #FF9900;">Last Seen: ' . $lastlogin . '</td>';
            echo '<td style="width:15%; color: #ff1b00;">' . $banned . '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';

    echo '</div>';
    echo '</div>';

} else {
    //Show a list of current registered users and a search bar
    echo '<div class="container well well-icarey">
            <div class="row">
                <div class="col col-12">
                    <h4 class="wltitle icarey-orange-text">User Lookup</h4>
                    <div class="search-container">
                        <form id="search-form" action="lookup.php" method="POST">
                            <input type="text" placeholder="Search.." name="query">
                            <button type="submit" name=search value=search class="btn icarey-orange"><i class="fa fa-search"></i></button>
                         </form>
                    </div>
                </div>';

    $database = new DatabaseConnect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['port']);
    $resultuser = $database->query("SELECT * FROM website_users WHERE approved = '1' ORDER BY `username`");
    if ($database->num_rows($resultuser) == 0) {
        echo '<div class="alert alert-success" role="alert">No Users exist.</div>';
    } else if ($database->num_rows($resultuser) > 0) {
        while ($row = $database->fetch($resultuser)) {
            $uuid = $row['uuid'];
            $statsfile = $config['minecraft']['worldpath'] . "/stats/$uuid.json";
            if (file_exists($statsfile)) {
                //echo "$filename was last modified: " . date ("F d Y H:i:s.", filemtime($filename));
                $lastlogin = date("F d Y", filemtime($statsfile));
            } else {
                $lastlogin = 'User has not logged in';
            }
            if (!file_exists("img/skins/head/$uuid.png")) {
                $head = Basic::getPlayerHead($uuid);
            }
            if ($row['banned'] == 1) {
                $banned = 'Banned';
            } else {
                $banned = '';
            }
            echo '<table class="table table-responsive table-dark">';
            echo '<tr>';
            echo '<td style="margin-left: 5%;  width:15%; color: #FF9900;"><img height="20%" width="20%" src="img/skins/head/' . $uuid . '.png"></td>';
            echo '<td style="width:30%; color: #FF9900;"><a href="lookup.php?user=' . $row['username'] . '"</a>' . $row['username'] . '</td>';
            echo '<td style="width:30%; color: #FF9900;">Last Seen: ' . $lastlogin . '</td>';
            echo '<td style="width:15%; color: #ff1b00;">' . $banned . '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';

    echo '</div>';
    echo '</div>';


}
?>
    </div>
    <!-- Footer Import -->
<?php

include_once('footer.php'); ?>