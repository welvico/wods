<?php
// 读取文件内容并分割成数组
$acmFile = file('template/ACM.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$allFile = file('result/all.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// 准备合并后的数组
$mergedContent = [];

// 函数：处理文件数组内容并存入合并数组
function processFileContent($lines, &$mergedContent) {
    $group = '';
    foreach ($lines as $line) {
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
}

// 处理ACM.txt和all.txt的内容
processFileContent($acmFile, $mergedContent);
processFileContent($allFile, $mergedContent);

// 准备最终写入all.txt的内容
$finalContent = '';
foreach ($mergedContent as $group => $lines) {
    $finalContent .= $group . "\n" . implode("\n", $lines) . "\n";
}

// 写入result/all.txt
file_put_contents('result/all.txt', trim($finalContent));

echo "文件合并完成。\n";
