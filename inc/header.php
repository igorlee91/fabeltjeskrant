<header class='header'>
        <div class='header__topbar__userpage'>  
            <div class="searchbar">
                <form method="GET">
                    <input list="content" type="text" placeholder="zoeken.." id="searchbar"> 
                        <datalist id='content'>                 
                        <?php
                            
                            $result = R::getAssoc("SELECT * FROM `profielen`");
                            if (!empty($result)) {
                                foreach ($result as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['voornaam']?> <?php echo $value['familienaam']?>"></option> 
                                        <?php
                                }
                                
                            }
                        ?>  
                        </datalist>  
                    <button type="submit" name="do_search" id="searchbtn">
                        <img id="zoom" src="css/img/logos/zoom.png" alt="zoeken">
                    </button>
                    <script>
                        document.querySelector('#searchbar').onchange=x=> {
                            if (x.target.value !== "") {
                                window.location = "./searchresults.php?do_search="+x.target.value;
                            }
                            
                        }
                        document.querySelector('#searchbtn').onclick=x=> {
                            document.querySelector('#searchbtn').value = document.querySelector('#searchbar').value;
                        }
                    </script>
                </form>
                
            </div>
            <div class="user_avatar">
                <a href="userpage.php"><?php echo $_SESSION['voornaam'];?></a>
                <?php
                echo "<img alt='css/img/logos/usericon.png' class='icon_profile' src=handlers/useravatars/".$_SESSION['avatar'].">";
                ?>
                <img id='bell' src="css/img/logos/bell.png" alt="belicon">
                <img id="hamburger" src="css/img/logos/hamburger.png" alt="menu gebruker">          
            </div>
            <script>
                document.querySelector(".icon_profile").onclick=  ()=>{
                    location.href="editprofile.php";
                }
            </script>
        </div>
        <!--------------------------------------------------UNDISPLAYED---------------------------------------------------->
        <div class="usermenu undisplayed">
            <div class="menu-container">
                <form action="editprofile.php">
                    <button type="submit" alt='Ga naar mijn profiel'>Wijzig profiel</button>
                </form>
                <form action="handlers/logout.php" method="POST">
                    <button type="submit" id='header_afmelden' alt='afmelden' name='do_logout'>Afmelden</button>
                </form>
            </div>
        </div> 
        <div class="notifmenu undisplayed">
            <div class='notif-container'>
            <?php
                $unaccepted_invites = R::getAll("SELECT * from `invites` where `bestemming_idcode`=? and `geaccepteerd`=0 and `geweigerd`=0",[$_SESSION['idcode']]);
                $accepted_invites = R::getAll("SELECT * from `invites` where `bestemming_idcode`=? and `geaccepteerd`=1",[$_SESSION['idcode']]);
                if (empty($unaccepted_invites)) {
                    echo "
                        <p class='notif-title'>Geen nieuwe meldingen</p>
                    ";
                }
                if (!empty($unaccepted_invites)) {
                    foreach ($unaccepted_invites as $key => $value) {
                        $aanvrager_avatar=R::getRow("SELECT `avatar` from `profielen` where `idcode`=?",[$value['aanvrager_idcode']]);
                        if (!$value['geaccepteerd']==1) {
                            echo "
                        <div class='notif-aanvraag'>
                            <img src=handlers/useravatars/". $aanvrager_avatar['avatar'] ." alt='css/img/logos/usericon.png' class='icon_profile'>
                            <p>".$value['aanvrager_voornaam']." ".$value['aanvrager_familienaam']."</p>
                            <form method='POST'>
                                <button type='submit' name=do_accept".$value['id'].">Aanvaarden</button>
                            </form>
                            <form method='POST'>
                                <button type='submit' name='do_decline'>Weiger</button>
                            </form>
                        </div>";
                        }
                        if (isset($_POST['do_accept'.$value['id']])) {
                            $accepted = R::load('invites',$value['id']);
                            $accepted -> geaccepteerd = 1;
                            R::store($accepted);
                        }
                        if (isset($_POST['do_decline'])) {
                            $declined = R::load('invites',$value['id']);
                            $declined -> geweigerd = 1;
                            R::store($declined);
                        }
                    }
                }
            ?>
            </div>  
        </div>    
    </header>