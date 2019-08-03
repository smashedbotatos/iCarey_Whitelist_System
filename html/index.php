<?php
/**
 * Home Page for the main site
 * @license      Apache License v2.0
 * @author       Smashedbotatos <ian@icarey.net>
 * @copyright    Copyright © 2009-2019 iCarey Computer Services.
 *
 */
?>

<!-- Header Import -->
<?php include_once('header.php'); ?>
<!-- Page Content -->
<div class="container well well-icarey">
    <div class="row">
        <div class="col col-9">
            <h1 class="wltitle icarey-orange-text">Welcome to <?php print_r($config['general']['sitename']) ?></h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat.</p>
            <img src="img/news.png" class="img-fluid" alt="Responsive image">
        </div>
        <div class="col col-3 text-center">
            <h4 class="wltitle icarey-orange-text">Server Status</h4>
            <?php
            $status = new MCQuery();
            $online = $status->GetStatus($config['minecraft']['host'], $config['minecraft']['port'])->Response();
            $players = $online['player_list'];
            if ($online['online'] === true) {
                print_r('<br />');
                print_r('<h5 class="icarey-orange-text" style="text-align: center">Server Online </h5><img src="img/Redstone_Torch_On.png" style="width: 20%; align-self: center;"> <br />');
                print_r('<p>');
                print_r($online['players']);
                print_r('/');
                print_r($online['max_players']);
                print_r(' Online Players</p>');
                if ($online['player_list'] != null) {
                    foreach ($online['player_list'] as $result) {
                        print_r($result);
                        print_r(" ");
                    }
                } else {
                    echo 'No Players Online';
                }
            } else {
                print_r('<br />');
                print_r('<h5 class="icarey-orange-text" style="text-align: center">Server Offline </h5><img src="img/Redstone_Torch_Off.png" style="width: 20%; align-self: center;">');
            }


            ?>
        </div>
    </div>
</div>

<!-- Footer Import -->
<?php include_once('footer.php'); ?>
