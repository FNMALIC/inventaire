<?php

// include filss
include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM `articles` WHERE ID = :var1";

    // Select the record that is going to be deleted
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['var1' => $_GET['id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        exit('Article doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM articles WHERE ID = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the article!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete Article #<?=$article['ID']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php else: ?>
        <p>Are you sure you want to delete  #<?=$article['ID']?>?</p>
        <div class="yesno">
            <a href="delete.php?id=<?=$article['ID']?>&confirm=yes">Yes</a>
            <a href="delete.php?id=<?=$article['ID']?>&confirm=no">No</a>
        </div>
    <?php endif;?>
</div>

<?=template_footer()?>