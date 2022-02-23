<?php

include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
$msg = '';

if (!empty($_POST)) {

    $nom = $_POST['nom_p'];
    $localisation = $_POST['localisation'];

    if ($nom == "" || $localisation == "") {
        printf('<div class="alert alert-danger d-flex align-items-center" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
  <div>
    An example danger alert with an icon
  </div>
</div>');
    } else {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            $sql = "INSERT INTO piscine (piscine_name,localisation) VALUES (:var0,:var1)";
            $statement = $pdo->prepare($sql);

            $statement->execute(['var0' => $nom, 'var1' => $localisation]);
        } catch (PDOException $exception) {
            echo "PDO error :" . $exception->getMessage();
        }

    }
}

?>

<?=template_header('Enreg._P');?>

<div class="container mt-5 update">
    <h2>Enregistre Piscine</h2>
    <form action="create_p.php" method="post">

  <div class="col-md-6 col-sm-12">
    <label for="inputEmail4" class="form-label">Nom de Piscine</label>
    <input type="text" name="nom_p" class="form-control" id="inputEmail4" required>
  </div>
  <div class="col-md-6 col-sm-12">
    <label for="inputPassword4" class="form-label">Localisation</label>
    <input type="text" name="localisation" class="form-control" required>
  </div>


    <input type="submit"  value="Enregistre">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif;?>
</div>



<?=template_footer();?>
