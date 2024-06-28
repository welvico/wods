<?php

function replaceIpInTemplate($ipFile, $templateFile, $resultFile) {
    // Check if IP file exists
    if (!file_exists($ipFile)) {
        echo "IP file not found: $ipFile\n";
        return;
    }

    // Check if template file exists
    if (!file_exists($templateFile)) {
        echo "Template file not found: $templateFile\n";
        return;
    }

    // Read IP addresses from $ipFile
    $ipLines = file($ipFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "Read " . count($ipLines) . " IP addresses from $ipFile\n";

    // Read template content from $templateFile
    $templateContent = file_get_contents($templateFile);

    // Replace 'ipipip' with each IP address from $ipFile
    foreach ($ipLines as $ip) {
        $templateContent = str_replace('ipipip', $ip, $templateContent);
        echo "Replaced ipipip with $ip\n";
    }

    // Write replaced content to $resultFile
    file_put_contents($resultFile, $templateContent);
    echo "Replacement completed for $resultFile\n";

    // Print the final content of the result file
    echo "Content of $resultFile:\n";
    echo $templateContent;
}

function main() {
    $templateDir = 'template/';
    $resultDir = 'result/';

    // Create result directory if it doesn't exist
    if (!is_dir($resultDir)) {
        mkdir($resultDir, 0755, true);
        echo "Created result directory\n";
    }

    // Get all files in template directory
    $templateFiles = glob($templateDir . '*.txt');
    
    // Process each template file
    foreach ($templateFiles as $templateFile) {
        // Construct paths for IP, template, and result files
        $templateFileName = basename($templateFile);
        $ipFile = 'ip/' . $templateFileName;
        $resultFile = $resultDir . $templateFileName;

        // Process files using the existing function
        replaceIpInTemplate($ipFile, $templateFile, $resultFile);
    }
}

main();


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

    // Check if template/Henan_328.txt exists
    $henanTemplateFile_LT = $templateDir . 'Henan_328.txt';
    if (file_exists($henanTemplateFile_LT)) {
        // Read Henan_328.txt content first
        $henanLines_LT = file($henanTemplateFile_LT, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentGroup_LT = '';

        // Process each line in Henan_328.txt
        foreach ($henanLines_LT as $line_LT) {
            if (strpos($line_LT, ',#genre#') !== false) {
                $currentGroup_LT = $line_LT;
                continue;
            }

            // Add line to merged content under current group
            if (!empty($currentGroup_LT)) {
                if (!isset($mergedContent[$currentGroup_LT])) {
                    $mergedContent[$currentGroup_LT] = [];
                }
                $mergedContent[$currentGroup_LT][] = $line_LT;
            }
        }
    }

    // Get all other template files
    $templateFiles = glob($templateDir . '*.txt');

    // Process each template file (excluding Henan_327.txt , Henan_328.txt )
    foreach ($templateFiles as $templateFile) {
        if ($templateFile === $henanTemplateFile || $templateFile === $henanTemplateFile_LT) {
            continue; // Skip Henan_327.txt or Henan_328.txt since it's already processed
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
