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
$sql = "SELECT customer_name,customer_phone, COUNT(*) AS CountOf FROM customers GROUP BY customer_name,customer_phone HAVING COUNT(*) >= 1 ORDER BY Date DESC";
$stmt = $session->runQuery($sql);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_articles = $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$num = $num_articles;
?>



<?=template_header('Read')?>

<div class="container mt-3 read">
    <h2>List de client</h2>
    <span id="message"></span>

    <div class="row mt-4" style="align-items: center;">
    <div class="col-md-7">
        <div class="row"  style="align-items: center;">
                <div class="col-3">
                    <a href="client_p.php" class="create-contact">Enregistre Client</a>
                </div>
                <div class="col-3">
                    <form method="post" action="export_p.php">
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
                    <form action="Detail.php" method="post" class="p-1">
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
foreach ($customers as $article):
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
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="read_p.php?page=<?=$page - 1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif;?>
        <?php if ($page * $records_per_page < $num_articles): ?>
            <a href="read_p.php?page=<?=$page + 1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif;?>
    </div>
</div>


<?=template_footer()?>
<script>
$(document).ready(function(){
  $('#import_excel_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"import_p.php",
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
  $("#search").keyup(function () {
    let searchText = $(this).val();
    if (searchText != "") {
      $.ajax({
        url: "action.php",
        method: "post",
        data: {
          query: searchText,
        },
        success: function (response) {
          $("#show-list").html(response);
        },
      });
    } else {
      $("#show-list").html("");
    }
  });
  // Set searched text in input field on click of search button
  $(document).on("click", "a", function () {
    $("#search").val($(this).text());
    $("#show-list").html("");
  });
});
</script>