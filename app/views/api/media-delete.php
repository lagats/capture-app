<?php

/* ---------------------------- *
 * Set the response headers
 * ---------------------------- */

header('Content-Type: application/json');



/* ---------------------------- *
 * Load images from upload folder
 * ---------------------------- */

// Paths
$uploadDir = Flight::get('public.upload.path');
$uploadUrl = Flight::get('public.upload.url');
$thumbnailDir = Flight::get('public.thumbnail.path');
$thumbnailUrl = Flight::get('public.thumbnail.url');
$allowedExtensions = Flight::get('app.allow.media');

// Default values for pagination (sanitize later)
$delete_files = json_decode(Flight::request()->data->delete_files ?? []);

// Get captured photos by user
$capturedPhotos = Flight::session()->getOrDefault('my_captures', []);

// Response object
$response = [
    'success' => 0,
    'deleted' => [],
    'errors' => [],
];

// Loop through files in JSON string
foreach ($delete_files as $delete_file) {

    $filePath = $uploadDir . '/' . $delete_file;
    $thumbPath = $thumbnailDir . '/thumb__' . $delete_file;

    // Check if file exists in upload directory
    if (in_array($delete_file, $capturedPhotos)) {
        // Delete file
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                $response['success']++;
                $response['deleted'][] = $uploadUrl . '/' . $delete_file;
            } else {
                $response['errors'][] = "Error deleting $delete_file from upload directory.";
            }
        }

        // Check if thumbnail exists and delete
        if (file_exists($thumbPath)) {
            if (unlink($thumbPath)) {
                $response['success']++;
                $response['deleted'][] = $thumbnailUrl . '/thumb__' . $delete_file;
            } else {
                $response['errors'][] = "Error deleting $delete_file thumbnail.";
            }
        }
    } else {
        $response['errors'][] = "File '$delete_file' not found or not owned by user.";
    }
}

echo json_encode($response);
