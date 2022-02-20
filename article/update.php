<?php
// include filss
include '../functions.php';
include '../components/__header.php';

$db = new Database;
$pdo = $db->pdo_connect_mysql();
$msg = '';
// Check if the article id exists, for example update.php?id=1 will get the article with the id of 1
if (isset($_GET['id'])) {

    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $name = $_POST['nom'];
        $fourni = $_POST['fourni'];
        $quantite = $_POST['nombre'];
        $prix = $_POST['Prix_u'];
        $desc = $_POST['desc'];
        $place = $_POST['emplace'];
        // Update the record
        $total = $prix * $quantite;
        $sql = " UPDATE articles SET Nom_de_larticle = :var1 ,
                                    Description_de_larticle = :var2,
                                    Emplacement = :var8,
                                    N = :var3,
                                    Prix_unitaire = :var4,
                                    Prix_total = :var5,
                                    Fournisseur = :var6
                                    WHERE id = :var7";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['var1' => $name, 'var2' => $desc, 'var3' => $quantite, 'var4' => $prix, 'var5' => $total, 'var6' => $fourni, 'var7' => $_GET['id'], 'var8' => $place]);
        $msg = 'Updated Successfully!';
        header("Refresh:0; url=read.php");
    }
    // Get the article from the articles table
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$article) {
        exit('article doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="container mt-5 update">
    <h2>Update article #<?=$article['ID']?></h2>
    <form action="update.php?id=<?=$article['ID']?>" method="post">

    <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Nom de l'article</label>
    <input type="text" name="nom" class="form-control" id="inputEmail4" value="<?=$article['Nom_de_larticle']?>" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Emplacement</label>
    <input type="text" name="emplace" class="form-control" value="<?=$article['Emplacement']?>" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Fournisseur</label>
    <input type="text" name="fourni" class="form-control" value="<?=$article['Fournisseur']?>" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Quantite</label>
    <input type="number" id="quantite" name="nombre" min="0"  class="form-control" oninput="add_number()"  value="<?=$article['N']?>" required>
  </div>

<div class="col-md-6">
    <label for="inputPassword4" class="form-label">Prix l'unite</label>
    <input type="number" id="prix" name="Prix_u" min="0" class="form-control" oninput="add_number()" value="<?=$article['Prix_unitaire']?>" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Total</label>
    <input type="text" id="total" name="Prix_t" class="form-control"  <?php echo "disabled" ?> value="<?=$article['Prix_total']?>" >
  </div>


  <div class="col-md-12">
    <label for="inputPassword4" class="form-label">Description</label>
    <div class="form-floating">
  <textarea class="form-control" name="desc" cols="30" rows="30"    >
    <?php echo $article['Description_de_larticle'] ?>
  </textarea>

</div>
  </div>
        <input type="submit" value="Update">

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