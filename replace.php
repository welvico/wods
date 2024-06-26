<?php

function mergeFilesByGroup($templateDir, $resultFile) {
    // Open or create the result file
    $resultHandle = fopen($resultFile, 'w');
    if (!$resultHandle) {
        echo "Failed to open result file: $resultFile\n";
        return;
    }

    // Array to store merged content by group
    $mergedContent = [];

    // Check if template/Henan_327.txt exists
    $henanTemplateFile = $templateDir . 'Henan_327.txt';
    if (file_exists($henanTemplateFile)) {
        // Read Henan_327.txt content first
        $henanLines = file($henanTemplateFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentGroup = '';

        // Process each line in Henan_327.txt
        foreach ($henanLines as $line) {
            if (strpos($line, ',#genre#') !== false) {
                $currentGroup = $line;
                continue;
            }

            // Add line to merged content under current group
            if (!empty($currentGroup)) {
                if (!isset($mergedContent[$currentGroup])) {
                    $mergedContent[$currentGroup] = [];
                }
                $mergedContent[$currentGroup][] = $line;
            }
        }
    }

    // Get all other template files
    $templateFiles = glob($templateDir . '*.txt');

    // Process each template file (excluding Henan_327.txt)
    foreach ($templateFiles as $templateFile) {
        if ($templateFile === $henanTemplateFile) {
            continue; // Skip Henan_327.txt since it's already processed
        }

        $lines = file($templateFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentGroup = '';

        // Process each line in the file
        foreach ($lines as $line) {
            if (strpos($line, ',#genre#') !== false) {
                $currentGroup = $line;
                continue;
            }

            // Add line to merged content under current group
            if (!empty($currentGroup)) {
                if (!isset($mergedContent[$currentGroup])) {
                    $mergedContent[$currentGroup] = [];
                }
                $mergedContent[$currentGroup][] = $line;
            }
        }
    }

    // Write merged content to result file
    foreach ($mergedContent as $group => $content) {
        fwrite($resultHandle, "$group\n");
        foreach ($content as $line) {
            fwrite($resultHandle, "$line\n");
        }
        fwrite($resultHandle, "\n");
    }

    // Close the result file handle
    fclose($resultHandle);
    echo "Merged content written to $resultFile\n";
}

// Paths
$templateDir = 'result/';
$resultFile = 'result/all.txt';

// Call function to merge files by group
mergeFilesByGroup($templateDir, $resultFile);

?>
