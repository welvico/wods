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

?>
