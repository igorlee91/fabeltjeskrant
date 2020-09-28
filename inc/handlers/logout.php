<?php
    if (isset($_POST['do_logout'])) {
        session_start();
        session_destroy();
    header("refresh:1;url=../../index.php?afgemeld=ja");
    }
?>