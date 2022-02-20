<?php
 include 'functions.php';

 $user_logout = new USER();
 $user_logout->doLogout();
 $user_logout->redirect('index.php');
?>