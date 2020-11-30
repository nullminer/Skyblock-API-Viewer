<?php
require 'resources/packages/nbt.class.php';
$config = json_decode(file_get_contents("config.json"), 1);
function exists($var, $type = 0) {
    if (isset($var) && $var != null) {
        print($var);
    } else {
        if ($type == 1) {
            print("N/A");
        } else {
            print("None");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            <?php
            if (isset($_GET['player']) && $_GET['player'] != null) {
                print($_GET['player'] . "'s Stats");
            } else {
                print("Skyblock API Viewer");
            }
            ?>
        </title>
        <style>
            .accordion {
                background-color: #eee;
                color: #444;
                cursor: pointer;
                padding: 18px;
                width: 100%;
                border: none;
                text-align: left;
                outline: none;
                font-size: 15px;
                transition: 0.4s;
            }

            .active, .accordion:hover {
                background-color: #ccc;
            }

            .panel {
                padding: 0 18px;
                background-color: white;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.2s ease-out;
            }
            
            .accordion:after {
                content: '\02795';
                font-size: 13px;
                color: #777;
                float: right;
                margin-left: 5px;
            }

            .active:after {
                content: "\2796";
            }
            
            html, body {
                background-image: url("resources/images/background.png");
                height: 100%;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        </style>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    </head>
    <body>
        <!-- Header (Coming Soon) -->
        <?php
        if (isset($_GET['player']) && $_GET['player'] != null) {
            $mojangapi = file_get_contents("https://api.mojang.com/users/profiles/minecraft/" . $_GET['player']);
            if ($mojangapi != null) {
                $uuid = json_decode($mojangapi, true)['id'];
                $profileapi = file_get_contents("https://api.hypixel.net/skyblock/profiles?key=" . $config['apikey'] . "&uuid=$uuid");
                $profiles = json_decode($profileapi, true)['profiles'];
                if ($profiles != null) {
                    if (!file_exists("history/$uuid")) {
                        mkdir("history/$uuid");
                    }
                    file_put_contents("history/$uuid/" . time() . ".txt", $profileapi);
                    foreach ($profiles as $profile) {
                        $playerdata = $profile['members'][$uuid];
			            ?>
			            <button class="accordion"><?php print($profile['cute_name']) ?></button>
                        <div class="panel">
                            <b>Quick Stats</b>
                                <p>Last Save: <?php exists($playerdata['last_save']) ?></p>
                                <p>Fairy Souls: <?php exists($playerdata['fairy_souls_collected']) ?></p>
                                <?php
                                if (isset($playerdata['coin_purse'])) {
                                    ?><p>Purse: <?php print(round($playerdata['coin_purse'], 1)) ?></p><?php
                                } else {
                                    ?><p>Purse: None</p><?php
                                }
                                ?>
                                <p>Joined: <?php exists($playerdata['first_join']) ?></p>
                                <p>Highest Crit: <?php exists($playerdata['stats']['highest_critical_damage']) ?></p>
                            <b>Auction Stats</b>
                                <p>Total Bids: <?php exists($playerdata['stats']['auctions_bids']) ?></p>
                                <p>Auction Wins: <?php exists($playerdata['stats']['auctions_won']) ?></p>
                                <p>Highest Bid: <?php exists($playerdata['stats']['auctions_highest_bid']) ?></p>
                                <p>Total Spent: <?php exists($playerdata['stats']['auctions_gold_spent']) ?></p>
                                <p>Auction Fees: <?php exists($playerdata['stats']['auctions_fees']) ?></p>
                                <p>Total Earned: <?php exists($playerdata['stats']['auctions_gold_earned']) ?></p>
                                <p>Auctions w/o Bids: <?php exists($playerdata['stats']['auctions_no_bids']) ?></p>
                                <p>Items Sold: <?php print($playerdata['stats']['auctions_sold_common'] + $playerdata['stats']['auctions_sold_uncommon'] + $playerdata['stats']['auctions_sold_rare'] + $playerdata['stats']['auctions_sold_epic'] + $playerdata['stats']['auctions_sold_legendary'] + $playerdata['stats']['auctions_sold_mythic'] + $playerdata['stats']['auctions_sold_special']) ?></p>
                            <b>Kills/Deaths Stats</b>
                                <p>Kills: <?php exists($playerdata['stats']['kills']) ?></p>
                                <p>Deaths: <?php exists($playerdata['stats']['deaths']) ?></p>
                                <?php
                                if (isset($playerdata['stats']['kills']) && isset($playerdata['stats']['deaths'])) {
                                    ?><p>K/D: <?php print(round($playerdata['stats']['kills'] / $playerdata['stats']['deaths'], 2)) ?></p><?php
                                } else {
                                    ?><p>K/D: None</p><?php
                                }
                                ?>
                            <b>Slayer Stats</b>
                                <table style="width:15%;">
                                    <tbody>
                                        <tr>
                                            <td>---</td>
                                            <td><u>T1</u></td>
                                            <td><u>T2</u></td>
                                            <td><u>T3</u></td>
                                            <td><u>T4</u></td>
                                            <td><u>XP</u></td>
                                        </tr>
                                        <tr>
                                            <td><u>Zombie</u></td>
                                            <td><?php exists($playerdata['slayer_bosses']['zombie']['boss_kills_tier_0'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['zombie']['boss_kills_tier_1'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['zombie']['boss_kills_tier_2'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['zombie']['boss_kills_tier_3'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['zombie']['xp'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Spider</u></td>
                                            <td><?php exists($playerdata['slayer_bosses']['spider']['boss_kills_tier_0'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['spider']['boss_kills_tier_1'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['spider']['boss_kills_tier_2'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['spider']['boss_kills_tier_3'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['spider']['xp'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Wolf</u></td>
                                            <td><?php exists($playerdata['slayer_bosses']['wolf']['boss_kills_tier_0'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['wolf']['boss_kills_tier_1'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['wolf']['boss_kills_tier_2'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['wolf']['boss_kills_tier_3'], 1) ?></td>
                                            <td><?php exists($playerdata['slayer_bosses']['wolf']['xp'], 1) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <b>Armour Data</b>
                                <p>Coming Soon!</p>
                                <?php
                                // Create NBT file and load it
                                ?>
                            <p>&nbsp;</p>
                            </div>
			            <?php
                    }
                    ?>
                    <script>
                        var acc = document.getElementsByClassName("accordion");
                        var i;

                        for (i = 0; i < acc.length; i++) {
                            acc[i].addEventListener("click", function() {
                                this.classList.toggle("active");
                                var panel = this.nextElementSibling;
                                if (panel.style.maxHeight) {
                                    panel.style.maxHeight = null;
                                } else {
                                    panel.style.maxHeight = panel.scrollHeight + "px";
                                }
                            });
                        }
                    </script>
                    <?php
                } else {
                    ?>
                    <audio autoplay="true" style="display:none;">
                        <source src="resources/music/error.mp3" type="audio/mp3">
                    </audio>
                    <h2>The player has no profiles!</h2>
                    <?php
                }
            } else {
                ?>
                <audio autoplay="true" style="display:none;">
                    <source src="resources/music/error.mp3" type="audio/mp3">
                </audio>
                <h2>The player doesn't exist!</h2>
                <?php
            }
        } else {
            ?>
            <div style="text-align:center; padding-top:10%;">
			    <form action="index.php" method="get">
			        <p>&nbsp;</p>
			        <input class="input is-large has-text-centered" style="width:30%;" type="text" id="player" name="player" placeholder="Enter Player IGN" autofocus>
			        <p>&nbsp;</p>
                    <button class="button is-rounded is-large">Search</button>
			    </form>
			</div>
            <?php
        }
        ?>
        <!-- Footer (Coming Soon) -->
    </body>
</html>
