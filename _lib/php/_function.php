<?php
defined('_SB_CFG') or die;

function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

/*****************************************/
/*** Коннекты к базам ***/
function ConnectMyDB($dbName, $where="") {
	global $_SB_cfg, $_dbLocal;
	if($dbName=='portal'){
		return $_dbLocal;
	} else {
		$sql = "SELECT * FROM `databases` WHERE `dbname` = ?s " . $where;
		$row = $_dbLocal->getRow($sql, $dbName);
	
		$db = new SafeMysql(array(
					'host' => $row['server'],
					'user' => $row['login'],
					'pass' => $row['password'],
					'db'   => $dbName,
					'charset' => $row['charset']
					));
		return $db;
	}
}


function ConnectPDO($_db_name, $opt="") {
	global $_SB_cfg;
	if($opt==""){
		$opt = array(
			PDO::ATTR_PERSISTENT			=> true,
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
		);
	}
	return new PDO(
		$_SB_cfg['connect'][$_db_name]['dns'],
		$_SB_cfg['connect'][$_db_name]['login'],
		$_SB_cfg['connect'][$_db_name]['password'],
		$opt);
}



function ConnectMyPDO($dbName, $where="") {
	global $_SB_cfg, $_pdoLocal;
	$charset = 'utf8';
	
	$dsn= "mysql:host={$_SB_cfg['LocalConnectServer']};dbname=$dbName;charset=$charset";
	$opt = array(
		PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
	);
	return new PDO($dsn, $_SB_cfg['LocalConnectLogin'], $_SB_cfg['LocalConnectPassword'], $opt);
}



function ConnectMyDB2($dbName, $where="") {
	global $_SB_cfg, $_dbLocal;
//	if($dbName=='portal'){
//		return $_dbLocal;
//	} else {
		$sql = "SELECT * FROM `databases` WHERE `dbname` = ?s " . $where;
		$row = $_dbLocal->getRow($sql, $dbName);
	
		$mysqli = new mysqli($row['server'], $row['login'], $row['password'], $dbName);
		mysqli_set_charset($mysqli,$row['charset']);

		return $mysqli;
//	}
}


function ConnectOciDB($dbName) {
	global $_SB_cfg, $_dbLocal;

	$row = $_dbLocal->getRow("SELECT * FROM `databases` WHERE `dbname` = ?s", $dbName);
	//print_r($row);
		$conn = oci_connect($row['login'],$row['password'],$row['server'].":".$row['port']."/".$row['sid'],$row['charset']);
	if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}






function ConnectJOUR() {
	global $_SB_cfg;
	$conn = oci_connect($_SB_cfg['JOURconnectLogin'],$_SB_cfg['JOURconnectPassword'],$_SB_cfg['JOURconnectServer'],$_SB_cfg['JOURconnectCodePage']);
	if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}


function ConnectTIR() {
	global $_SB_cfg;
	$conn = oci_connect($_SB_cfg['TIRconnectLogin'],$_SB_cfg['TIRconnectPassword'],$_SB_cfg['TIRconnectServer'],$_SB_cfg['TIRconnectCodePage']);
	if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}
function ConnectLocalTIR() {
	global $_SB_cfg;
	$conn = oci_pconnect($_SB_cfg['LocalTIRconnectLogin'],$_SB_cfg['LocalTIRconnectPassword'],$_SB_cfg['LocalTIRconnectServer'],$_SB_cfg['LocalTIRconnectCodePage']);
	if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}
function ConnectLocalPVD() {
	global $_SB_cfg;
	$conn = @oci_pconnect($_SB_cfg['LocalPVDconnectLogin'],$_SB_cfg['LocalPVDconnectPassword'],$_SB_cfg['LocalPVDconnectServer'],$_SB_cfg['LocalPVDconnectCodePage']);
	if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}
