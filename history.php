<!DOCTYPE html>
<html>
    <head>
        <?php
        if (isset($_GET['uuid']) && $_GET['uuid'] != null) {
            ?>
            <title><?php print($_GET['uuid']) ?></title>
            <link rel="icon" type="image/png" href="http://cravatar.eu/avatar/<?php print($_GET['uuid']) ?>.png">
            <?php
        } else {
            ?>
            <title>Skyhistory</title>
            <?php
        }
        ?>
    </head>
    <body>
    <?php
    if (isset($_GET['uuid']) && $_GET['uuid'] != null) {
        if (file_exists("history/" . $_GET['uuid'])) {
            if (isset($_GET['timestamp']) && $_GET['timestamp'] != null) {
                if (file_exists("history/" . $_GET['uuid'] . "/" . $_GET['timestamp'] . ".txt")) {
                    print(file_get_contents("history/" . $_GET['uuid'] . "/" . $_GET['timestamp'] . ".txt"));
                } else {
                    print(json_encode(["error" => "TIMESTAMP_NO_HISTORY"]));
                }
            } else {
                $filenames = scandir("history/" . $_GET['uuid']);
                $data = [];
                foreach ($filenames as $filename) {
                    if ($filename != "." && $filename != "..") {
                        $data[basename($filename, ".txt")] = 1;
                    }
                }
                print(json_encode($data));
            }
        } else {
            print(json_encode(["error" => "PLAYER_NO_HISTORY"]));
        }
    } else {
        print(json_encode(["error" => "UUID_INVALID"]));
    }
    ?>
    </body>
</html>
