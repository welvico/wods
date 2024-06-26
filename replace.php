<?php

function mergeFilesByGroup($templateDir, $resultFile) {
    // Check if Henan_327.txt template file exists
    $templateHenan = $templateDir . 'Henan_327.txt';
    $templateExists = file_exists($templateHenan);

    // Open or create the result file
    $resultHandle = fopen($resultFile, 'w');
    if (!$resultHandle) {
        echo "Failed to open result file: $resultFile\n";
        return;
    }

    // Array to store merged content by group
    $mergedContent = [];

    // Get all template files
    $templateFiles = glob($templateDir . '*.txt');

    // Process each template file
    foreach ($templateFiles as $templateFile) {
        // Skip Henan_327.txt if it exists (we handle it separately)
        if ($templateFile === $templateHenan) {
            continue;
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

    // Write template/Henan_327.txt content first if exists
    if ($templateExists) {
        $henanLines = file($templateHenan, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($henanLines as $line) {
            fwrite($resultHandle, "$line\n");
        }
        fwrite($resultHandle, "\n");
    }

    // Write merged content to result file, excluding Henan_327.txt
    foreach ($mergedContent as $group => $content) {
        if (strpos($group, ',#genre#') === false) { // Skip the template file's header line
            fwrite($resultHandle, "$group\n");
            foreach ($content as $line) {
                fwrite($resultHandle, "$line\n");
            }
            fwrite($resultHandle, "\n");
        }
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
