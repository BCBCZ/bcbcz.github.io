<?php
//判断接收方式
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $re_fs = "get";
    //echo '这是一个GET请求';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo '这是一个POST请求';
    $re_fs = "post";
} else {
    //echo '这不是GET也不是POST请求';
}

//判断接收格式
/*
if (isset($_SERVER['HTTP_CONTENT_TYPE']) && $_SERVER['HTTP_CONTENT_TYPE'] === 'application/json') {
    $re_gs = "json";
    // 请求包含 JSON 数据
    $json_data = file_get_contents("php://input");
    $jsondata = json_decode($json_data, true); // 解析 JSON 数据
    
    echo "解析json" . $jsondata;
}
*/
$re_gs = "form";
$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
if (stripos($content_type, 'application/json') !== false) {
    $re_gs = "json";
    if ($re_fs == "post"){
        // 请求包含 JSON 数据
        $json_data = file_get_contents("php://input");
        $jsondata = json_decode($json_data, true); // 解析 JSON 数据
    }
    if ($re_fs == "get"){
        $json_data = $_GET['data']; // ?data={json内容}
        //echo "data：" . $_GET['data'];
        $jsondata = json_decode($json_data, true); // 解析 JSON 数据
    }
    
    if ($jsondata !== null) {
        // 成功解析 JSON 数据，继续处理
        //echo "成功解析json：" . $json_data . "\n";
        $responddata['json_parsing'] = "ok";
    } else {
        // 无法解析 JSON 数据，处理错误情况
        //echo "json解析失败\n";
        $responddata['json_parsing'] = "no";
      }
}
//gf  gj  pf  pj          gj pj
//构建 源响应 结果
$sourceresponddata = array();
$sendapitest = array();
//输出为{"g":"ok","data(源响应)":{"k":"23","a":"6"},"p":"g"}

//参数解析    
/*
account
addAddress

*/
$Sourceparameters = array(); // 使用关联数组存储参数 源参数
$Sourceparameternames = array('ask', 'text'); // 源参数名   Dedicateddatachannel专用数据通道
if ($re_fs == "get" && $re_gs == "form"){
    //$Sourceparameters = array(); // 使用关联数组存储参数 源参数
    //$Sourceparameternames = array('account', 'a', 'b', 'c', 'd', 'e'); // 源参数名
    foreach ($Sourceparameternames as $Sourceparametername) {
        if (isset($_GET[$Sourceparametername])) {
            $Sourceparametervalue= $_GET[$Sourceparametername];
            $Sourceparameters[$Sourceparametername] = $Sourceparametervalue;
        }
    }

}
if ($re_fs == "post" && $re_gs == "form"){
    //$Sourceparameters = array(); // 使用关联数组存储参数 源参数
    //$Sourceparameternames = array('account', 'a', 'b', 'c', 'd', 'e'); // 源参数名
    foreach ($Sourceparameternames as $Sourceparametername) {
        if (isset($_POST[$Sourceparametername])) {
            $Sourceparametervalue= $_POST[$Sourceparametername];
            $Sourceparameters[$Sourceparametername] = $Sourceparametervalue;
        }
    }
}
if ($re_gs == "json"){
    //$Sourceparameters = array(); // 使用关联数组存储参数 源参数
    //$Sourceparameternames = array('account', 'a', 'b', 'c', 'd', 'e'); // 源参数名
    foreach ($Sourceparameternames as $Sourceparametername) {
        if (isset($jsondata[$Sourceparametername])) {
            $Sourceparametervalue= $jsondata[$Sourceparametername];
            $Sourceparameters[$Sourceparametername] = $Sourceparametervalue;
        }
    }
}

$debugging = $Sourceparameters['debugging'];
if ($debugging == "on") {
    $Addeffectivetime = $Sourceparameters['Addeffectivetime'] ?? "60";//默认加 60秒 有效时间
    $sendapitest['Addeffectivetime'] = $Addeffectivetime;
}



$string = $Sourceparameters['ask'];
// 使用正则表达式匹配 "KB"
preg_match('/KB/', $string, $matches);
if (!empty($matches)) {
    //echo "提取的字符串是: " . $matches[0];
} else {
    //echo "没有找到匹配的字符串";
}
if ($Sourceparameters['ask'] == "KB" || $matches[0] == "KB" || $Sourceparameters['ask'] == "查天气" || $Sourceparameters['ask'] == "服务器状态") {
    $Sourceparameters['ask'] = "你在干什么";
} else {

  }

if ($Sourceparameters['ask'] == "test") {
    $Sourceparameters['ask'] = "测试成功";
}
if ($Sourceparameters['ask'] == "转发服务日志") {
    $Sourceparameters['ask'] = "转发服务日志\n24.7.29-修复转发服务掉线问题";
}
if ($Sourceparameters['ask'] == "绑定") {
    $Sourceparameters['ask'] = "格式：绑定+id\n例如：绑定MClwt233";
}



$responddata['code'] = 200;

$responddata['content'] = $Sourceparameters['ask'];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($responddata, JSON_UNESCAPED_UNICODE);


?>
