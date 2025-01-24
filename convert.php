<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jsonFile'])) {
    // আপলোড করা ফাইল প্রক্রিয়াকরণ
    $uploadedFile = $_FILES['jsonFile']['tmp_name'];
    $jsonContent = file_get_contents($uploadedFile);

    // JSON ডাটা পার্স করা
    $data = json_decode($jsonContent, true);

    // নতুন ফরম্যাটে ডাটা রূপান্তর
    $convertedData = [];
    foreach ($data as $item) {
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

    // রূপান্তরিত ডাটাকে নতুন ফাইল আকারে সংরক্ষণ
    $outputFile = 'converted_data.json';
    file_put_contents($outputFile, json_encode($convertedData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // ডাউনলোড লিঙ্ক প্রদর্শন
    echo "<h2>Conversion Successful!</h2>";
    echo "<a href='$outputFile' download>Download Converted JSON</a>";
} else {
    echo "Invalid Request!";
}
?>
