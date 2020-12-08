<?php
$config = json_decode(file_get_contents("config.json"), 1);
function exists($var, $type = 0) {
    if (isset($var) && $var != null) {
        if ($type == 2) {
            print(round($var/1000));
        } else {
            print($var);
        }
    } else {
        if ($type == 1 || $type == 2) {
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
        <?php
        if (isset($_GET['player']) && $_GET['player'] != null) {
            ?>
            <title><?php print($_GET['player']) ?>'s Stats</title>
            <link rel="icon" type="image/png" href="http://cravatar.eu/avatar/<?php print($_GET['player']) ?>.png">
            <?php
        } else {
            ?>
            <title>Skyblock API Viewer</title>
            <?php
        }
        ?>
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
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="index.php">
                    <img src="resources/images/logo.png" width="112" height="28">
                </a>
                <a class="navbar-item" href="https://hypixel.net/threads/skyblock-api-viewer-version-0-6.3602932/" target="_blank">Forum Thread</a>
                <a class="navbar-item" href="https://github.com/nullminer/Skyblock-API-Viewer" target="_blank">Github</a>
                <a class="navbar-item" href="https://www.youtube.com/channel/UCpwHu2wzwAWLGtKUu5fmkJA" target="_blank">My Youtube</a>
            </div>
        </nav>
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
                    ?>
                    <div style="width:25%; float:left; text-align:center; padding-top:12.5%;">
                        <img src="https://crafatar.com/renders/body/<?php print($uuid) ?>?scale=10" alt="<?php print($_GET['player']) ?>'s skin" />
                    </div>
                    <div style="width:75%; float:left;">
                    <?php
                    foreach ($profiles as $profile) {
                        $playerdata = $profile['members'][$uuid];
			            ?>
			            <button class="accordion"><?php print($profile['cute_name']) ?></button>
                        <div class="panel">
                            <div id="quick_stats">
                                <b>Quick Stats</b>
                                <p>Last Save: <?php print(date('Y/m/d H:i:s', $playerdata['last_save']/1000)) ?></p>
                                <p>First Join: <?php print(date('Y/m/d H:i:s', $playerdata['first_join']/1000)) ?></p>
                                <p>Fairy Souls: <?php exists($playerdata['fairy_souls_collected']) ?></p>
                                <?php
                                if (isset($playerdata['coin_purse'])) {
                                    ?><p>Purse: <?php print(round($playerdata['coin_purse'], 1)) ?></p><?php
                                } else {
                                    ?><p>Purse: None</p><?php
                                }
                                if (isset($playerdata['stats']['highest_critical_damage'])) {
                                    ?><p>Highest Crit: <?php print(round($playerdata['stats']['highest_critical_damage'])) ?></p><?php
                                } else {
                                    ?><p>Highest Crit: None</p><?php
                                }
                                ?>
                            </div>
                            <div id="auction_stats">
                                <b>Auction Stats</b>
                                <p>Total Bids: <?php exists($playerdata['stats']['auctions_bids']) ?></p>
                                <p>Auction Wins: <?php exists($playerdata['stats']['auctions_won']) ?></p>
                                <p>Highest Bid: <?php exists($playerdata['stats']['auctions_highest_bid']) ?></p>
                                <p>Total Spent: <?php exists($playerdata['stats']['auctions_gold_spent']) ?></p>
                                <p>Auction Fees: <?php exists($playerdata['stats']['auctions_fees']) ?></p>
                                <p>Total Earned: <?php exists($playerdata['stats']['auctions_gold_earned']) ?></p>
                                <p>Auctions w/o Bids: <?php exists($playerdata['stats']['auctions_no_bids']) ?></p>
                                <p>Items Sold: <?php print($playerdata['stats']['auctions_sold_common'] + $playerdata['stats']['auctions_sold_uncommon'] + $playerdata['stats']['auctions_sold_rare'] + $playerdata['stats']['auctions_sold_epic'] + $playerdata['stats']['auctions_sold_legendary'] + $playerdata['stats']['auctions_sold_mythic'] + $playerdata['stats']['auctions_sold_special']) ?></p>
                            </div>
                            <div id="kd_stats">
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
                            </div>
                            <div id="slayer_stats">
                                <b>Slayer Stats</b>
                                <table style="width:20%">
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
                            </div>
                            <div id="dungeon_stats">
                                <b>Dungeon Stats -> Catacombs</b>
                                <table style="width:45%">
                                    <tbody>
                                        <tr>
                                            <td>---</td>
                                            <td><u>Entrance</u></td>
                                            <td><u>Floor 1</u></td>
                                            <td><u>Floor 2</u></td>
                                            <td><u>Floor 3</u></td>
                                            <td><u>Floor 4</u></td>
                                            <td><u>Floor 5</u></td>
                                            <td><u>Floor 6</u></td>
                                            <td><u>Floor 7</u></td>
                                        </tr>
                                        <tr>
                                            <td><u>Times Played</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['0'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['1'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['2'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['3'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['4'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['5'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['6'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['times_played']['7'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Floor Completions</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['0'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['1'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['2'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['3'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['4'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['5'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['6'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['tier_completions']['7'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Fastest Time [s]</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['0'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['1'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['2'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['3'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['4'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['5'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['6'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time']['7'], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Best Score</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['0'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['1'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['2'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['3'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['4'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['5'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['6'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['best_score']['7'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Most Mobs Killed</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['0'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['1'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['2'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['3'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['4'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['5'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['6'], 1) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['mobs_killed']['7'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td><u>Fastest S Run</u></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['0'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['1'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['2'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['3'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['4'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['5'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['6'], 2) ?></td>
                                            <td><?php exists($playerdata['dungeons']['dungeon_types']['catacombs']['fastest_time_s']['7'], 2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p>&nbsp;</p>
                        </div>
			            <?php
                    }
                    ?>
                    </div>
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
            <div style="text-align:center; padding-top:12.5%;">
			    <form action="index.php" method="get">
                    <img src="resources/images/logo.png">
			        <p>&nbsp;</p>
			        <input class="input is-large has-text-centered" style="width:30%;" type="text" id="player" name="player" placeholder="Enter Player IGN" autofocus>
			        <p>&nbsp;</p>
                    <button class="button is-rounded is-large">Search</button>
			    </form>
			</div>
            <?php
        }
        ?>
    </body>
</html>
