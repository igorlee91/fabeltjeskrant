<?php
session_start();
include "./conn/dbconn.php";

?>
<!DOCTYPE html>
<html lang="NL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabeltjeskrant</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/index.css">
    <script src="./js/index.js"></script>
</head>
<body class='body'>
    <?php 
        include_once "header.php";
    ?>
    <main class="main">
        <?php
            if (isset($_GET['do_search'])) {
                //bring out resultspage for all matches found with links to profiles
                $d=$_GET['do_search'];
                if ($d == trim($d) && strpos($d, ' ') !== false) {
                    //echo 'has spaces, but not at beginning or end';
                    $arnames = array();
                    $n = $_GET['do_search'];
                    $ns = explode(" ",$n);
                    foreach ($ns as $key => $value) {
                        $arnames[$key]=$value;
                    }
                    $vn= $arnames[0];
                    $fn=$arnames[1];
                    $res = R::getAssoc("SELECT * from `profielen` where `voornaam`=? or `familienaam`=?",[$vn,$fn]);
                    if (!empty($res)) {
                        foreach ($res as $key => $value) {
                            echo "<div class='wrapper-results'>
                            <a href=user.php?v=".$value['idcode'].">".$value['voornaam']." ". $value['familienaam']."</a>
                            </div>";
                        }
                    }elseif(empty($res)) {
                        echo "user not found";
                    }
                }else{
                    $sres = R::getAssoc("SELECT * from `profielen` where voornaam like ? or familienaam like ?",[$_GET['do_search'],$_GET['do_search']]);
                    if (!empty($sres)) {
                        foreach ($sres as $key => $value) {
                            echo "<div class='wrapper-results'>
                            <a href=user.php?v=".$value['idcode'].">".$value['voornaam']." ". $value['familienaam']."</a>
                            </div>";
                        }
                    }elseif(empty($sres)) {
                        echo "user not found";
                    }
                }
            }
        ?>
    </main>