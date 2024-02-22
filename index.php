<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = 'uploads/';
    $mergedFileName = 'merged_file.csv';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['files']['error'] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['files']['tmp_name'][$key];
            $name = basename($_FILES['files']['name'][$key]);
            $destination = $uploadDir.$name;

            move_uploaded_file($tmp_name, $destination);
        }
    }

    $csvFiles = glob($uploadDir.'*.csv');

    if (!empty($csvFiles)) {
        $mergedContent = '';

        foreach ($csvFiles as $file) {
            $content = file_get_contents($file);

            if ($mergedContent != '') {
                $lines = explode("\n", $content);
                unset($lines[0]);
                $content = implode("\n", $lines);
            }

            $mergedContent .= $content;
        }

        file_put_contents($uploadDir.$mergedFileName, $mergedContent);

        echo 'Files uploaded and merged successfully!';
    } else {
        echo 'No files were uploaded.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV File Upload and Merge</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="files">Select CSV files to upload:</label>
        <input type="file" name="files[]" id="files" multiple accept=".csv">
        <br>
        <button type="submit">Upload and Merge</button>
    </form>
</body>
</html>