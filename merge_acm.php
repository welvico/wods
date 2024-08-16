<?php
// 读取result/ACM.txt文件内容
$acmFile = file_get_contents('template/ACM.txt');
$acmLines = explode("\n", $acmFile);

// 读取result/all.txt文件内容
$allFile = file_get_contents('result/all.txt');
$allLines = explode("\n", $allFile);

// 准备合并后的数组
$mergedContent = [];

// 处理ACM.txt的内容
$group = '';
foreach ($acmLines as $line) {
    $line = trim($line); // 去除多余空格和换行符
    if (strpos($line, '#genre#') !== false) {
        // 新分组
        $group = $line;
        if (!isset($mergedContent[$group])) {
            $mergedContent[$group] = [];
        }
    } elseif ($group) {
        // 加入当前分组
        $mergedContent[$group][] = $line;
    }
}

// 处理all.txt的内容
$group = '';
foreach ($allLines as $line) {
    $line = trim($line); // 去除多余空格和换行符
    if (strpos($line, '#genre#') !== false) {
        // 新分组
        $group = $line;
        if (!isset($mergedContent[$group])) {
            $mergedContent[$group] = [];
        }
    } elseif ($group) {
        // 加入当前分组
        $mergedContent[$group][] = $line;
    }
}

// 准备最终写入all.txt的内容
$finalContent = '';
foreach ($mergedContent as $group => $lines) {
    $finalContent .= $group . "\n" . implode("\n", $lines) . "\n";
}

// 写入result/all.txt
file_put_contents('result/all.txt', trim($finalContent));

echo "文件合并完成。\n";
