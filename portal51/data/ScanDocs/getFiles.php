<?php
include_once("{$_SERVER['DOCUMENT_ROOT']}/php/init.php");
//header('Content-type: text/html; charset=utf-8');
//set_time_limit(300);

/*-------------------------- Входные переменные -----------------------------*/
//$Org_id = trim ((!empty($_GET['org'])) ? $_GET['org'] : "1" ); // 
$params = $_POST; 
/*---------------------------------------------------------------------------*/

//    $db = ConnectMyDB('Scan_docs');

/*---------------------------------------------------------------------------*/

$start_dir = "/mnt/archivedocs/";
$dirScan = 'scans/';
$dirTemp = 'temp/';




class getFiles {

    private $_db;
    protected $_result;
    public $results;

    public function __construct() {
        $this->_db = ConnectMyDB('Scan_docs');
		//new mysqli('host', 'username' ,'password', 'database');

        $_db = $this->_db;

        if ($_db->connect_error) {
            die('Connection Error: ' . $_db->connect_error);
        }

        return $_db;
    }

    public function getResults($params) {
        $_db = $this->_db;

        $_result = $_db->query("SELECT name,email,phone FROM heroes") or
                   die('Connection Error: ' . $_db->connect_error);

        $results = array();

        while ($row = $_result->fetch_assoc()) {
            array_push($results, $row);
        }

        $this->_db->close();

        return $results;
    }

 }






/*


$a = explode('/',$_SERVER["DOCUMENT_ROOT"]);
//print_r($a);
	
	if( $a[1] == 'var'){
		$rp = $start_dir.$dirScan;
		$rt = $start_dir.$dirTemp;
/*	} else {
		//$rp = 'file://///ARCHIVESHARE/Archive/'.$dirScan.'/';
		$rp = '\\\\ARCHIVESHARE\\Archive\\'.$dirScan.'\\';
		$rt = '\\\\ARCHIVESHARE\\Archive\\'.$dirTemp.'\\'; */
/*	}

$DPD = str_replace(" ","_", 'DPD_'.substr($params['name'],0,13) );	
	
$url_text="[InternetShortcut]
URL=file:///\\ARCHIVESHARE/Archive/". $dirScan . $DPD;
//echo $url_text."<br>";
	
	// Исходный каталог с Делом
	$dir_source = urldecode(  $start_dir . $params['path'] . '/' . $params['cyear'] . '/' . $params['name'] );
	// Каталог для обработки Дела
	$dir_dest = $rp . $DPD;
		
	// 
	@mkdir( $dir_dest ); //, null, true );
	@mkdir( $dir_dest . '/' . $params['name'] );
	
	// URL-файл для доступа к этому каталогу
	$temp_URL = $DPD.'.url'; //time().'.url';
	
	//echo $html;
	$fh = fopen($rt.$temp_URL,"w+");
	fwrite( $fh, $url_text );
	fclose( $fh );

	//file_force_download2($temp_name);

	// Копируем выбранное Дело для обработки в Платформе Ретроконверсии
	full_copy($dir_source,$dir_dest . '/' . $params['name']);
	//$output = shell_exec( " cp -r -a $dir_source/* $dir_dest 2>&1 " );
	//echo $output;
	$db->query( "UPDATE docs2 SET retro=1 WHERE id={$_POST['id']}" );

	$c = array('success'=>0,'file'=>'data/ScanDocs/temp/'.$temp_URL,'folder'=>$DPD);
	echo json_encode($c);
	

function full_copy($source, $target) {
	if (is_dir($source))  {
		@mkdir($target);
		$d = dir($source);
		while (FALSE !== ($entry = $d->read())) {
			if ($entry == '.' || $entry == '..') continue;
			full_copy("$source/$entry", "$target/$entry");
		}
		$d->close();
	}else{
		if( substr($source,-4)==".pdf" ){
			copy($source, $target);
		}
	}
}

	
function cpy($source, $dest){
    if(is_dir($source)) {
        $dir_handle=opendir($source);
        while($file=readdir($dir_handle)){
            if($file!="." && $file!=".."){
                if(is_dir($source."/".$file)){
                    if(!is_dir($dest."/".$file)){
                        mkdir($dest."/".$file);
                    }
                    cpy($source."/".$file, $dest."/".$file);
                } else {
					if( substr($file,-4)==".pdf" ){
						copy($source."/".$file, $dest."/".$file);
					}
                }
            }
        }
        closedir($dir_handle);
    } else {
		if( substr($source,-4)==".pdf" ){
			copy($source, $dest);
		}
    }
}
*/	
?>