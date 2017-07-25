<?php
//连接sql server
$db_server = '10.0.5.74,52437';

$db_id = 'sa';

$db_pwd = 'cm248soft*';

$db_name = 'MyCMP';

$con_info = array("Database" => $db_name, "UID" => $db_id, "PWD" => $db_pwd);

global $con;

$con = sqlsrv_connect($db_server, $con_info);

//$con = mssql_connect($db_server,$db_id,$db_pwd);

$val = sqlsrv_query($con, "select * from s_flowmessage where tableid = '862'");
$new_row = [];
while ($row = sqlsrv_fetch_array($val,SQLSRV_FETCH_ASSOC)) {
    $new_row[]=$row;
}
foreach($new_row as $k=>&$v){
    foreach($v as $key=>$val){
        if(!is_utf8($val))
        $v[$key] = iconv("gb2312","utf-8//IGNORE", $val);

    	$v['index'] = $k+1;
    }
}
echo json_encode($new_row);

//
function is_utf8($str){
    $len = strlen($str);
    for($i = 0; $i < $len; $i++){
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}


