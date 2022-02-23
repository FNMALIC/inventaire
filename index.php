<?php
include 'functions.php';
include 'components/__header.php';

$session = new USER();
if (!$session->is_loggedin()) {$session->redirect('login.php');}

// $auth_user = new USER();
$user_id = $_SESSION['user_session'];

$stmt = $session->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id" => $user_id));
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
// Home Page template below.
?>

<?=template_header('Home', ' ')?>

<nav class="navtop container">
    <div>

        <a href="article/read.php">Articles</a>

        <a href="logout.php">Logout</a>

        <?php
if ($session->isSuper($user_id)) {
    echo "<a href='createNUser.php'>Register</a>";
}
?>
    </div>
</nav>
<div class="content">
    <h2>Gestion des Articel</h2>
    <p class="text-center">Bienvenu</p>
    <div class="container">
        <h1>Hello, <?php echo $userRow['user_name']; ?>!</h1>
        <hr/>
        <p class="text-center">C'est une zone est prive</p>
    </div>
</div>

<?=template_footer()?>