<?php
include '../functions.php';
include '../components/__header.php';

$user = new USER;
if (!$user->is_loggedin()) {$user->redirect('../login.php');}

$user_id = $_SESSION['user_session'];

$stmt = $user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id" => $user_id));
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
// Home Page template below.

// echo $user_id;
?>


<?=template_header('Piscine');?>





        <nav class="navtop container">
            <div>
                <?php
// if ($user->isSuper($user_id)) {print('<a href="create_p.php">Enregistre Piscine</a>');}
?>
            <a href="read_p.php">Table de Client</a>
            <a href="../logout.php">Logout</a>

            </div>
        </nav>
    <!-- </div> -->

<div class="content">
     <h2>Organisation de la piscine</h2>
    <p class="text-center">Bienvenu</p>
    <div class="container">
        <h1>Hello, <?php echo $userRow['user_name']; ?>!</h1>
        <hr/>
        <p  class="text-center">C'est une zone est prive</p>
    </div>
</div>

<?=template_footer();?>

