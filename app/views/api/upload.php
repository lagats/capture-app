<?php

/* ---------------------------- *
 * Set the response headers
 * ---------------------------- */

header('Content-Type: application/json');



/* ---------------------------- *
 * Process request
 * ---------------------------- */

// Paths
$uploadDir = Flight::get('public.upload.path');
$uploadUrl = Flight::get('public.upload.url');
$allowedExtensions = Flight::get('app.allow.media');

// Handle the uploaded file data
$dataUriString = $_POST['file'] ?? '';
$isBase64Jpg = preg_match('/^data:image\/jpeg;base64,/', $dataUriString);
$dataDecoded = null;

$uploadFile = $_FILES['file'] ?? null;
$isFileUpload = !empty($uploadFile['tmp_name']);

// File vars
$basename = 'media__' . Flight::get('app.timestamp') . '__' . uniqid('P');
$extension = null;

// Check if the data is a base64-encoded string
if ($isBase64Jpg) {
    // Remove base64 header
    $dataDecoded = base64_decode(str_replace('data:image/jpeg;base64,', '', $dataUriString));
    // It has to be a jpg
    $extension = 'jpg';
    // Check if the data is valid (a 1px image is about 1000 bytes, so use this as the threshold)
    if(strlen($dataDecoded) < 1000) {
        echo json_encode([
            'error' => 'Invalid file data.'
        ]);
        exit;
    }
} elseif ($isFileUpload) {
    // If a file was uploaded, get the extension from the file name
    $extension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
    $filesize = filesize($uploadFile['tmp_name']);
    // Check if the data is valid (a 1px image is about 1000 bytes, so use this as the threshold)
    if($filesize < 1000) {
        echo json_encode([
            'error' => 'Invalid file upload.'
        ]);
        exit;
    }
} else {
    // Return an error if no data was provided
    echo json_encode([
        'error' => 'No file data provided.'
    ]);
    exit;
}

// Check if the extension is allowed
if (!in_array($extension, $allowedExtensions)) {
    // Return an error response if the file extension is not allowed
    echo json_encode([
        'error' => 'Invalid file type.'
    ]);
    exit;
}

// Save the file to the server (adjust the path as needed)
$filename = $basename . '.' . $extension;
$filepath = $uploadDir . $filename;

// make uploads folder if doesnt exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Save the file to the server (adjust the path as needed)
if ($isBase64Jpg) {
    // If the data is a string (base64-decoded), write it directly
    file_put_contents($filepath, $dataDecoded);
} elseif ($isFileUpload) {
    // If the data is a binary file, move it from the temporary location
    move_uploaded_file($uploadFile['tmp_name'], $filepath);
}

// Add image to your session to track which images were uploaded by the user
$session = Flight::session();
$my_captures = $session->getOrDefault('my_captures', []);
$my_captures[] = $filename;
$session->set('my_captures', $my_captures );
$session->commit();

// Generate a thumbnail for the uploaded file
$thumbnailData = generateThumbnail($filepath);

// Send a response indicating success and the uploaded file URL
echo json_encode([
    'name' => $filename,
    'size' => [
        'original' => $uploadUrl . $filename,
        'thumb' => $thumbnailData['url']
    ]
]);