function ConnectGRP($dept_id=1) {
	global $_SB_cfg;
	$conn = oci_pconnect($_SB_cfg['GRPconnectLogin'][$dept_id],$_SB_cfg['GRPconnectPassword'][$dept_id],$_SB_cfg['GRPconnectServer'][$dept_id],$_SB_cfg['SSDconnectCodePage']);
	//if (!$conn) {$e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);}
	return $conn;
}
function ConnectLOCAL($_DB = "") {
	global $_SB_cfg;
	$db = trim ( $_DB )=="" ? $_SB_cfg['PortalDatabase'] : $_DB ;
//echo $db."<br>";
	$linkMysql = mysql_connect($_SB_cfg['PortalServer'],$_SB_cfg['PortalLogin'],$_SB_cfg['PortalPassword']);
		//@new mysqli($_SB_cfg['LocalConnectServer'],$_SB_cfg['LocalConnectLogin'],$_SB_cfg['LocalConnectPassword'], $db);
	if (!$linkMysql) { die('Could not connect: ' .  mysql_error());} //$mysqli->connect_error); } //
	if (!mysql_select_db($db)) { die('Could not select database: '.mysql_error()); }
	if (!mysql_query('SET NAMES "UTF8"')) { die('Could not select database: ' . mysql_error());}
	return $linkMysql;
}
function ConnectPGL($_DB = "") {
	global $_SB_cfg;
	$db = trim ( $_DB )=="" ? $_SB_cfg['PortalDatabase'] : $_DB ;
//echo $db."<br>";

	$dbconn = pg_connect("host={$_SB_cfg['PortalServer']} dbname={$db} user={$_SB_cfg['PortalLogin']} password={$_SB_cfg['PortalPassword']}")
    or die('Could not connect: ' . pg_last_error());

//	if (!pg_query('SET NAMES "UTF8"')) { die('Could not select database: ' . pg_last_error());}
	return $dbconn;
}


/*---------------------------------------------------------------------------------*/
/*** Убиваем коннекты к базам Oracle, иначе они будут висеть в сессиях
$a = массив   */
function DisconnectOCI($a) {
	foreach ( $a as $v ) {
		@oci_close($v);
	}
}


