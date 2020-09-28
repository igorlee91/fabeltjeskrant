<?php
    session_start();
    include_once 'conn/dbconn.php';
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
    <main class='main-editprofile'>
        <div class='main__formwrapper'>
            <form class='main__signupform' method="POST" enctype="multipart/form-data">
                <?php
                    date_default_timezone_set('Europe/Paris');
                    $fouten=array();
                    //fouten handlers
                    if (isset($_POST['do_opslaan'])||isset($_POST['do_wijzig'])) {
                        foreach ($_POST as $key => $value) {
                            if (empty(trim($value))) {
                                if ($key!=='do_opslaan'&&$key!=='do_wijzig'&&$key!=='ww'&&$key!=='wwherhaal') {
                                    $fouten[$key]="<label><p>leeg veld:" . $key . "</p></label><br>";
                                }
                            }
                        }
                        //added
                        if (isset($_POST['do_wijzig'])) {
                            foreach ($_POST as $key => $value) {
                                if ($key=='ww'||$key='wwherhaal') {
                                    if ($key=='ww') {
                                        if (strlen($value)<6) {
                                            $fouten[$key]="<label><p>Uw wachtwoord moet minstens 6 tekens zijn</p></label><br>";
                                        }
                                    }  
                                }
                            }
                            if ($_POST['ww']!==$_POST['wwherhaal']) {
                                $fouten[$key]="<label><p>Uw wachtwoord komt niet overheen</p></label><br>";
                            }
                        }
                        //added^
                        if (isset($fouten)) {
                            foreach ($_POST as $kpost => $vpost) {
                                if ($kpost!=='do_signup') {
                                    if (isset($fouten[$kpost])) {
                                        echo $fouten[$kpost];
                                    }
                                }                      
                            }
                        }
                        //moved^   
                        if (empty($fouten)) {
                            
                            //update database table
                            $userav = R::load('profielen',$_SESSION['id']);
                            $userav -> voornaam = $_POST['voornaam'];
                            $userav -> familienaam = $_POST['achternaam'];
                            $userav -> gemeente = $_POST['gemeente'];
                            $userav -> email = $_POST['email'];
                            if ($_FILES['avatar']['size']>0) {
                                $userav->avatar=$_FILES['avatar']['name'];
                                move_uploaded_file($_FILES['avatar']['tmp_name'],'handlers/useravatars/'.$_FILES['avatar']['name']);
                                $_SESSION['avatar']=$_FILES['avatar']['name'];
                            }
                            if (isset($_POST['do_wijzig'])) {
                                $hash = password_hash($_POST['ww'],PASSWORD_DEFAULT);
                                $userav -> wachtwoord = $hash;
                            }
                            R::store($userav);
                            //update $_SESSION values
                            $_SESSION['voornaam']= $_POST['voornaam'];
                            $_SESSION['familienaam']=$_POST['achternaam'];
                            $_SESSION['gemeente'] = $_POST['gemeente'];
                            $_SESSION['email'] = $_POST['email'];
                            header("refresh:1;url=userpage.php?wijzig=ok");
                        }
                    }
                    
                    /*
                    if (isset($_POST['do_opslaan'])) {
                        if (empty($fouten)) {
                            //update database table
                            $userav = R::load('profielen',$_SESSION['id']);
                            $userav -> voornaam = $_POST['voornaam'];
                            $userav -> familienaam = $_POST['achternaam'];
                            $userav -> gemeente = $_POST['gemeente'];
                            $userav -> email = $_POST['email'];
                            if ($_FILES['avatar']['size']>0) {
                                $userav->avatar=$_FILES['avatar']['name'];
                                $_SESSION['avatar']=$_FILES['avatar']['name'];
                                move_uploaded_file($_FILES['avatar']['tmp_name'],'handlers/useravatars/'.$_FILES['avatar']['name']);
                            }
                            R::store($userav);
                            //update $_SESSION values
                            $_SESSION['voornaam']= $_POST['voornaam'];
                            $_SESSION['familienaam']=$_POST['achternaam'];
                            $_SESSION['gemeente'] = $_POST['gemeente'];
                            $_SESSION['email'] = $_POST['email'];
                            header("refresh:1;url=userpage.php?wijzig=ok");
                        }
                    }  
                    if (isset($_POST['do_wijzig'])) {
                        foreach ($_POST as $key => $value) {
                            if ($key=='ww'||$key='wwherhaal') {
                                if ($key=='ww') {
                                    if (strlen($value)<6) {
                                        $fouten[$key]="<label><p>Uw wachtwoord moet minstens 6 tekens zijn</p></label><br>";
                                    }
                                }  
                            }
                        }
                        if ($_POST['ww']!==$_POST['wwherhaal']) {
                            $fouten[$key]="<label><p>Uw wachtwoord komt niet overheen</p></label><br>";
                        }
                        if (empty($fouten)) {
                            $hash = password_hash($_POST['ww'],PASSWORD_DEFAULT);
                            //update database table
                            $userav = R::load('profielen',$_SESSION['id']);
                            $userav -> voornaam = $_POST['voornaam'];
                            $userav -> familienaam = $_POST['achternaam'];
                            $userav -> gemeente = $_POST['gemeente'];
                            $userav -> email = $_POST['email'];
                            $userav -> wachtwoord = $hash;
                            if ($_FILES['avatar']['size']>0) {
                                $userav->avatar=$_FILES['avatar']['name'];
                                move_uploaded_file($_FILES['avatar']['tmp_name'],'handlers/useravatars/'.$_FILES['avatar']['name']);
                                $_SESSION['avatar']=$_FILES['avatar']['name'];
                            }
                            R::store($userav);
                            //update $_SESSION values
                            $_SESSION['voornaam']= $_POST['voornaam'];
                            $_SESSION['familienaam']=$_POST['achternaam'];
                            $_SESSION['gemeente'] = $_POST['gemeente'];
                            $_SESSION['email'] = $_POST['email'];
                            header("refresh:1;url=userpage.php?wijzig=ok");
                        }
                    }*/
                    
                ?>
                <label id="upavlabel"><input type="file" name="avatar" id="upav"><p id="upavtext">Profielfoto wijzigen</p></label>
                <input type='text' placeholder='Voornaam' name="voornaam" value="<?php echo @$_SESSION['voornaam']; ?>">
                <input type='text' placeholder='Familienaam' name="achternaam" value="<?php echo @$_SESSION['familienaam']; ?>">
                <input type='text' placeholder='Gemeente' name="gemeente" value="<?php echo @$_SESSION['gemeente']; ?>">
                <input type='email' placeholder='E-mail' name="email" value="<?php echo @$_SESSION['email']; ?>">
                <?php 
                    if (!isset($_GET['do_ww'])||isset($_GET['ga_terug'])) {
                        echo "<button type='submit' name='do_opslaan' class='main_opslaan'>Opslaan</button>
                        </form>
                            <form class='main__signupform'>
                                    <button class='wijzigknop' type='submit' name='do_ww'>Wachtwoord wijzigen</button>
                            </form>
                        ";
                    }elseif (isset($_GET['do_ww'])) {
                        echo "
                            <input type='password' placeholder='Nieuw wachtwoord' name='ww'>
                            <input type='password' placeholder='Herhaal nieuw wachtwoord' name='wwherhaal'>
                            <button type='submit' name='do_wijzig' class='main_opslaan'>Opslaan</button>
                        </form>
                        <div class='main__signupform'>
                            <form>
                                <button class='wijzigknop' type='submit' name='ga_terug'>Annuleer</button>
                            </form>
                        </div>
                        ";
                    }
                ?>
                
        </div>
    </main>
    
    <footer class='footer'>
        <p>&copy Igor Lee</p>
    </footer>
</body>
</html>