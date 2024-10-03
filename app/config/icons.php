<?php


/* ---------------------------- *
 * Helpers
 * ---------------------------- */

/**
 * Retrieves the contents of the specified SVG file.
 **/
function getSVG(string $svgFile, string $className = '') {
	if (file_exists($svgFile)) {
			$svgContent = file_get_contents($svgFile);

			// Add the class to the SVG element
			$svgContent = preg_replace('/<svg/', '<svg class="' . trim('svg-icon' . ' ' . $className) . '" ', $svgContent, 1);

			return $svgContent;
	} else {
			return '';
	}
}



/* ---------------------------- *
 * Load Icons
 * ---------------------------- */

// Get image files considering extensions
$files = glob(Flight::get('app.icon.path') . "/*.svg");
foreach ($files as $file) {
    $filename = pathinfo($file, PATHINFO_FILENAME);
	Flight::set('icon.' . $filename, getSVG($file));
}