/*****************************************/
/*** Обработка запросов Oracle ***/
function oci_sql_exec($connection, $sql){
	$statement = oci_parse($connection, $sql);
	if(! oci_execute($statement)) { $e = oci_error();trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $statement;
}

/*** Обработка запросов MySQL ***/
function mys_query($query) {
	$result = mysql_query( $query );
	mys_test_result($result,$query);
	return $result;
}



function xml2array ( $xmlObject, $out = array () ) {
        foreach ( (array) $xmlObject as $index => $node )
             $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
        return $out;
}

function GetFileNameFromDir($path, $extension){
     $dir_handle = @opendir($path) or die("Unable to open $path");
     while($file = readdir($dir_handle)){ //loop through all the files in the path
         if($file == "." || $file == ".."){continue;} //ignore these
         $filename = explode(".",$file); //seperate filename from extenstion
         $cnt = count($filename); $cnt--; $ext = $filename[$cnt]; //as above
         if(strtolower($ext) == strtolower($extension)){ //if the extension of the file matches the extension we are looking for...
             return $file;
         }
     }
     return false;
}

function list_by_ext($extension, $path){
     $list = array(); //initialise a variable
     $dir_handle = @opendir($path) or die("Unable to open $path"); //attempt to open path
     while($file = readdir($dir_handle)){ //loop through all the files in the path
         if($file == "." || $file == ".."){continue;} //ignore these
         $filename = explode(".",$file); //seperate filename from extenstion
         $cnt = count($filename); $cnt--; $ext = $filename[$cnt]; //as above
         if(strtolower($ext) == strtolower($extension)){ //if the extension of the file matches the extension we are looking for...
             array_push($list, $file); //...then stick it onto the end of the list array
         }
     }
     if($list[0]){ //...if matches were found...
     return $list; //...return the array
     } else {//otherwise...
     return false;
     }
}
 
function mydate ($arg_1){
	$tmp = explode(".",$arg_1);
	$mydate = implode(".",array_reverse($tmp));
	return $mydate; 
}



  ////////////////////////////////////////////////////////// 
  // Рекурсивная функция - спускаемся вниз по каталогу 
  ////////////////////////////////////////////////////////// 
  function scan_dir($dirname) 
  { 
    // Объявляем переменные замены глобальными 
    GLOBAL $scan__text, $scan__retext; 
    // Открываем текущую директорию 
    $dir = opendir($dirname); 
    // Читаем в цикле директорию 
    while (($file = readdir($dir)) !== false) 
    { 
      // Если файл обрабатываем его содержимое 
      if($file != "." && $file != "..") 
      { 
        // Если имеем дело с файлом - производим в нём замену 
        if(is_file($dirname."/".$file)) 
        { 
          // Читаем содержимое файла 
          $content = file_get_contents($dirname."/".$file); 
          // Осуществляем замену 
          $content = str_replace($scan__text, $scan__retext, $content); 
          // Перезаписываем файл 
          file_put_contents(file_put_contents,$content); 
        } 
        // Если перед нами директория, вызываем рекурсивно 
        // функцию scan_dir 
        if(is_dir($dirname."/".$file)) 
        { 
          echo $dirname."/".$file."<br>"; 
          scan_dir($dirname."/".$file); 
        } 
      } 
    } 
    // Закрываем директорию 
    closedir($dir); 
  }

  
function trubleXML($str){
	$healthy = array("<>",  "<",  ">",  "&");
	$yummy   = array(".не равно.",".меньше.",".больше.",".and.");
	return str_replace($healthy,$yummy,$str);
}
  
function sb_debug_info($text){
	if (_SB_DEBUG){ echo "<p>$text</p>"; }
}
function debug_info($text){
	if (_SB_DEBUG){ echo "$text<br>"; }
}
  
  
/////////////////////////////////////////////\///\///////////////////////////////////////
//////////////////////////////////////////////\///\//////////////////////////////////////
////////////////////////// MySQL //////////////\///\/////////////////////////////////////
////////////////////////////////////////////////\///\////////////////////////////////////

function mys_test_result($result,$query){
	// Проверяем результат
	// Это показывает реальный запрос, посланный к MySQL, а также ошибку. Удобно при отладке.
	if (!$result) {
		$message  = 'Неверный запрос: ' . mysql_error() . "\n";
		$message .= 'Запрос целиком: ' . $query;
		die($message);
	}
}

/////////////////////////////////////////////\///\///////////////////////////////////////
//// Взвращает параметры в виде объекта, переданные в PHP разными методами
/////////////////////////////////////////////\///\///////////////////////////////////////
function parseRequest($method) {
        if ($method == 'PUT') {   // <-- Have to jump through hoops to get PUT data
            $raw  = '';
            $httpContent = fopen('php://input', 'r');
            while ($kb = fread($httpContent, 1024)) {
                $raw .= $kb;
            }
            fclose($httpContent);
            $params = array();
            parse_str($raw, $params);

			//print_r( $params );
            if (isset($params['data'])) {
                $sparams =  json_decode(stripslashes($params['data']));
            } else {
                $params = json_decode(stripslashes($raw));
                $sparams = $params->data;
            }
        } else {
            // grab JSON data if there...
            $sparams = (isset($_REQUEST['data'])) ? json_decode(stripslashes($_REQUEST['data'])) : null;

            if (isset($_REQUEST['data'])) {
                $sparams =  json_decode(stripslashes($_REQUEST['data']));
            } else {
                $raw  = '';
                $httpContent = fopen('php://input', 'r');
                while ($kb = fread($httpContent, 1024)) {
                    $raw .= $kb;
                }
                $params = json_decode(stripslashes($raw));
                if ($params) {
                    $sparams = $params->data;
                }
            }

        }
		return (array)$sparams;
}

/////////////////////////////////////////////////
function ent2utf($str){
	$json = preg_replace_callback('/u(\w\w\w\w)/', 
		function($matches){ 
			return  '&#'.hexdec($matches[1]).';';
		},
		json_encode($str));
//		echo $json;
	return mb_convert_encoding( $json,'UTF-8', 'HTML-ENTITIES');
}

/////////////////////////////////////////////////
function setFilter($filters, $pre=''){
// GridFilters sends filters as an Array if not json encoded
if (is_array($filters)) {
    $encoded = false;
} else {
    $encoded = true;
    $filters = json_decode($filters);
}

$where = '';
$qs = ' AND 0 = 0 ';

// loop through filters sent by client
if (is_array($filters)) {
    for ($i=0;$i<count($filters);$i++){
        $filter = $filters[$i];
//		print_r($filters);
//echo '***test='.$filter->field;
        // assign filter data (location depends if encoded or not)
        if ($encoded) {
            $field = $filter->field;
            $value = $filter->value;
            $compare = isset($filter->comparison) ? $filter->comparison : null;
            $filterType = $filter->type;
        } else {
            $field = $filter['field'];
            $value = $filter['data']['value'];
            $compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
            $filterType = $filter['data']['type'];
        }

//		echo "test: ".$field."=>".$value."<br>";
		
        switch($filterType){
            case 'string' : $qs .= " AND upper(".$field.") LIKE upper('%".$value."%')"; Break;
            case 'list' :
				if( is_array($value) ) {
/*                if (strstr($value,',')){
                    $fi = explode(',',$value);
                    for ($q=0;$q<count($fi);$q++){
                        $fi[$q] = "'".$fi[$q]."'";
                    } */
					if($field == 'kladr_n-y'){
						if( $value[0] == '1' or $value[1] == '1'){
							$qs .= " AND KLADR_NEED = '1'";
						}
						if( $value[0] == '2' or $value[1] == '2'){
							$qs .= " AND KLADR_YES = '1'";
						}
					} else {
						$value = implode(',',$value); 
						$qs .= " AND ".$field." IN (".$value.")";
					}
                }else{
					if($field == 'kladr_n-y'){
						if( $value == '1'){
							$qs .= " AND KLADR_NEED = '1'";
						}
						if( $value == '2'){
							$qs .= " AND KLADR_YES = '1'";
						}
					} else {
						$qs .= " AND ".$field." = '".$value."'";
					}
                }
            Break;
            case 'boolean' :
				if ($value){$qs .= " AND ".$field." = '".($value)."'";}
				else {$qs .= " AND ( ".$field." = '0' OR ".$field." IS NULL )"; }
				Break;
            case 'numeric' :
                switch ($compare) {
                    case 'ne' : $qs .= " AND ".$field." != ".$value; Break;
                    case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
                    case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
                    case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
                }
            Break;
            case 'date' :
                switch ($compare) {
                    case 'ne' : $qs .= " AND ".$field." != '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
                    case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
                }
            Break;
			default:
				$qs .= " AND ".$field." = ".$value;
				
        }
		if($pre){
			$qs .= $pre;
		}
    }
//	echo $qs;
    $where .= $qs;
	return $where;
}
}
////////////////////////////////////////////////////////////////////////////////////
/////////// LOGIN

function setMemberSession($login, $password) {
  //session_start();
  $_SESSION["login"]=$login;
  $_SESSION["password"]=$password;
  $_SESSION["loggedIn"]=true;
} 

function flushMemberSession() {
  unset($_SESSION["login"]);
  unset($_SESSION["password"]);
  unset($_SESSION["loggedIn"]);
  session_destroy();
  return true;
} 

function checkLoggedIn($status){
  switch($status){
    case "yes":
      if(!isset($_SESSION["loggedIn"])){
        header("Location: login.php");
        exit;
      }
      break;
    case "no":
      if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true ){
        header("Location: index.php");
      }
      break;
  }
  return true;
} 

