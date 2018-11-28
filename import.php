<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Import</title>
    </head>
    <body>
        <form method="post" enctype="multipart/form-data" action="import.php">
            <div class="form-group">
                <label for="exampleInputFile">File Upload</label>
                <input type="file" name="file" class="form-control" id="exampleInputFile">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php
        require 'app/db/connection.php';
        
        //load spreadsheet functions
        require 'libraries/autoload.php';

        //Allowed file types
        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');


        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes))
        {

            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension)
            {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            }
            else
            {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $idxStamnr;
            $idxKlas;
            $idxRoepnaam;
            $idxTussenv;
            $idxAchternaam;

            //Find column indexes
            foreach ($sheetData[0] as $key => $value)
            {
                switch ($value)
                {
                    case 'Stamnr':
                        $idxStamnr = $key;
                        break;
                    case 'Klas':
                        $idxKlas = $key;
                        break;
                    case 'Roepnaam':
                        $idxRoepnaam = $key;
                        break;
                    case 'Tussenv':
                        $idxTussenv = $key;
                        break;
                    case 'Achternaam':
                        $idxAchternaam = $key;
                        break;
                }
            }

            //Loop the data
            foreach (array_slice($sheetData, 1) as $key => $value)
            {
                $stamnr = $value[$idxStamnr];
                $klas = $value[$idxKlas];
                $roepnaam = $value[$idxRoepnaam];
                $tussenv = $value[$idxTussenv];
                $achternaam = $value[$idxAchternaam];
               
                $stmt = $connection->prepare("INSERT INTO student (studentnumber, firstname, surname_prefix, surname, class_id) VALUES (?, ?, ?, ?, (SELECT id FROM class WHERE class = ?)) ;");
                $stmt->bind_param("sssss", $stamnr, $roepnaam, $tussenv, $achternaam, $klas);
                             
                if ($stmt->execute() == false)
                {
                    print($connection->error);
                }
            }
        }
        ?>
    </body>
</html>
