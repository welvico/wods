<?php
// 读取 e.xml 的内容
$aContent = file_get_contents('epg/xml/e.xml');
// 读取 d.xml 的内容
$bContent = file_get_contents('epg/dxty/d.xml');
/*
// 去掉 d.xml 的 XML 声明和根标签 <tv>
$bContent = preg_replace('/<\?xml.*?\?>/', '', $bContent);
$bContent = preg_replace('/<\/?tv.*?>/', '', $bContent);
*/
// 找到 </tv> 标签的位置
$pos = strrpos($aContent, '</tv>');

if ($pos !== false) {
    // 将 e.xml 中的 </tv> 标签替换为 d.xml 的内容
    $aContent = substr_replace($aContent, $bContent, $pos, strlen('</tv>'));

    // 添加 </tv> 到 e.xml 内容的末尾
    $aContent .= '</tv>';
} else {
    echo "未找到 </tv> 标签。请检查 A.xml 文件。\n";
    exit;
}

// 将合并后的内容写回 e.xml 文件
file_put_contents('epg/xml/e.xml', $aContent);

echo "e.xml 和 d.xml 的内容已成功合并！\n";
    

?>
