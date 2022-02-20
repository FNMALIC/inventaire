<?php

//import.php

include '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Worksheet\Row;

include '../functions.php';

$session = new USER;

// $db = new Database;
// $pdo = $db->pdo_connect_mysql();

$user_id = $_SESSION['user_session'];

if ($_FILES["import_excel"]["name"] != '') {
    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["import_excel"]["name"]);
    $file_extension = end($file_array);

    if (in_array($file_extension, $allowed_extension)) {
        $file_name = time() . '.' . $file_extension;
        move_uploaded_file($_FILES['import_excel']['tmp_name'], $file_name);
        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

        $spreadsheet = $reader->load($file_name);

        unlink($file_name);

        $data = $spreadsheet->getActiveSheet()->toArray();

        $i = 1;
        foreach ($data as $row => $key) {

            // echo var_dump($key);

            if ($i >= 3) {
                $sql = "INSERT INTO articles (user_id,
                                          Nom_de_larticle ,
                                          Description_de_larticle ,
                                          Emplacement,
                                          N ,
                                          Prix_unitaire ,
                                          Prix_total,
                                          Fournisseur) VALUES (:var0,:var1,:var2,:var3,:var4,:var5,:var6, :var7)";

                $statement = $session->runQuery($sql);

                $statement->execute(['var0' => $user_id, 'var1' => $key[1], 'var2' => $key[2], 'var3' => $key[3], 'var4' => $key[4], 'var5' => $key[5], 'var6' => $key[6], 'var7' => $key[7]]);
            }

            $i = $i + 1;
        }

        $message = '<div class="alert alert-success">Data Imported Successfully</div>';

    } else {
        $message = '<div class="alert alert-danger">Only .xls .csv or .xlsx file allowed</div>';
    }
} else {
    $message = '<div class="alert alert-danger">Please Select File</div>';
}

// header("Refresh:0");

// $page = $_SERVER['PHP_SELF'];
// $sec = "3";
// header("Refresh: $sec; url=$page");
echo $message;
