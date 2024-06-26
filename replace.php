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
        $replacedContent = preg_replace('/ipipip/', $ip, $templateContent, 1);
        $templateContent = $replacedContent; // Update template content for next iteration
    }

    // Write replaced content to $resultFile
    file_put_contents($resultFile, $replacedContent);
    echo "Replacement completed for $resultFile\n";
}

function main() {
    // Paths to your files
    $ipFujian = 'ip/Fujian_114.txt';
    $templateFujian = 'template/Fujian_114.txt';
    $resultFujian = 'result/Fujian_114.txt';

    $ipHenan = 'ip/Henan_327.txt';
    $templateHenan = 'template/Henan_327.txt';
    $resultHenan = 'result/Henan_327.txt';

    // Create result directory if it doesn't exist
    if (!is_dir('result')) {
        mkdir('result', 0755, true);
    }

    // Process Fujian files
    replaceIpInTemplate($ipFujian, $templateFujian, $resultFujian);

    // Process Henan files
    replaceIpInTemplate($ipHenan, $templateHenan, $resultHenan);
}

main();

?>
