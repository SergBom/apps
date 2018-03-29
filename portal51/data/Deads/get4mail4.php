<?php
$isCLI = ( php_sapi_name() == 'cli' );
$_include_path = ($isCLI) ? "/var/www/portal/public_html" : $_SERVER['DOCUMENT_ROOT'];
include_once($_include_path."/php/init2.php");
header('Content-type: text/html; charset=utf-8');
/*-------------------------- Входные переменные -----------------------------*/
$uploaddir = "files/";
/*---------------------------------------------------------------------------*/
    $db = ConnectPDO('Deads');
/*---------------------------------------------------------------------------*/
 
 $f = array();

//$connect = pop3_login('10.51.100.252', '7110', 'zags2d', 'javascript')  or die("can't connect: ".imap_last_error()); 

//error_reporting(0);

require_once("functions.php");

$mail_login    = "zags2d";
$mail_password = "javascript";
$mail_imap 	   = "{10.51.100.252:7143/imap/novalidate-cert}";

// Список учитываемых типов файлов
$mail_filetypes = array(
	//"MSWORD","OCTET-STREAM",
	"PLAIN"
	//,"JPEG"
);

$spam = true;
$spam_box = "Junk";

$log = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';

$connection = imap_open($mail_imap, $mail_login, $mail_password);

