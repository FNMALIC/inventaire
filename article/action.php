<?php
require_once '../functions.php';
$user = new USER;
$user_id = $_SESSION['user_session'];

if (isset($_POST['query'])) {
    $inpText = $_POST['query'];
    $sql = 'SELECT Nom_de_larticle FROM articles WHERE user_id = ' . $user_id . ' AND Nom_de_larticle  LIKE :name';
    $stmt = $user->runQuery($sql);
    $stmt->execute(['name' => '%' . $inpText . '%']);
    $result = $stmt->fetchAll();

    if ($result) {
        foreach ($result as $row) {
            echo '<a href="#" class="list-group-item list-group-item-action border-1">' . $row['Nom_de_larticle'] . '</a>';
        }
    } else {
        echo '<p class="list-group-item border-1">No Record</p>';
    }
}
