<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversion Successful</title>
    <!-- Materialize CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .success-icon {
            font-size: 50px;
            color: #4caf50;
        }
        .error-icon {
            font-size: 50px;
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jsonFile'])) {
            // Check if a file was uploaded and is not empty
            if (!empty($_FILES['jsonFile']['tmp_name']) && is_uploaded_file($_FILES['jsonFile']['tmp_name'])) {
                $uploadedFile = $_FILES['jsonFile']['tmp_name'];
                $jsonContent = file_get_contents($uploadedFile);

                // Attempt to decode JSON
                $data = json_decode($jsonContent, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    // Convert JSON data to the new format
                    $convertedData = [];
                    foreach ($data as $item) {
                        if (isset($item['question'], $item['answer1'], $item['answer2'], $item['answer3'], $item['answer4'], $item['correct'])) {
                            $convertedData[] = [
                                "question" => $item['question'],
                                "options" => [
                                    "ক. " . $item['answer1'],
                                    "খ. " . $item['answer2'],
                                    "গ. " . $item['answer3'],
                                    "ঘ. " . $item['answer4'],
                                ],
                                "correctAnswerIndex" => array_search(strtolower($item['correct']), ['a', 'b', 'c', 'd'])
                            ];
                        }
                    }

                    // Save the converted data to a new file
                    $outputFile = 'converted_data.json';
                    if (file_put_contents($outputFile, json_encode($convertedData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
                        // Display success message and download link
                        echo "<i class='material-icons success-icon'>check_circle</i>";
                        echo "<h4>Conversion Successful!</h4>";
                        echo "<p>Your file has been converted successfully. You can download the result below or convert another file.</p>";
                        echo "<a href='$outputFile' class='btn waves-effect waves-light green' download>Download Converted JSON</a>";
                        echo "<br><br>";
                        echo "<a href='index.html' class='btn waves-effect waves-light blue'>Convert Another</a>";
                    } else {
                        // File saving error
                        echo "<i class='material-icons error-icon'>error</i>";
                        echo "<h4>Conversion Failed!</h4>";
                        echo "<p>There was an issue saving the converted file. Please try again.</p>";
                        echo "<a href='index.html' class='btn waves-effect waves-light blue'>Try Again</a>";
                    }
                } else {
                    // Invalid JSON format
                    echo "<i class='material-icons error-icon'>error</i>";
                    echo "<h4>Invalid JSON File!</h4>";
                    echo "<p>The uploaded file does not contain valid JSON data. Please check the file and try again.</p>";
                    echo "<a href='index.html' class='btn waves-effect waves-light blue'>Try Again</a>";
                }
            } else {
                // No file uploaded
                echo "<i class='material-icons error-icon'>error</i>";
                echo "<h4>No File Uploaded!</h4>";
                echo "<p>Please upload a JSON file to proceed with the conversion.</p>";
                echo "<a href='index.html' class='btn waves-effect waves-light blue'>Try Again</a>";
            }
        } else {
            // Invalid request
            echo "<i class='material-icons error-icon'>error</i>";
            echo "<h4>Invalid Request!</h4>";
            echo "<p>This page is only accessible via a file upload form. Please use the upload form to proceed.</p>";
            echo "<a href='index.html' class='btn waves-effect waves-light blue'>Go to Upload Form</a>";
        }
        ?>
    </div>
    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
