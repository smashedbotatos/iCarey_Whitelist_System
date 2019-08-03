<?php
/**
 * Profile Page for the main site
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */


include_once('header.php');
if (!isset($_SESSION['icarey']['email'])) {
    header("Location:index.php");
}
?>

    <!-- Page Content -->
    <div class="container well well-icarey">
        <div class="row">
            <div class="col col-9">
                <h1 class="wltitle icarey-orange-text">User Profile
                    of <?php print_r($_SESSION['icarey']['username']) ?></h1>
                <ul>
                    <li>Group: <?php print_r($_SESSION['icarey']['role']); ?></li>
                    <?php if ($config['addons']['discord_bot'] == 'true') { ?>
                        <li>Discord Registration: !verify
                            @<?php print_r($_SESSION['icarey']['discorduser']) ?> <?php print_r($_SESSION['icarey']['verification']) ?></li> <?php } ?>
                    <li>Stats Page: <a
                                href="<?php print_r($config['general']['siteurl']); ?>/stats.php#player:<?php print_r($_SESSION['icarey']['uuid']); ?>"><?php print_r($_SESSION['icarey']['username']); ?></a>
                    </li>
                </ul>
            </div>
            <div class="col col-3 text-center">
                <h4 class="wltitle icarey-orange-text"></h4>
                <?php
                $uuid = $_SESSION['icarey']['uuid'];
                echo '<img src="img/skins/body/' . $uuid . '.png">';
                ?>
            </div>
        </div>
    </div>

    <!-- Footer Import -->
<?php include_once('footer.php'); ?>