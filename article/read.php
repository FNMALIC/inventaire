<?php
include '../functions.php';
include '../components/__header.php';

// create user instance
$session = new USER();
if (!$session->is_loggedin()) {$session->redirect('login.php');}
// Connect to MySQL database
$db = new Database;
$pdo = $db->pdo_connect_mysql();

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 10;

//create user seasion
$user_id = $_SESSION['user_session'];

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = "SELECT * FROM articles WHERE user_id = '" . $user_id . "' ORDER BY ID DESC LIMIT :current_page, :record_per_page";
$stmt = $session->runQuery($sql);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_articles = $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
$num = $num_articles;
?>



<?=template_header('Read')?>

<div class="container mt-3 read">
    <h2>Read Article</h2>
    <span id="message"></span>

    <div class="row mt-4" style="align-items: center;">
    <div class="col-md-7">
        <div class="row"  style="align-items: center;">
                <div class="col-3">
                    <a href="create.php" class="create-contact">Create Article</a>
                </div>
                <div class="col-3">
                    <form method="post" action="export.php">
                        <div class="input-group ">
                        <input type="submit" name="export" class="btn btn-primary btn-sm" value="Export" />
                        <select class="form-select" name="file_type" class="form-control input-sm">
                            <option value="Xls">Xls</option>
                            <option value="Csv">Csv</option>
                        </select>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <form method="post" id="import_excel_form" enctype="multipart/form-data">
                    <div class="input-group ">
                            <input type="submit" name="import" id="import" class="btn btn-primary" value="Import" />
                            <input class="form-control" type="file" id="formFileMultiple"  name="import_excel" >
                    </div>
                    </form>
                </div>
        </div>
     </div>
     <div class="search col-md-5">
         <div class="container">
             <div class="row">
                <div class=" col-12 mx-auto rounded p-2">
                    <form action="details.php" method="post" class="p-1">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control form-control-lg rounded-0 border-info" placeholder="Search..." autocomplete="off" required>
                        <div class="input-group-append">
                        <input type="submit" name="submit" value="Search" class="btn btn-info btn-lg rounded-0">
                        </div>
                    </div>
                    </form>
                </div>
             </div>
             <div class="row">
                <div class="col-md-4" style="position: absolute;"   >
                    <div class="list-group" id="show-list">
                    <!-- Here autocomplete list will be display -->
                    </div>
                </div>
             </div>
         </div>
     </div>
     </div>
    <table>
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
foreach ($articles as $article):
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
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="read.php?page=<?=$page - 1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif;?>
        <?php if ($page * $records_per_page < $num_articles): ?>
            <a href="read.php?page=<?=$page + 1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif;?>
    </div>
</div>


<?=template_footer()?>
<script>
$(document).ready(function(){
  $('#import_excel_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"import.php",
      method:"POST",
      data:new FormData(this),
      contentType:false,
      cache:false,
      processData:false,
      beforeSend:function(){
        $('#import').attr('disabled', 'disabled');
        $('#import').val('Importing...');
      },
      success:function(data)
      {
        $('#message').html(data);
        $('#import_excel_form')[0].reset();
        $('#import').attr('disabled', false);
        $('#import').val('Import');
      }
    })
  });
});
</script>