<?php
include 'functions.php';
include 'components/__header.php';

$session = new USER();
if (!$session->is_loggedin()) {$session->redirect('login.php');}

$auth_user = new USER();
$user_id = $_SESSION['user_session'];
$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id" => $user_id));
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
// Home Page template below.
?>

<?=template_header('Home', ' ')?>
    <?php
$super = $auth_user->runQuery("SELECT Super_admin FROM users WHERE user_id=:user_id");
$super->execute(array("user_id" => $user_id));
$use = $super->fetch(PDO::FETCH_ASSOC);
// var_dump($use);

?>
<nav class="navtop container">
    <div>

        <!-- <a href="index.php">Home</a> -->
        <!-- <a href="read.php">Articles</a> -->
        <a href="logout.php">Logout</a>
        <?php
// if ($use != 1) {
// echo $use;
foreach ($use as $key) {
    if ($key == 1) {
        echo "<a href='createNUser.php'>Register</a>";
    }
}
//  }
?>
    </div>
</nav>
<div class="content">
    <h2>Home</h2>
    <p>Welcome to the home page!</p>
    <div class="container">
        <h1>Hello, <?php echo $userRow['user_name']; ?>!</h1>
        <hr/>
        <p>This is the  area, this content is private.</p>
    </div>
</div>

<?=template_footer()?>