<?php
Header("Access-Control-Allow-Origin: *"); 
Header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); 
Header("Access-Control-Allow-Headers:orgin, x-requested-with"); 

$regkey = $_POST['regkey'];
$target  = $_POST['target'];
$query_this  = $_POST['text'];
$query_this = preg_replace('/( *)/', '', $query_this);
$now = array();

function do_curl($url,$param,$cookies=NULL,$referer_url=NULL){
    if(strlen(trim($referer_url)) == 0) $referer_url= $url;
    $fetch = curl_init ();
    curl_setopt ($fetch, CURLOPT_URL, $url);
    curl_setopt ($fetch, CURLOPT_POST, 1);
    curl_setopt ($fetch, CURLOPT_POSTFIELDS, $param);
    curl_setopt ($fetch, CURLOPT_TIMEOUT, 60);
    if($cookies && $cookies!=""){
        curl_setopt ($fetch, CURLOPT_COOKIE, $cookies);
    }
    curl_setopt ($fetch, CURLOPT_HEADER, 1);
    curl_setopt ($fetch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.01; Windows NT 6.0)");
    curl_setopt ($fetch, CURLOPT_REFERER, "$referer_url"); 

    ob_start();
    $res = curl_exec ($fetch);
    $buffer = ob_get_contents();
    ob_end_clean();
    $returnVal = array();
    if (!$buffer) {
        $returnVal['error'] = true;
        $returnVal['content'] = "Curl Fetch Error : ".curl_error($fetch);
    }else{
        $returnVal['error'] = false;
        $returnVal['content'] = $buffer;
    }
    curl_close($fetch);

    return $returnVal;
}

function get_xml($query){
    global $regkey, $target;
    $query = iconv('utf-8','euc-kr',$query);

    $post_data = array(
        'target' => $target,
        'regkey' => $regkey,
        'query' => $query
    );

    $url = 'http://biz.epost.go.kr/KpostPortal/openapi';
    $param = http_build_query($post_data);
    $result = do_curl($url,$param);
    $result['content'] = remove_not_xml($result['content']);

    return $result;
}

function remove_not_xml($content){
    $arrayed = explode("\n", $content);
    foreach ($arrayed as $k => $v) {
        if(substr(trim($v),0,1)!='<'){
            $arrayed[$k]='';
        }
    }
    unset($arrayed[0]);
    $content = implode("\n", $arrayed);
    return trim($content);
}

function add_dash($postcd){
    $postcd1=substr($postcd,0,3);
    $postcd2=substr($postcd,3,3);
    return $postcd1.'-'.$postcd2;
}

function print_postcode($xml){
    global $now;

    foreach ($xml->itemlist->item as $v) {
        $postcd = add_dash($v->postcd);
        $now[] = (string)$v->address.' '.$postcd;
    }

    return $now;
}


if(!empty($query_this)){
    $result = get_xml($query_this); 

    if ($result['error'] == false){
        $xml = new SimpleXMLElement($result['content']);
        if(count($xml->itemlist->item) == 0){

        }else{
           $ret = print_postcode($xml);
        }
    }
}

echo json_encode( $ret );
