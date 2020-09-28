<?php
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
                        <img src=./handlers/useravatars/".$user['avatar']. " alt='avatar' id='avcircle' style='border-radius: 50%;'>
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
                <form class="underbar_form" method="POST">
                    <?php
                        $res = R::getRow("SELECT * from `profielen` where `idcode`=?",[$_GET['v']]);
                        $sendcheck = R::getAll("SELECT * from `invites` where geaccepteerd=0 and `geweigerd`=0 and aanvrager_idcode=? and bestemming_idcode=?  or geaccepteerd=0 and `geweigerd`=0 and aanvrager_idcode=? and bestemming_idcode=?",[$_GET['v'],$_SESSION['idcode'],$_SESSION['idcode'],$_GET['v']]);
                        $friendcheck=R::getAll("SELECT * from `invites` where geaccepteerd=1 and aanvrager_idcode=? and bestemming_idcode=? or geaccepteerd=1 and aanvrager_idcode=? and bestemming_idcode=?",[$_GET['v'],$_SESSION['idcode'],$_SESSION['idcode'],$_GET['v']]);
                        if ($res['voornaam']!==$_SESSION['voornaam']) {
                            if (!empty($sendcheck)) {
                                echo "
                                    <form class='form_invite'>
                                        <button type='submit' id='btn_invite' name='do_invite'>Stuur vriendschapverzoek</button>
                                    </form>
                                ";
                            }
                            if (!empty($sendcheck)) {
                                foreach ($sendcheck as $key => $value) {
                                    if($value['aanvrager_idcode']==$_SESSION['idcode']&&$value['bestemming_idcode']==$_GET['v']){
                                        echo "
                                            <form class='form_invite'>
                                                <div id='verzonden'>Verzoek verzonden</div>
                                            </form>
                                        ";
                                    }elseif ($value['aanvrager_idcode']==$_GET['v']&&$value['bestemming_idcode']==$_SESSION['idcode']) {
                                        ?>
                                        <form method='POST'>
                                            <button type='submit' name='do_accept<?php echo $value['id']; ?>'>Aanvaarden</button>
                                            <button type='submit' name='do_decline'>Weiger</button>
                                        </form>
                                        <?php
                                        if (isset($_POST['do_accept'.$value['id']])) {
                                            $accepted = R::load('invites',$value['id']);
                                            $accepted -> geaccepteerd = 1;
                                            R::store($accepted);
                                            header("refresh:0");
                                        }
                                    }
                                }
                            }
                            if (!empty($friendcheck)) {
                                echo "
                                    <div id='verzonden'><p>✔ Vrienden</p></div>
                                ";
                            }
                            if (isset($_POST['do_invite'])) {
                                //1 update requesters status
                                //2 update recepient status
                                if (!isset($_SESSION['verzoekverzonden'])) {
                                    if (!isset($_SESSION['verzoekverzonden'])==$res['idcode']) {
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
                                        $_SESSION['verzoekverzonden']=$res['idcode'];
                                    }
                                }
                            }
                        }
                        
                    ?>
                </form>
                <?php
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
                    }
                ?>
            </div>
            </div>
    </div>
    </main>
    
</body>
</html>