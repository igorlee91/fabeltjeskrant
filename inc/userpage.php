<?php
    session_start();
    include_once "conn/dbconn.php";
    if (!isset($_SESSION['voornaam'])) {
        header("location:../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="NL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabeltjeskrant</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    
    <script src="js/index.js"></script>
</head>
<body class='body'>
    <?php
        include_once 'header.php';
    ?>
    <main class='main-userpage'>
        <div class="wrapper-mypost">
            <form class="mypost-container" method="POST" action="handlers/posthandler.php" enctype="multipart/form-data">
                <textarea id='mypost' placeholder='Waar denk je aan?' name="mypost" required></textarea>
                <div class="uploadpanel-between">
                    <label for="upimage" id="labelupimage">Foto uploaden</label>
                    <input id='upimage'type="file" name="foto">
                </div>
                <div class="uploadpanel">
                    <button id="bevestig" type="submit" name="do_plaats">Plaats bericht</button>
                </div>
            </form>
        </div>
        <div class='wrapper-berichten'>
            <h2 class='wrapper-title'>Berichten</h2>
                <?php
                    $user = R::getAssoc("SELECT * FROM `userposts` WHERE `idcode`=?",[$_SESSION['idcode']]);
                    foreach ($user as $key => $value) {
                        if (!empty($value['foto'])) {
                            echo "
                                <div class='gepost-container'>
                                    <div class='foto-container'>
                                        <img class='fotouser' src=handlers/userimages/". $value['foto'].">        
                                    </div>
                                    <div class='bericht-container'>
                                    <div class='bericht-gepost'>".$value['bericht'] ."</div>
                                        <div class='auteur'>
                                            <div class='iconwrap'>
                                                <img src=handlers/useravatars/". $value['avatar'] ." alt='css/img/logos/usericon.png' class='icon_profile'>
                                                <p>".$value['voornaam']."</p>
                                            </div>
                                            <p id='gepostop'>Gepost op ".$value['tijdgepost']."</p>
                                        </div>
                                </div> 
                            </div>";
                        }elseif (empty($value['foto'])) {
                            echo "
                                <div class='gepost-container'>
                                    <div class='bericht-container'>
                                    <div class='bericht-gepost'>".$value['bericht'] ."</div>
                                        <div class='auteur'>
                                            <div class='iconwrap'>
                                                <img src=handlers/useravatars/". $value['avatar'] ." alt='css/img/logos/usericon.png' class='icon_profile'>
                                                <p>".$value['voornaam']."</p>
                                            </div>
                                            <p id='gepostop'>Gepost op ".$value['tijdgepost']."</p>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }
                ?>
            </div>    
        </div>
        <div class="wrapper-myfriends">
        <h2 class='wrapper-title'>Vrienden</h2>
            <div class="friends-container">
                <?php
                    $accepted_invites = R::getAssoc("SELECT * from `invites` where geaccepteerd=1 and bestemming_idcode=? or geaccepteerd=1 and aanvrager_idcode=?",[$_SESSION['idcode'],$_SESSION['idcode']]);
                    $fr_av = array();
                    foreach ($accepted_invites as $key => $value) {
                        if ($value['aanvrager_idcode']!==$_SESSION['idcode']) {
                            $find_avatar = R::getRow("SELECT * from `profielen` where `idcode`=?",[$value['aanvrager_idcode']]);
                        }
                        if ($value['aanvrager_idcode']==$_SESSION['idcode']) {
                            $find_avatar = R::getRow("SELECT * from `profielen` where `idcode`=?",[$value['bestemming_idcode']]);
                        }
                        foreach ($find_avatar as $k => $v) {
                            $fr_av[$k]=$v; 
                        }
                ?>
                    <div class="friend-item">
                        <img src="handlers/useravatars/<?php echo $fr_av['avatar'];?> " alt="css/img/logos/usericon.png" class="friend-av">
                        <div class="fr-name">
                            <?php 
                                if ($value['aanvrager_idcode']==$_SESSION['idcode']) {
                                    echo "<a href=./user.php?v=".$fr_av['idcode'].">".$value['bestemming_voornaam']." ".$value['bestemming_familienaam']."</a>";
                                }
                                if ($value['bestemming_idcode']==$_SESSION['idcode']) {
                                    echo "<a href=./user.php?v=".$fr_av['idcode'].">".$value['aanvrager_voornaam']." ".$value['aanvrager_familienaam']."</a>";
                                }
                            ?>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <script>
            if(document.querySelector(".wrapper-berichten").children.length<=1){
                document.querySelector(".wrapper-berichten").classList.add("unshown");
            };
            if(document.querySelector(".friends-container").children.length<1){
                document.querySelector(".wrapper-myfriends").classList.add("unshown");
            };
        </script>
    </main>
    <footer class='footer'>
        <p>&copy Igor Lee</p>
    </footer>
</body>
</html>