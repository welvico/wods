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
        
    // Process Henan_327.txt if it exists
    $henan327File = $templateDir . 'Henan_327.txt';
    if (file_exists($henan327File)) {
        processFile($henan327File, $mergedContent);
    }

    // Process Henan_338.txt if it exists and append to merged content
    $henan338File = $templateDir . 'Henan_338.txt';
    if (file_exists($henan338File)) {
        processFile($henan338File, $mergedContent);
    }

    // Process Shanxi_CU_517.txt if it exists and append to merged content
    $shanxi517File = $templateDir . 'Shanxi_CU_517.txt';
    if (file_exists($shanxi517File)) {
        processFile($shanxi517File, $mergedContent);
    }

    // Get all other template files
    $templateFiles = glob($templateDir . '*.txt');

    // Process each template file (excluding Henan_327.txt and Henan_338.txt and Shanxi_CU_517.txt)
    foreach ($templateFiles as $templateFile) {
        if ($templateFile === $henan327File || $templateFile === $henan338File || $templateFile === $shanxi517File) {
            continue; // Skip Henan_327.txt and Henan_338.txt and Shanxi_CU_517.txt since they're already processed
        }

        processFile($templateFile, $mergedContent);
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

function processFile($filePath, &$mergedContent) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $currentGroup = '';

    foreach ($lines as $line) {
        if (strpos($line, ',#genre#') !== false) {
            $currentGroup = $line;
            continue;
        }

        if (!empty($currentGroup)) {
            if (!isset($mergedContent[$currentGroup])) {
                $mergedContent[$currentGroup] = [];
            }
            $mergedContent[$currentGroup][] = $line;
        }
    }
}

// Paths
$templateDir = 'result/';
$resultFile = 'result/all.txt';

// Call function to merge files by group
mergeFilesByGroup($templateDir, $resultFile);
include 'merge_acm.php';
?>
