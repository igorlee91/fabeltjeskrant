<?php
    session_start();
    if (isset($_SESSION['idcode'])) {
        header("location: inc/userpage.php?redirect=isback");
    }
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
            <form action='registratie.php'>
            <button id='header_registreer' alt='Registreer'>Registreer</button>
            </form>
            <form action='inc/handlers/logincheck.php' class='header__topbar__end' method='POST'>
            <?php
                if (isset($_GET['foutlogin'])) {
                    if ($_GET['foutlogin']=='email') {
                        echo "<input class='errorlogin' type='text' name='loginemail' required placeholder='Onbestaand e-mailadres' id='header_login' alt='voer uw login of email in'>";
                        echo "<input type='password' name='loginwachtwoord' required placeholder='wachtwoord' id='header_wachtwoord' alt='voer uw wachtwoord in'>";
                    }
                    if ($_GET['foutlogin']=='wachtwoord') {

                        echo "<input type='text' name='loginemail' required placeholder='e-mail' id='header_login' alt='voer uw login of email in' value=" . $_GET['lastemail'] . ">"; 
                        echo "<input class='errorlogin' type='password' name='loginwachtwoord' required placeholder='Ongeldig wachtwoord' id='header_wachtwoord' alt='voer uw wachtwoord in'>";
                    }
                }
                elseif (!isset($_GET['foutlogin'])) {
                    echo "<input type='text' name='loginemail' required placeholder='e-mail' id='header_login' alt='voer uw login of email in'>";
                    echo "<input type='password' name='loginwachtwoord' required placeholder='wachtwoord' id='header_wachtwoord' alt='voer uw wachtwoord in'>";
                }
            ?>
                <button type='submit' name='do_login' alt='knop Aanmelden' id='header_aanmelden'>Aanmelden</button>
            </form>
        </div>
    </header>
    <main class='main'>
    </main>
    <footer class='footer'>
        <p>&copy Igor Lee</p>
    </footer>
</body>
</html>