function checkPass($login, $password) {
	$link = ConnectLOCAL();
	$password = md5($password);
  $query="SELECT count(*) cnt FROM users WHERE username='$login' and password='$password'";
  $result=mysql_query($query, $link)
    or die("checkPass fatal error: ".mysql_error());
  $row = mysql_fetch_assoc($result);
  if($row['cnt']==1) {
    //$row=mysql_fetch_array($result);
	mysql_close();
    return true;
  }
  mysql_close();
  return false;
}
//////////////////////////////////////////////////////////////
//  Excel
// Redirect output to a client’s web browser (Excel2007)
function xlsxOutFile($objPHPExcel, $fname){
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$fname.'.xlsx');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
}
	/**
	 *	String from columnindex
	 *
	 *	@param	int $pColumnIndex Column index (base 0 !!!)
	 *	@return	string
	 */
function stringFromColumnIndex($pColumnIndex = 0)
	{
		//	Using a lookup cache adds a slight memory overhead, but boosts speed
		//	caching using a static within the method is faster than a class static,
		//		though it's additional memory overhead
		static $_indexCache = array();

		if (!isset($_indexCache[$pColumnIndex])) {
			// Determine column string
			if ($pColumnIndex < 26) {
				$_indexCache[$pColumnIndex] = chr(65 + $pColumnIndex);
			} elseif ($pColumnIndex < 702) {
				$_indexCache[$pColumnIndex] = chr(64 + ($pColumnIndex / 26)) .
											  chr(65 + $pColumnIndex % 26);
			} else {
				$_indexCache[$pColumnIndex] = chr(64 + (($pColumnIndex - 26) / 676)) .
											  chr(65 + ((($pColumnIndex - 26) % 676) / 26)) .
											  chr(65 + $pColumnIndex % 26);
			}
		}
		return $_indexCache[$pColumnIndex];
}

