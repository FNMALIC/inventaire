<?php

// include filss
include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    $sql = "SELECT * FROM `customers` WHERE customer_name = :var1";

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
            echo $article['customer_name'];
            $sql = 'DELETE FROM customers WHERE customer_name="' . $article['customer_name'] . '"';
            $statement = $pdo->prepare($sql);
            $statement->execute();
            var_dump($statement);
            $msg = 'You have deleted the article!';
            // sleep(3);
            // header('Location: read_p.php');
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read_p.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
        <h2>Supprime completement la presence du client  #<?=$article['customer_name']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php else: ?>

        <div class="yesno">
            <a href="reduce.php?id=<?=$article['customer_name']?>&confirm=yes">Yes</a>
            <a href="reduce.php?id=<?=$article['customer_name']?>&confirm=no">No</a>
        </div>
    <?php endif;?>
</div>

<?=template_footer()?>