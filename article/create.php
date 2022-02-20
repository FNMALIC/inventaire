

<?php

// use function PHPSTORM_META\type;

include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
$msg = '';

$user_id = $_SESSION['user_session'];

// Check if POST data is not empty
if (!empty($_POST)) {

    // // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $name = $_POST['nom'];
    $fourni = $_POST['fourni'];
    $quantite = $_POST['nombre'];
    $prix = $_POST['Prix_u'];
    $desc = $_POST['desc'];
    $place = $_POST['emplace'];
    if ($name == "" || $fourni == "" || $quantite == "" || $prix == "" || $desc == "") {
        printf('<div class="alert alert-danger d-flex align-items-center" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
  <div>
    An example danger alert with an icon
  </div>
</div>');
    } else {
        $total = $prix * $quantite;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            $sql = "INSERT INTO articles (user_id,
                                            Nom_de_larticle ,
                                            Description_de_larticle ,
                                            Emplacement,
                                            N ,
                                            Prix_unitaire ,
                                            Prix_total,
                                            Fournisseur) VALUES (:var0,:var1,:var2,:var3,:var4,:var5,:var6, :var7)";

            $statement = $pdo->prepare($sql);

            $statement->execute(['var0' => $user_id, 'var1' => $name, 'var2' => $desc, 'var3' => $place, 'var4' => $quantite, 'var5' => $prix, 'var6' => $total, 'var7' => $fourni]);
        } catch (PDOException $exception) {
            echo "PDO error :" . $exception->getMessage();
        }

    }

}

?>
<?=template_header('Create')?>

<div class="container mt-5 update">
    <h2>Create Article</h2>
    <form action="create.php" method="post">


  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Nom de l'article</label>
    <input type="text" name="nom" class="form-control" id="inputEmail4" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Emplacement</label>
    <input type="text" name="emplace" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Fournisseur</label>
    <input type="text" name="fourni" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Quantite</label>
    <input type="number" id="quantite" name="nombre" min="0" oninput="add_number()" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Prix l'unite</label>
    <input type="number" id="prix" name="Prix_u" min="0" class="form-control" oninput="add_number()" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Total</label>
    <input type="text" name="Prix_t" id="total" class="form-control"  <?php echo "disabled" ?> value="0" >
  </div>


  <div class="col-md-12">
    <label for="inputPassword4" class="form-label">Description</label>
    <div class="form-floating">
  <textarea class="form-control" name="desc" placeholder="Description" id="floatingTextarea" required></textarea>
</div>
  </div>
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif;?>
</div>

<?=template_footer()?>

<script>

var text1 = document.getElementById("prix");
var text2 = document.getElementById("quantite");

function add_number() {
   var first_number = parseFloat(text1.value);
   if (isNaN(first_number)) first_number = 0;
   var second_number = parseFloat(text2.value);
   if (isNaN(second_number)) second_number = 0;
   var result = first_number * second_number;
   document.getElementById("total").value = result;
}
</script>