//*****************************************************************************//
function mail_send($arr)
 {
     if (!isset($arr['to_email'], $arr['from_email'], $arr['subject'], $arr['message'])) {
         throw new HelperException('mail(); not all parameters provided.');
     }
     
     $to            = empty($arr['to_name']) ? $arr['to_email'] : '"' . mb_encode_mimeheader($arr['to_name']) . '" <' . $arr['to_email'] . '>';
     $from        = empty($arr['from_name']) ? $arr['from_email'] : '"' . mb_encode_mimeheader($arr['from_name']) . '" <' . $arr['from_email'] . '>';
     
     $headers    = array
     (
         'MIME-Version: 1.0',
         'Content-Type: text/html; charset="UTF-8";',
         'Content-Transfer-Encoding: 7bit',
         'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
         'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER["SERVER_NAME"] . '>',
         'From: ' . $from,
         'Reply-To: ' . $from,
         'Return-Path: ' . $from,
         'X-Mailer: PHP v' . phpversion(),
         'X-Originating-IP: ' . $_SERVER["SERVER_ADDR"],
     );
     
     mail($to, '=?UTF-8?B?' . base64_encode($arr['subject']) . '?=', $arr['message'], implode("\n", $headers));
 }

function str_ends($string,$end){
     return (substr($string,-strlen($end),strlen($end)) === $end);
}    
function str_begins($string,$start){
     return (substr($string,0,strlen($start)) === $start);
}

///////////////////////////
function nl() {
     echo "<br/> \n";
}

function user_dn_container($user_dn){
	$a_dn = explode(",", $user_dn);
	$o = array_slice($a_dn, 1);
	return implode(",", $o);
}

function user_dn_cn($user_dn){
	$a_dn = explode(",", $user_dn);
	return $a_dn[0];
}



?>