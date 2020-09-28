<?php
ob_start();
    if (!isset($_GET['v'])) {
        header("location:../index.php");
    }
    session_start();
    include "conn/dbconn.php";
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
    <div class="wrapper-usersearch">
        <div class="banner">
            <div class="cover">
                <?php
                    $userresult = R::getAssocRow("SELECT * from `profielen` where `idcode`=?",[$_GET['v']]);
                    $user = array();
                    foreach ($userresult as $key => $value) {
                        foreach ($value as $k => $v) {
                            $user[$k]=$v;
                        }
                    }
                    if ($user['avatar']=='usericon.png') {
                        echo "
                        <img src='css/img/avcircle.png' alt='avatar' id='avcircle'>
                        ";
                    }else{
                        echo "
                        <img src=./handlers/useravatars/".$user['avatar']. " alt='./css/img/logos/usericon.png' id='avcircle' style='border-radius: 50%;'>
                        ";
                    }
                ?>
                <div class="usernamec">
                    <p class="user">
                        <?php 
                            echo $user['voornaam'] . " " . $user['familienaam'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="underbar">
                <div class="underbar_items">
                    <div class="underbar_item"><a href="!#" class="underbar_link">Fotos</a></div>
                    <div class="underbar_item"><a href="!#" class="underbar_link">Vrienden</a></div>
                </div>
                <div class="underbar_forms">
                <?php
                    $res = R::getRow("SELECT * from `profielen` where `idcode`=?",[$_GET['v']]);
                    $requests = R::getAll("SELECT * from `invites` where geaccepteerd=0 and `geweigerd`=0 and aanvrager_idcode=? and bestemming_idcode=?  or geaccepteerd=0 and `geweigerd`=0 and aanvrager_idcode=? and bestemming_idcode=?",[$_GET['v'],$_SESSION['idcode'],$_SESSION['idcode'],$_GET['v']]);
                    $friendcheck=R::getAll("SELECT * from `invites` where geaccepteerd=1 and aanvrager_idcode=? and bestemming_idcode=? or geaccepteerd=1 and aanvrager_idcode=? and bestemming_idcode=?",[$_GET['v'],$_SESSION['idcode'],$_SESSION['idcode'],$_GET['v']]);
                    if ($res['idcode']!==$_SESSION['idcode']) {
                        if (!empty($friendcheck)) {
                            echo "
                                <div id='verzonden'><p>✔ Vrienden</p></div>
                            ";
                        }
                        if (!empty($friendcheck)) {
                            echo "
                                <form method='POST'>
                                    <div id='ontvriend'>
                                        <button type='submit' id='do_unfriend' name='do_unfriend'>Ontvrienden</button>
                                    </div>
                                </form>
                            ";
                        }
                        if (isset($_POST['do_unfriend'])) {
                            $relid= $friendcheck['0']["id"];
                            $unfriend = R::load('invites',$relid);
                            $unfriend->geaccepteerd=0;
                            $unfriend->geweigerd=1;
                            R::store($unfriend);
                            header("refresh:0");
                        }
                        if (empty($friendcheck)&&empty($requests)) {
                            echo "
                                <form class='form_invite' method='POST'>
                                    <button type='submit' id='btn_invite' name='do_invite'>Stuur vriendschapverzoek</button>
                                </form>";
                        }
                        $checkdeclined = R::getAll("SELECT * FROM `invites` where `geweigerd`=1 and `aanvrager_idcode`=? and `bestemming_idcode`=? or `geweigerd`=1 and `aanvrager_idcode`=? and `bestemming_idcode`=?",[$_GET['v'],$_SESSION['idcode'],$_SESSION['idcode'],$_GET['v']]);
                        if (isset($_POST['do_invite'])) {
                                if (!empty($checkdeclined)) {
                                    $backfriend = R::load('invites',$checkdeclined[0]['id']);
                                    $backfriend->geaccepteerd=0;
                                    $backfriend->geweigerd=0;
                                    R::store($backfriend);
                                    header("refresh:0");
                                }
                                if (empty($checkdeclined)) {
                                    date_default_timezone_set('Europe/Paris');
                                    $request = R::dispense('invites');
                                    $request -> aanvrager_idcode = $_SESSION['idcode'];
                                    $request -> aanvrager_voornaam = $_SESSION['voornaam'];
                                    $request -> aanvrager_familienaam = $_SESSION['familienaam'];
                                    $request -> bestemming_idcode = $res['idcode'];
                                    $request -> bestemming_voornaam = $res['voornaam'];
                                    $request -> bestemming_familienaam = $res['familienaam'];
                                    $request -> aanvraagtijd = date("Y-m-d H:i:s");
                                    $request -> geaccepteerd = 0;
                                    $request -> geweigerd = 0;
                                    $id = R::store($request);
                                    header("refresh:0");
                                    ob_end_flush();
                                }
                            }
                        if (!empty($requests)) {
                            if($requests[0]['aanvrager_idcode']==$_SESSION['idcode']&&$requests[0]['bestemming_idcode']==$_GET['v']){
                                echo "
                                    <form class='form_invite'>
                                        <div id='verzonden'>Verzoek verzonden</div>
                                    </form>
                                ";
                            }
                        }
                    }
                    
                ?>
                </div>
            </div>
        </div>
    </div>
    </main>
    
</body>
</html>