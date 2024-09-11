<?php
$dxtyEpgUrl = getenv('DXTY_EPG_URL');
$dxtyCookie = getenv('DXTY_COOKIE');

$n = array(
"DX24h轮播"=>"C8000000000000000001658200465881",
"DX传奇剧场"=>"C8000000000000000001586755568202",
"DX热血剧场"=>"C8000000000000000001586755334304",
"DX硬汉剧场"=>"C8000000000000000001587975332585",	
"DX往昔剧场"=>"C8000000000000000001681195984396",
"DX生活剧场"=>"C8000000000000000001587975283115",
"DX怀旧经典"=>"C8000000000000000001681196041195",
"DX放映厅"=>"C8000000000000000001659935357556",
"DX梨园戏曲"=>"C8000000000000000001681695523229",
"DX红色经典"=>"C8000000000000000001681368925801",
"童年时光机"=>"C8000000000000000001681368975369",
"大咖面对面"=>"C8000000000000000001658992595677",
);

// 获取键和值
$keys = array_keys($n);
$values = array_values($n);

$ids = '';
foreach ($n as $id) {
	$ids .= $id .',';
}

// 定义 URL
$url = $dxtyEpgUrl . $ids;

// 初始化 cURL
$ch = curl_init($url);

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 临时禁用 SSL 证书验证
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  // 临时禁用 SSL 主机验证


// 设置请求头
$headers = [		    
		    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
				'Accept-Encoding: gzip, deflate, br',
				'Accept-Language: zh-CN,zh;q=0.9',
				'Cache-Control: no-cache',
				'Connection: keep-alive',
				$dxtyCookie,
				'DNT: 1',
				'Pragma: no-cache',
				'sec-ch-ua: "Chromium";v="109", "Not_A Brand";v="99"',
				'sec-ch-ua-mobile: ?1',
				'sec-ch-ua-platform: "Android"',
				'Sec-Fetch-Dest: document',
				'Sec-Fetch-Mode: navigate',
				'Sec-Fetch-Site: none',
				'Sec-Fetch-User: ?1',
				'Upgrade-Insecure-Requests: 1',
				'User-Agent: Mozilla/5.0 (Linux; Android 8.0.0; SM-G955U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Mobile Safari/537.36',          
		];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 执行 cURL 请求
$json_data = curl_exec($ch);

// 检查 cURL 请求是否成功
if ($json_data === false) {
    die('cURL 错误: ' . curl_error($ch));
}

// 关闭 cURL
curl_close($ch);

// 打印 JSON 数据以检查格式
//echo '<pre>' . htmlspecialchars($json_data) . '</pre>';

// 解析 JSON 数据
$data = json_decode($json_data, true);

// 检查 JSON 是否解析成功
if ($data === NULL) {
    die('JSON 解析失败');
}

// 提取 info 数组
if (isset($data['info']) && is_array($data['info'])) {
    $info = $data['info'];
    $content ='';
    $k = 0;
    $match_id = $values[0];
    $m = 0;
    // 遍历 info 数组生成输出
    foreach ($info as $item) {
        $starttime = str_replace(['-', ' ', ':'], '', $item['starttime']) . ' +0800';
        $endtime = str_replace(['-', ' ', ':'], '', $item['endtime']) . ' +0800';
        $title = $item['title'];
        $nameid =  $item['liveid'];
        //echo "start=\"$starttime\"\n";
        //echo "stop=\"$endtime\"\n";
        //echo "$title\n\n";
        $content3 = '';
        if($match_id == $nameid){        
            if($m == 0){
                $content1 = "<channel id=\"". $keys[$k] ."\">\n<display-name lang=\"zh\">". $keys[$k] ."</display-name>\n</channel>\n";
        	      $m=1;    
    	       }else{
        	      $content1 = '';	
    	       }
            $content2 = "<programme channel=\"". $keys[$k] ."\" start=\"".$starttime."\" stop=\"".$endtime."\">\n  <title lang=\"zh\">".$title."</title>\n</programme>\n";	                   
            $content3 =  $content1 . $content2;
            $content .= $content3; 
	      }else{
            $match_id = $nameid;
            $k++;
            $m=0;      	
	      }                  
    }
    //echo $content ;
    // 使用 file_put_contents() 将 $content 写入到 d.xml 文件
    file_put_contents('epg/dxty/d.xml', $content);

} else {
    echo '未找到 info 数组或 info 不是数组';
}
