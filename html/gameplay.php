<?php
/**
 * Gameplay information page for the main site
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
            <h1 class="wltitle">Gameplay</h1>
            <p>This page is here to help you get familiarized with the game play on iCarey.net Minecraft.</p>
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="StartingOut">
                        <h5 class="mb-0">
                            <button class="btn icarey-orange-text btn-link" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Starting Out
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="StartingOut" data-parent="#accordion">
                        <div class="card-body">
                            Wait out your first night in the spawn area, talk to other players and maybe forage for food
                            in the community gardens.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="FirstDay">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed icarey-orange-text" data-toggle="collapse"
                                    data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                First Day
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="FirstDay" data-parent="#accordion">
                        <div class="card-body">
                            When the sun comes up its off to find a new home, head out from spawn in your desired
                            direction. Do not build within 200 blocks of spawn, or with 200 blocks of another player.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="CustomDrops">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed icarey-orange-text" data-toggle="collapse"
                                    data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Custom Loot Tables
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="CustomDrops" data-parent="#accordion">
                        <div class="card-body">
                            We have a datapack that drops heads from all mobs when killed on a rare occasion. Plus the
                            Ender Dragon drops Elytra's, and Enderman rarely drop Shulker shells. This is to help curb
                            the issue of The End being raided.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="Sleep">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed icarey-orange-text" data-toggle="collapse"
                                    data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Single Player Sleep
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="Sleep" data-parent="#accordion">
                        <div class="card-body">
                            We use a Single Player Sleep datapack that allows a single person to sleep to make night go
                            slightly faster. The more people that sleep the faster the night goes. It is calculated by
                            how many players are on-line and how many players are sleeping.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-3 text-center">
            <h4 class="wltitle">Server Status</h4>
            <?php
            $status = new MCQuery();
            $online = $status->GetStatus($config['minecraft']['host'], $config['minecraft']['port'])->Response();
            $players = $online['player_list'];
            if ($online['online'] === true) {
                print_r('<br />');
                print_r('<h5 style="text-align: center">Server Online </h5><img src="img/Redstone_Torch_On.png" style="width: 20%; align-self: center;"> <br />');
                print_r('<p>');
                print_r($online['players']);
                print_r('/');
                print_r($online['max_players']);
                print_r(' Online Players</p>');
                foreach ($online['player_list'] as $result) {
                    print_r($result);
                    print_r(" ");
                }
            } else {
                print_r('<br />');
                print_r('<h5 style="text-align: center">Server Offline </h5><img src="img/Redstone_Torch_Off.png">');
            }


            ?>
        </div>
    </div>
</div>

<!-- Footer Import -->
<?php include_once('footer.php'); ?>
