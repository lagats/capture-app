<?php

/**
 * Generates a thumbnail for the specified image file.
 *
 * @param string $imageFile The path to the original image file.
 * @return string|false The path to the generated thumbnail file, or false if the thumbnail could not be created.
 */
function generateThumbnail($imageFile) {
    // Check if the original image file exists
    if (!file_exists($imageFile)) {
        return false;
    }

    // Paths
    $thumbnailDir = Flight::get('public.thumbnail.path');
    $thumbnailUrl = Flight::get('public.thumbnail.url');

    // Get the original image filename and extension
    $originalFilename = basename($imageFile);
    $imageExtension = pathinfo($imageFile, PATHINFO_EXTENSION);

    // Create the thumbnail filename and path
    $thumbnailFilename = 'thumb__' . $originalFilename;
    $thumbnailFilepath = $thumbnailDir . '/' . $thumbnailFilename;

    // Make thumbnail folder if it doesn't exist
    if (!file_exists($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }

    // Check if the thumbnail already exists
    if (!file_exists($thumbnailFilepath)) {
        // Load the original image
        $originalImage = imagecreatefromstring(file_get_contents($imageFile));

        // Get the original image dimensions
        $originalWidth = imagesx($originalImage);
        $originalHeight = imagesy($originalImage);

        // Determine the thumbnail dimensions, maintaining aspect ratio
        $thumbnailWidth = 300; // Target width
        $thumbnailHeight = $originalHeight * ($thumbnailWidth / $originalWidth);

        // Create a new image with the thumbnail dimensions
        $thumbnailImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        // Resize the original image to fit the thumbnail dimensions
        imagecopyresampled($thumbnailImage, $originalImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

        // Set the image quality to 70%
        imagejpeg($thumbnailImage, $thumbnailFilepath, 70);

        // Free memory
        imagedestroy($originalImage);
        imagedestroy($thumbnailImage);
    }

    return [
        'name' => $thumbnailFilename,
        'path' => $thumbnailFilepath,
        'url'  => $thumbnailUrl . $thumbnailFilename,
    ];
}