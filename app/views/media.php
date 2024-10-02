<?php

// Paths
$uploadDir = Flight::get('public.upload.path');
$uploadUrl = Flight::get('public.upload.url');
$thumbnailDir = Flight::get('public.thumbnail.path');
$thumbnailUrl = Flight::get('public.thumbnail.url');
$allowedExtensions = Flight::get('app.allow.media');

// Check if the directory exists and is readable
if (!is_dir($uploadDir) || !is_readable($uploadDir)) {
  die("Error: Directory '$uploadDir' does not exist or is not readable.");
}

// Default values for pagination (sanitize later)
$page = isset($_GET['page']) ? (int) filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT) : 1;
$itemsPerPage = isset($_GET['count']) ? (int) filter_var($_GET['count'], FILTER_SANITIZE_NUMBER_INT) : 10;

// Get image files considering extensions
$files = glob($uploadDir . "/*.{" . implode(',', $allowedExtensions) . "}", GLOB_BRACE);

// Check if any images were found
if (empty($files)) {
  echo json_encode(['message' => 'No images found in the directory.']);
  exit;
}

// Sort files by modification time (newest first)
usort($files, function ($a, $b) {
  return filemtime($b) - filemtime($a); // Descending order
});

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Limit the results based on items per page
$slicedFiles = array_slice($files, $offset, $itemsPerPage);

$data = [];
foreach ($slicedFiles as $file) {
  $filename = basename($file);

  // Generate a thumbnail for the file if doesnt exist
  $thumbnailData = generateThumbnail($file);

  $data[] = [
    'name' => $filename,
    'size' => [
        'original' => $uploadUrl . $filename,
        'thumb' => $thumbnailData['url']
    ]
  ];
}

// Encode data as JSON
echo json_encode($data);