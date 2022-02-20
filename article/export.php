
<?php

//php_spreadsheet_export.php

include '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

include '../functions.php';

$user_id = $_SESSION['user_session'];

$session = new USER;

// echo $user_id;

$query = "SELECT * FROM articles WHERE user_id = " . $user_id . "  ORDER BY ID DESC";

$statement = $session->runQuery($query);

$statement->execute();

$result = $statement->fetchAll();
//  var_dump($result);
// echo $result;
if (isset($_POST["export"])) {

    if ($result != []) {
        $file = new Spreadsheet();

        $active_sheet = $file->getActiveSheet();

        $active_sheet->SetCellValue('A1', 'ID');
        $active_sheet->SetCellValue('B1', 'Nom_de_larticle');
        $active_sheet->SetCellValue('C1', 'Description_de_larticle');
        $active_sheet->SetCellValue('D1', 'Emplacement');
        $active_sheet->SetCellValue('E1', 'Quantite');
        $active_sheet->SetCellValue('F1', 'Prix_unitaire');
        $active_sheet->SetCellValue('G1', 'Prix_total');
        $active_sheet->SetCellValue('H1', 'Fournisseur');
        $active_sheet->SetCellValue('I1', 'Date_dentre');

        $count = 2;

        foreach ($result as $row) {
            // echo  $row["Nom_de_larticle"] ?? "hello";
            $active_sheet->setCellValue('A' . $count, $row["ID"]);
            $active_sheet->setCellValue('B' . $count, $row["Nom_de_larticle"]);
            $active_sheet->setCellValue('C' . $count, $row["Description_de_larticle"]);
            $active_sheet->setCellValue('D' . $count, $row["Emplacement"]);
            $active_sheet->setCellValue('E' . $count, $row["N"]);
            $active_sheet->setCellValue('F' . $count, $row["Prix_unitaire"]);
            $active_sheet->setCellValue('G' . $count, $row["Prix_total"]);
            $active_sheet->setCellValue('H' . $count, $row["Fournisseur"]);
            $active_sheet->setCellValue('I' . $count, $row["Date_dentre"]);

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