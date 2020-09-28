<?php
    include_once 'inc/conn/dbconn.php';
    $d=$_POST;
?>
<!DOCTYPE html>
<html lang="NL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabeltjeskrant</title>
    <link rel="stylesheet" href="inc/css/reset.css">
    <link rel="stylesheet" href="inc/css/index.css">
</head>
<body class='body'>
    <header class='header'>
        <div class='header__topbar'>
            <form action="index.php"><button class="stdbutton">Terug</button></form>    
        </div>
    </header>
    <main class='main'>
        <div class='main__formwrapper'>
            <form class='main__signupform' method="POST" enctype="multipart/form-data">
            <?php
            date_default_timezone_set('Europe/Paris');
            $fouten=array();
            if (isset($_POST['do_signup'])) {
                foreach ($_POST as $key => $value) {
                    if (empty(trim($value))) {
                        if ($key!=='do_signup'&&$key!=='ww'&&$key!=='wwherhaal') {
                            $fouten[$key]="<label><p>leeg veld:" . $key . "</p></label><br>";
                        }elseif ($key=='ww') {
                            $fouten[$key]="<label>leeg veld: Wachtwoord</label><br>";
                        }elseif ($key=='wwherhaal') {
                            $fouten[$key]="<label>leeg veld: Bevestig Wachtwoord</label><br>";
                        }
                    }elseif(!empty(trim($value))){
                        if ($key=='ww') {
                            if (strlen($value)<6) {
                                $fouten[$key]="<label>Uw wachtwoord moet minstens 6 tekens bevatten</label> <br>";
                            }
                        }
                    }
                }
                if ($_POST['ww']!==$_POST['wwherhaal']) {
                    $fouten[$key]="<label>Uw wachtwoord komt niet overheen</label> <br>";
                } 
                if (isset($fouten)) {
                    foreach ($_POST as $kpost => $vpost) {
                        if ($kpost!=='do_signup') {
                            if (isset($fouten[$kpost])) {
                                echo $fouten[$kpost];
                            }
                        }                      
                    }
                    $userCheck = R::getAssoc( 'SELECT * FROM `profielen` WHERE email=?',[$_POST['e-mail']] );
                    if (!empty($userCheck)) {
                    $fouten['isbezet']="<label>Er bestaat reeds een gebruiker met dit e-mail adres.</label> <br>";
                    echo $fouten['isbezet'];   
                    } 
                    if (empty($fouten)) {
                        $nprofiel = R::dispense('profielen');
                        
                        $nprofiel->voornaam=$_POST['voornaam'];
                        $nprofiel->familienaam=$_POST['achternaam'];
                        $nprofiel->gemeente=$_POST['gemeente'];
                        $nprofiel->email=$_POST['e-mail'];
                        $hpw = password_hash($_POST['ww'],PASSWORD_DEFAULT);
                        $nprofiel->wachtwoord=$hpw;
                        $timestamp = date("Y-m-d H:i:s");
                        $nprofiel->timejoined=$timestamp;
                        $nprofiel->verified=0;
                        $nprofiel->idcode=md5(time());
                        if ($_FILES['profilepic']['size']==0) {
                            $nprofiel->avatar="usericon.png";
                        }elseif ($_FILES['profilepic']['size']>0) {
                            $nprofiel->avatar=$_FILES['profilepic']['name'];
                            move_uploaded_file($_FILES['profilepic']['tmp_name'],"inc/handlers/useravatars/".$_FILES['profilepic']['name']);
                        }
                        $id=R::store($nprofiel);
                        header("refresh:1;url=index.php?geregistreerd=ja");
                        echo "U bent geregistreerd! <br>";
                    }
                }
            }
            ?>    
                <label id="upproflabel"><input type="file" name="profilepic" id="profilepicup"><p id="upavtext">Profielfoto uploaden</p></label>
                <input type='text' placeholder='Voornaam' name="voornaam" value="<?php echo @$_POST['voornaam']; ?>">
                <input type='text' placeholder='Familienaam' name="achternaam" value="<?php echo @$_POST['achternaam']; ?>">
                <input type='text' placeholder='Gemeente' name="gemeente" value="<?php echo @$_POST['gemeente']; ?>">
                <input type='email' placeholder='E-mail' name="e-mail" value="<?php echo @$_POST['e-mail']; ?>">
                <input type='password' placeholder='Wachtwoord' name="ww">
                <input type='password' placeholder='Herhaal wachtwoord' name="wwherhaal">
                <button type='submit' name='do_signup' id="main_registreer">Registreer</button>
            </form>
        </div>
    </main>
    <footer class='footer'>
        <p>&copy Igor Lee</p>
    </footer>
</body>
</html>