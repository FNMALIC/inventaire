<?php

// include filss
include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
// echo $_POST['search'];
if (isset($_POST['submit'])) {
    $Nom_de_larticle = $_POST['search'];

    $sql = 'SELECT * FROM articles WHERE Nom_de_larticle = :name ORDER BY Date_dentre DESC';

    $stmt = $pdo->prepare($sql);

    $stmt->execute(['name' => $Nom_de_larticle]);

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($row);
} else {
    header('location: .');
    exit();
}
?>
<?=template_header('Read')?>
<div class="container mt-5 read">
    <h2> Les article trouve</h2>

     <table class="mt-3">
        <thead>
            <tr>
                <td>#</td>
                <td>Nom de l'article</td>
                <td>Description</td>
                <td>Emplacement</td>
                <td>N#</td>
                <td>Prix Unitaire</td>
                <td>Prix Total</td>
                <td>Founisseur</td>
                <td>Date d'enter</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php
$i = 1;
foreach ($row as $article):
?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$article['Nom_de_larticle']?></td>
                    <td><?=$article['Description_de_larticle']?></td>
                    <td><?=$article['Emplacement']?></td>
                    <td><?=$article['N']?></td>
                    <td><?=$article['Prix_unitaire']?></td>
                    <td><?=$article['Prix_total']?></td>
                    <td><?=$article['Fournisseur']?></td>
                    <td><?=$article['Date_dentre']?></td>
                    <td class="actions">
                        <a href="update.php?id=<?=$article['ID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                        <a href="delete.php?id=<?=$article['ID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php $i++;endforeach;?>
        </tbody>
    </table>
</div>




<?=template_footer()?>
