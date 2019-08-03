<?php
/**
 * Header for main site PHP
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
if (!session_id()) session_start();

if (!isset($_SESSION['icarey'])) {
    $_SESSION['icarey'] = array();
}


$config = parse_ini_file('includes/config.ini.php', 1, true);
require_once 'classes/Basic.php';
require_once 'classes/mail.php';
require_once 'classes/MCQuery.php';
require_once 'classes/MojangAPI.php';
$sitekey = $config['captcha']['sitekey'];
$captcha;

?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8">
    <title>iCarey Minecraft Server</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu|Ubuntu+Mono">
    <!-- Icon Fonts -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
            integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
            crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/minecraft.css">
    <!-- Google recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Favicon Configuration -->
    <link rel="apple-touch-icon" sizes="57x57" href="img/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">
    <link rel="manifest" href="img/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- JavaScript -->
    <script src="js/icarey.js"></script>
</head>

<body>
<header role="banner">
    <img src="img/header_logo1.png" id="main-logo" alt="iCarey Minecraft Server">
</header>
<div class="container"> <!-- Main Container -->
    <nav class="navbar navbar-expand-md navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rules.php">Rules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gameplay.php">Gameplay</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lookup.php">Player Search</a>
                </li>
                <li class="nav-item dropdown">
                    <button type="button" class="btn icarey-reverse-btn btn-sm">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Player Rankings
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="stats.php#hof">Hall of Fame</a>
                            <a class="dropdown-item" href="stats.php#awards">Awards</a>
                            <a class="dropdown-item" href="stats.php#players">Players</a>
                        </div>
                    </button>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link">
                        <button type="button" id="popup" onclick="div_show()" class="btn icarey-orange btn-sm">
                            <i class="fas fa-list"></i>
                            Ban Appeal
                        </button>
                    </a>
                </li>
                <?php if (isset($_SESSION['icarey']['email'])) { ?>
                    <li class="nav-item dropdown">
                        <button type="button" class="btn icarey-reverse-btn btn-sm">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php $email = $_SESSION['icarey']['username'];
                                $uuid = $_SESSION['icarey']['uuid'];
                                echo '<img class="playerhead" src="img/skins/head/' . $uuid . '.png"> &nbsp ' . $email . ''; ?>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                if ($_SESSION['icarey']['role'] == $config['groups']['moderator']) {
                                    echo '<a class="dropdown-item" href="whitelist.php">Whitelist</a>';
                                    echo '<a class="dropdown-item" href="bans.php">Bans</a>';
                                    echo '<div class="dropdown-divider"></div>';
                                    echo '<a class="dropdown-item" href="profile.php"><i class="fas fa-users"></i> Profile</a>';
                                    echo '<a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';

                                } else if ($_SESSION['icarey']['role'] == $config['groups']['administrator']) {
                                    echo '<a class="dropdown-item" href="whitelist.php">Whitelist</a>';
                                    echo '<a class="dropdown-item" href="bans.php">Bans</a>';
                                    echo '<a class="dropdown-item" href="commands.php">Commands</a>';
                                    echo '<div class="dropdown-divider"></div>';
                                    echo '<a class="dropdown-item" href="profile.php"><i class="fas fa-users"></i> Profile</a>';
                                    echo '<a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                                } else {
                                    echo '<a class="dropdown-item" href="profile.php"><i class="fas fa-users"></i> Profile</a>';
                                    echo '<a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                                }
                                ?>
                            </div>
                        </button>
                    </li>

                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link">
                            <button type="button" id="loginPopup" onclick="div_show_login()"
                                    class="btn icarey-orange btn-sm">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </button>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <button type="button" id="regPopup" onclick="div_show_reg()"
                                    class="btn icarey-orange btn-sm">
                                <i class="fas fa-user-plus"></i>
                                Register
                            </button>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>


    <!-- Main Menu Navigation ================================================== -->

    <!-- Appeal Form Popup -->
    <div id="TrackingWindow">
        <!-- Ban Popup Starts Here -->
        <div id="popupTrack">
            <!-- Appeal Form -->
            <div class="container">
                <form id="appeal-form" action="appeal.php" method="POST">
                    <h2>Ban Appeals</h2>
                    <i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4" onclick="div_hide()"
                       title="Close"></i>
                    <div class="form-group">
                        <label for="mc-email">Email address</label>
                        <input type="email" name="email" class="form-control" id="mc-email" aria-describedby="emailHelp"
                               placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">Email Address</small>
                    </div>
                    <div class="form-group">
                        <label for="mc-user">Minecraft Username</label>
                        <input type="text" name="username" class="form-control" id="mc-user" aria-describedby="mcUser"
                               placeholder="Enter Minecraft Username">
                        <small id="emailHelp" class="form-text text-muted">Minecraft Username</small>
                    </div>
                    <div class="form-group">
                        <label for="mc-appeal">Reason for Appeal</label>
                        <textarea class="form-control" name="comment" id="mc-appeal" aria-describedby="appealHelp"
                                  placeholder="Appeal Reason" rows="3"></textarea>
                        <small id="tagHelp" class="form-text text-muted">Reason you think you should be able to come
                            back.
                        </small>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php print_r($sitekey) ?>">
                    </div>
                    <button type="submit" name='appeal' value='Appeal' class="btn icarey-orange">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Appeal Form End -->
    <!-- Login Form Popup -->
    <div id="LoginWindow">
        <!-- Popup Div Starts Here -->
        <div id="popupLogin">
            <!-- Login Form -->
            <div class="container">
                <form id="login-form" action="login.php" method="POST">
                    <h2>Login</h2>
                    <i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4"
                       onclick="div_hide_login()" title="Close"></i>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp"
                               placeholder="Enter Email">

                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               aria-describedby="passwordHelp" placeholder="Enter Password">

                        <a href="forgot.php">Forgot Password?</a>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php print_r($sitekey) ?>">
                    </div>
                    <button type="submit" name='login' value='Login' class="btn icarey-orange">Login</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Login Form End -->
    <!-- Registration Form Popup -->
    <div id="RegWindow">
        <!-- Popup Div Starts Here -->
        <div id="popupReg">
            <!-- Registration Form -->
            <div class="container">
                <form class="regform" id="regform" action="register.php" method="post">
                    <h2>Registration</h2>
                    <i id="track-close" class="fa fa-2x fa-times-circle float-md-right ml-4 mb-4"
                       onclick="div_hide_reg()" title="Close"></i>
                    <div class="form-group">
                        <label for="InputIgn">Minecraft Username</label>
                        <input type="text" name="username" class="form-control" id="InputIgn"
                               placeholder="Minecraft Username">
                    </div>
                    <div class="form-group">
                        <label for="InputEmail">Email address</label>
                        <input type="email" name="email" class="form-control" id="InputEmail"
                               aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password</label>
                        <input type="password" name="password" class="form-control" id="InputPassword"
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password Again</label>
                        <input type="password" name="password2" class="form-control" id="InputPassword2"
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="InputAge">Age</label>
                        <input type="text" name="age" class="form-control" id="InputAge" placeholder="Age">
                    </div>
                    <div class="form-group">
                        <label for="InputDiscord">Discord</label>
                        <input type="text" name="discord" class="form-control" id="InputDiscord"
                               placeholder="DiscordUser#1234">
                    </div>
                    <div class="form-group">
                        <label for="InputComment">Application</label>
                        <textarea class="form-control" name="comment" id="InputComment" aria-describedby="commentHelp"
                                  placeholder="What brought you to our server?" rows="3"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="Check">
                        <label class="form-check-label" for="Check">I agree to the follow the rules.</label>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php print_r($sitekey) ?>">
                    </div>
                    <button type="submit" name="register" value="Register" class="btn icarey-orange">Register</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Registration Form End -->
