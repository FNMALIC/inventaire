
<?php

//php_spreadsheet_export.php

include '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

include '../functions.php';

$user_id = $_SESSION['user_session'];

$session = new USER;

// echo $user_id;

$query = "SELECT * FROM customers ORDER BY ID DESC";

$statement = $session->runQuery($query);

$statement->execute();

$result = $statement->fetchAll();
//  var_dump($result);
// echo $result;
if (isset($_POST["export"])) {

    if ($result != []) {
        $file = new Spreadsheet();

        $active_sheet = $file->getActiveSheet();

        $active_sheet->SetCellValue('A1', 'id');
        $active_sheet->SetCellValue('B1', 'Nom du client');
        $active_sheet->SetCellValue('C1', 'Numero de telephone');
        $active_sheet->SetCellValue('D1', 'Date');
        $count = 2;

        foreach ($result as $row) {
            // echo  $row["Nom_de_larticle"] ?? "hello";
            $active_sheet->setCellValue('A' . $count, $row["id"]);
            $active_sheet->setCellValue('B' . $count, $row["customer_name"]);
            $active_sheet->setCellValue('C' . $count, $row["customer_phone"]);
            $active_sheet->setCellValue('D' . $count, $row["Date"]);
            $count = $count + 1;
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, 'Csv');

        $file_name = time() . '.' . strtolower($_POST["file_type"]);

        $writer->save($file_name);

        header('Content-Type: application/x-www-form-urlencoded');

        header('Content-Transfer-Encoding: Binary');

        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");

        readfile($file_name);

        unlink($file_name);

        exit;
    } else {
        header("location:javascript://history.go(-1)");
    }

}
?>