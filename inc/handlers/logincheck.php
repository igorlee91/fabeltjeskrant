<?php
    session_start();
    require '../conn/dbconn.php';
    if (isset($_POST['do_login'])) {
        $result = R::getAll("SELECT * FROM `profielen` WHERE email=?",[$_POST['loginemail']]);
        if (empty($result)) {
            header("location:../../index.php?foutlogin=email");
        }elseif (!empty($result)) {
            foreach ($result as $key => $value) {
               if(!password_verify($_POST['loginwachtwoord'],$value['wachtwoord']) ){
                header("location:../../index.php?foutlogin=wachtwoord&lastemail=" . $_POST['loginemail']);
               }elseif(password_verify($_POST['loginwachtwoord'],$value['wachtwoord']) ){
                foreach ($result as $userassoc => $row) {
                    foreach ($row as $rowk => $rowv) {
                        if ($rowk!=='timejoined'&&$rowk!=='wachtwoord') {
                            $_SESSION[$rowk]=$rowv;
                            header("refresh:0;url=../userpage.php?aangemeld=ok");
                        }
                    }
                }
               }
            }
            
        }

    }
    
?>