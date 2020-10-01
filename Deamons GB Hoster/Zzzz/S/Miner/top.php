<?php
    session_start();

    if(!isset($_COOKIE["user"])) {
        $_SESSION['error'] = 'You are not logged in!';
	    header("Location: login.php");
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

<body>
    <nav class="nav-extended teal darken-1">
        <div class="nav-wrapper">
            <a href="home.php" class="brand-logo">&nbsp;&nbsp;Putin Miner</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="home.php">Home</a></li>
                <li><a href="rewards.php">Rewards</a></li>
                <li><a href="top.php"><b>Top Users</b></a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav teal accent-3" id="mobile-demo">
        <li><a href="home.php">Home</a></li>
        <li><a href="rewards.php">Rewards</a></li>
        <li><a href="top.php"><b>Top Users</b></a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="row">
        <div class="center">
            <table class="responsive-table white">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>User</td>
                        <td>Hashes</td>
                    </tr>
                </thead>

                <tbody id="list">
                </tbody>
            </table>
        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
        <script type="text/javascript">
            M.AutoInit();

            const table = document.querySelector( "#list" );

            function isFetchAvaliable() {
                "use strict";

                try {
                    eval("fetch('all.php').then(response => response.json());");
                } catch(error) {
                    return false;
                }

                return true;
            }

            if(isFetchAvaliable()) {
                console.log('FetchAPI avaliable: Using FetchAPI');

                fetch('all.php').then(response => response.json()).then(data => {
                    let id = 0;
                        const users = data;

                        users.forEach( user => {
                            id ++;
                            const row = document.createElement( "tr" );

                            const columnID = document.createElement( "td" );
                            columnID.appendChild( document.createTextNode( id ) );
                            row.appendChild( columnID );

                            const columnUser = document.createElement( "td" );
                            columnUser.appendChild( document.createTextNode( user.User ) );
                            row.appendChild( columnUser );

                            const columnHashes = document.createElement( "td" );
                            columnHashes.appendChild( document.createTextNode( user.Hashes ) );
                            row.appendChild( columnHashes );

                            table.appendChild( row );
                        });
                });
            }  else {    
                console.log('FetchAPI unavaliable: Using jQuery Ajax');

                $.ajax({
                    url: "all.php",
                    success: function(data) {
                        let id = 0;
                        const users = JSON.parse(data);

                        users.forEach( user => {
                            id ++;
                            const row = document.createElement( "tr" );

                            const columnID = document.createElement( "td" );
                            columnID.appendChild( document.createTextNode( id ) );
                            row.appendChild( columnID );

                            const columnUser = document.createElement( "td" );
                            columnUser.appendChild( document.createTextNode( user.User ) );
                            row.appendChild( columnUser );

                            const columnHashes = document.createElement( "td" );
                            columnHashes.appendChild( document.createTextNode( user.Hashes ) );
                            row.appendChild( columnHashes );

                            table.appendChild( row );
                        });
                    }
                });
            }
        </script>
</body>
</html>