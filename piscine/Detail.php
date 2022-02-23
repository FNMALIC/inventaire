<?php

// include filss
include '../functions.php';
include '../components/__header.php';

$session = new USER();


$db = new Database;
$pdo = $db->pdo_connect_mysql();
// echo $_POST['search'];
if (isset($_POST['submit'])) {
    $Nom_de_larticle = $_POST['search'];

    // $sql = 'SELECT * FROM customers  ORDER BY Date DESC';
    $sql = 'SELECT customer_name,customer_phone, COUNT(*) AS CountOf FROM customers WHERE customer_name = :name GROUP BY customer_name,customer_phone HAVING COUNT(*) >= 1 ORDER BY Date DESC';
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

        <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Nom du client</td>
                <td>Numero de telephone</td>
                <td>Presence</td>
                <td>Date</td>
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
                    <td><?=$article['customer_name']?></td>
                    <td><?=$article['customer_phone']?></td>
                    <td><?=$article['CountOf']?></td>
                    <td> <div class="list-group"><?php
$sql = "SELECT Date FROM customers WHERE customer_name =
         '" . $article['customer_name'] . "'
         ORDER BY Date DESC";
$stmt = $session->runQuery($sql);
$stmt->execute();

$s = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo $s;
foreach ($s as $key => $value) {
    foreach ($value as $val) {
        print('

            <a class="list-group-item list-group-item-action" href="delete_D.php?date=' . $val . '">' . $val . '</a>

        ');
    }
}
?></div></td>
                    <td class="actions text-center" >
                        <a href="add.php?id=<?=$article['customer_name']?>" class="edit"><i class="fas fa-plus"></i></a>
                        <a href="reduce.php?id=<?=$article['customer_name']?>" class="trash"><i class="fas fa-minus"></i></a>
                    </td>
                </tr>
            <?php $i++;endforeach;?>
        </tbody>
    </table>
</div>




<?=template_footer()?>