if(!$connection){

	$log = "Ошибка соединения с почтой - ".$mail_login. "\n";
//	echo("Ошибка соединения с почтой - ".$mail_login);
//	exit;
}else{

	$msg_num = imap_num_msg($connection);
	$log .= "Mails All = $msg_num<hr>\n";
	//echo "Mails All = $msg_num<hr>";
	
	if( $msg_num > 0 ) {

		$mails_data = array(); //Массив с письмами

		for($i = 1; $i <= $msg_num; $i++){
	

			/*
			Работать с каждым письмом 
			из IMAP-потока будем тут
			*/
		
			// Шапка письма
			$msg_header = imap_header($connection, $i);
		
			$mails_data[$i]["time"] = time($msg_header->MailDate);
			$mails_data[$i]["date"] = $msg_header->MailDate;

			foreach($msg_header->to as $data){
				$mails_data[$i]["to"] = $data->mailbox."@".$data->host;
				$log .= "To: ".$data->mailbox."@".$data->host."<br>\n";
			}

			foreach($msg_header->from as $data){
				$mails_data[$i]["from"] = $data->mailbox."@".$data->host;
				$log .= "From: ".$data->mailbox."@".$data->host."<br>\n";
			}		
		
			$mails_data[$i]["title"] = get_imap_title($msg_header->subject);
			$log .= "Title: ".$mails_data[$i]["title"]."<br>\n";

		
			// Тело письма
			$msg_structure = imap_fetchstructure($connection, $i);
			$msg_body 	   = imap_fetchbody($connection, $i, 1);
		
			$body = "";
		
			$recursive_data = recursive_search($msg_structure);

			if($recursive_data["encoding"] == 0 ||	$recursive_data["encoding"] == 1){
				$body = $msg_body;
			}

			if($recursive_data["encoding"] == 4){
				$body = structure_encoding($recursive_data["encoding"], $msg_body);
			}

			if($recursive_data["encoding"] == 3){
				$body = structure_encoding($recursive_data["encoding"], $msg_body);
			}

			if($recursive_data["encoding"] == 2){
				$body = structure_encoding($recursive_data["encoding"], $msg_body);
			}

			if(!check_utf8($recursive_data["charset"])){
				$body = convert_to_utf8($recursive_data["charset"], $msg_body);
			}
		
			$mails_data[$i]["body"] = base64_encode($body);
			//$log .= "Message:<br>\n" . $mails_data[$i]["body"] . "<br>\n";
		
			// Вложенные файлы
			if(isset($msg_structure->parts)){
				

				for($j = 1, $f = 2; $j < count($msg_structure->parts); $j++, $f++){

					if(in_array($msg_structure->parts[$j]->subtype, $mail_filetypes)){

						$mails_data[$i]["attachs"][$j]["type"] = $msg_structure->parts[$j]->subtype;
						$mails_data[$i]["attachs"][$j]["size"] = $msg_structure->parts[$j]->bytes;
						//echo "'_ " . $msg_structure->parts[$j]->parameters[0]->value . " _'<br>";
						$mails_data[$i]["attachs"][$j]["name"] = get_imap_title($msg_structure->parts[$j]->parameters[0]->value);
						//echo "'_ " . $mails_data[$i]["attachs"][$j]["name"] . " _'<br>";
						$mails_data[$i]["attachs"][$j]["file"] = structure_encoding(
							$msg_structure->parts[$j]->encoding,
							imap_fetchbody($connection, $i, $f)
						);

						$fname = "/var/www/portal/public_html/portal51/data/Deads/files/".iconv("utf-8", "cp1251", $mails_data[$i]["attachs"][$j]["name"]);
						$log .= "File: $fname<br>\n";
						//echo "file: $fname<br>";
						if( file_exists($fname) ){ unlink($fname); }
						if( file_put_contents($fname, $mails_data[$i]["attachs"][$j]["file"]) ){
					
							///*******************************************///
							/// Обработка файла
							//$fname = 'files/DOC_0000275231.txt';
				
							$f_str = file($fname);
					
							$marker = trim($f_str[0]);
							if( preg_match('/==ЗАГС==/i', $marker) ){ // == '==ЗАГС=='){
								
								$spam = false;
								
								$log .= "<b>$marker</b><br>\n";

								$s = explode('|',$f_str[1]);
								$place = rtrim($s[0]);

								for ( $fi=2; $fi<count($f_str); $fi++ ){
				
									$str = explode('|',$f_str[$fi]);
				
									if( trim($str[0]) <> '' ){
				
										$FIO = trim($str[0]);
										$DR = dpars($str[1]);
										$MR = trim($str[2]);
										$Period1 = dpars($str[3]);
//										$Period2 = dpars($str[4]);
				
										$sql = "INSERT INTO main SET
										FIO='$FIO', DR='$DR', MR='$MR', Period1='$Period1', Period2='$Period1', Place='$place'
										ON DUPLICATE KEY UPDATE FIO='$FIO', DR='$DR', Place='$place'
										";

										$log .=  $sql."<br>\n";
					
										$db->query($sql);
					
									}
								}
							} else {
								// Файл не из ЗАГСА
								$log .= "*** Файл не из ЗАГСА *** '$marker' ***<br>\n";
								$spam = true;
							}
							// delete file
							unlink($fname);
						} else {
							// Не смогли сохранить файл
							$log .= "*** Не смогли сохранить файл ***<br>\n";
						}
						///*******************************************///
					}
				}
			} else {
				// нет вложенных файлов
				$log .= "*** Нет вложенных файлов ***<br>\n";
			} 
		
/*			echo "Mail[$i]:<br>";
			print_r($mails_data[$i]);
			echo "<hr>";
	*/	
			if( $spam ){
				//$messageset = implode(",",$i);
				imap_mail_move($connection,"$i",$spam_box);
			} else {
				imap_delete($connection,$i);
			}
			
			$log .= "<br><hr><br>";
		}
		imap_expunge($connection);
		$log .= '</body></html>';

		$Name = "Da Duder"; //senders name 
		$email = "zags2d@r51.rosreestr.ru"; //senders e-mail adress 
		$recipient = "admin@r51.rosreestr.ru"; //recipient 
		//$mail_body = "The text for the mail..."; //mail body 
		$subject = "Отчет по принятым письмам"; //subject 
		$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields 

		//mail($recipient, $subject, $log, $header); //mail command :) 
		mail_send( array(
			'to_email' => 'admin@r51.rosreestr.ru',
			'from_email' => 'zags2d@r51.rosreestr.ru',
			'subject' => 'Отчет по принятым письмам',
			'message' => $log
		));
	} // $msg_num>0

	imap_close($connection);
}






/*
function pop3_login($host,$port,$user,$pass,$folder="INBOX",$ssl=false) 
{ 
    $ssl=($ssl==false)?"/novalidate-cert":""; 
    return (imap_open("{"."$host:$port/pop3$ssl"."}$folder",$user,$pass)); 
} 
*/
function dpars($d){
	//$R = date_parse($d);
	$R = preg_split('/\//',$d);
	//return $R['year'] .".". $R['month'] .".". $R['day'];
	return $R[2] .".". $R[1] .".". $R[0];
}

?>