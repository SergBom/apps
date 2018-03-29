<?php 
header('Content-type: text/html; charset=utf-8');
// Включаем библиотеку mime parser
require_once('receipt/rfc822_addresses.php');
require_once('receipt/mime_parser.php');

$mime = new mime_parser_class;

error_reporting(E_ALL ^ E_WARNING);
ob_implicit_flush();

// Email хостинг провайдера получил здесь
// http://api.hostinger.com.ua/redir/401579

$address = "10.51.100.252";  // адрес pop3-сервера 
$port    = "7110";          // порт (стандартный pop3 - 110)

$login   = "zags2d";    // логин к ящику
$pwd     = "javascript";    // пароль к ящику

try {
	
	// Создаем и соединяем сокет к серверу
	echo '<p>Соединение с \''.$address.':'.$port.'\' ... ';
	$socket = fsockopen($address, $port, $errno, $errstr);
	if (!$socket) {
		throw new Exception('fsockopen() failed: '.$errstr."<br>");
	}
	echo "открыто! </p>";
	
	// Читаем +OK
	read_pop3_answer($socket);

	// Делаем авторизацию
	echo '<p>Авторизация ... ';

	write_pop3_response($socket, 'USER '.$login);
	read_pop3_answer($socket); // ответ сервера
	
	write_pop3_response($socket, 'PASS '.$pwd);
	read_pop3_answer($socket); // ответ сервера

	echo "прошла успешно! </p>";
	
	// Определяем кол-во сообщений в ящике и общий размер
	write_pop3_response($socket, 'STAT');
	$answer = read_pop3_answer($socket); // ответ сервера
	
	preg_match('!([0-9]+)[[:space:]]([0-9]+)!is', $answer, $matches);
	$total_count = $matches[1];
	
	echo "<p>".'Всего сообщений: <strong>'.$total_count."</strong></p>";
	
	if ($total_count > 0) {
		echo '<p>Общий размер: <strong>'.ceil($matches[2] / 1024)." Kb</strong></p>";
	}
	
	$iii = 0;
	
	// Просматриваем параметры каждого сообщения
	for ($i = 1; $i <= $total_count; $i++) 
	{
		
		write_pop3_response($socket, 'TOP '.$i.' 0');
		$answer = read_pop3_answer($socket, true);
		
		write_pop3_response($socket, 'LIST '.$i);
		$answer2 = read_pop3_answer($socket);
		
		// Линия
		echo '<HR NOSHADE WIDTH="100%" COLOR="#023C47" SIZE="10">';	
		
		// Все сообщение		
		echo "<p>СООБЩЕНИЯ $i НАЧАЛО answer</p>";
						
		echo "<pre style=' font-size:20px; font-family:Calibri; padding-left: 6px;'>answer: $answer</pre>";
		
		echo "<p>СООБЩЕНИЯ $i КОНЕЦ answer</p>";
		
		// Определяем тему сообщения		
		preg_match('!Subject:[[:space:]]+(.*?)\n+.*!is', $answer, $matches);
		
		$msg_subject = $matches[1];
					
		echo '<p>Сообщение '.$i.' - Тема: <strong>'.$msg_subject."</strong></p>";
		
		// Определяем содержание сообщения		
		if (preg_match("!Content preview!ism",$answer))
		{
			preg_match('!Content preview:[[:space:]]+(.*?)\[...\]!is', $answer, $matches);
			$msg_content_preview = $matches[1];
		}
		
		if(!isset($msg_content_preview))
		{
			$msg_content_preview = "-"; //  [Name: Name]
		}
		
		echo '<p style="width:1000px;">Сообщение '.$i.' - Содержание: <strong>'.$msg_content_preview."</strong></p>";				
			
		
		// Определяем дату сообщения
		preg_match('!Date:[[:space:]]+(.*?)\n+.*!is', $answer, $matches);
		$msg_date_answer = date('d.m.Y H:i:s', strtotime($matches[1]));
		echo '<p>Сообщение '.$i.' - Дата: <strong>'.$msg_date_answer."</strong></p>";		
		
		// Определяем отправителя сообщения Return			
		preg_match('!Return-path:[[:space:]]+(.*?)\n+.*!is', $answer, $matches);
		preg_match('|<(.*?)>|is', $matches[1], $matches3);
		$return_path = $matches3[1];
		
		echo '<p>Сообщение '.$i.' - Отправитель (Return-path): <strong>'.$return_path."</strong> </p>";
		
		// Определяем отправителя сообщения From
		preg_match('!From:[[:space:]]+(.*?)\n+.*!is', $answer, $matches1);
		preg_match('|<(.*?)>|is', $matches1[1], $matches2);

		// Определяем тип сообщения		
		preg_match('!Content-Type:[[:space:]]+(.*?)\n+.*!is', $answer, $matches);
		$msg_type = $matches[1];
		
		echo '<p> Сообщение '.$i.' - Тип: <strong>'.$msg_type."</strong></p>";
					
		$ctype = explode (";",$msg_type);
		$types = explode ("/",$ctype[0]);
		$maintype = trim(strtolower($types[0])); // text или multipart
		$subtype = trim(strtolower($types[1])); // а это подтип(plain, html, mixed)

		// Определяем получателя сообщения		
		preg_match('!To:[[:space:]]+(.*?)\n+.*!is', $answer, $matches);
		$msg_SetFrom_email = $matches[1];
		echo '<p> Сообщение '.$i.' - Получатель: <strong>'.$msg_SetFrom_email."</strong></p>";			
		
		// Определяем размер сообщения
		preg_match('!^\+[A-Za-z]+[[:space:]]+[0-9]+[[:space:]]+([0-9]+)!is', $answer2, $matches);
		$msg_size = ceil($matches[1] / 1024);
		echo '<p> Сообщение '.$i.' - Размер: <strong>'.$msg_size."</strong> Kb</p>";			

	}
	
	echo '<HR NOSHADE WIDTH="100%" COLOR="#023C47" SIZE="10">';	
	
	// Отсоединяемся от сервера
	echo "<p>".'Соединение ... ';
	
	write_pop3_response($socket, 'QUIT');
	read_pop3_answer($socket); // ответ сервера
	
	echo "закрыто.</p>";
	
} catch (Exception $e) {
	echo "\nError: ".$e->getMessage();
}

if (isset($socket)) {
	fclose($socket);
}

// Функция для чтения ответа сервера. Выбрасывает исключение в случае ошибки
function read_pop3_answer($socket, $top = false) {
	$read = fgets($socket);
	
	if ($top) {
	
		// Если читаем заголовки
		$line = $read;
		
		while (!preg_match("^\.\r\n", $line)) {
		
			$line  = fgets($socket);
			$read .= $line;
			
		}
		
	}
	
	if ($read{0} != '+') {
		if (!empty($read)) {
			throw new Exception('<p>POP3 failed: '.$read."</p>");
		} else {
			throw new Exception('<p>Unknown error'."</p>");
		}
	}
	
	return $read;
}

// Функция для отправки запроса серверу
function write_pop3_response($socket, $msg) {
	$msg = $msg."\r\n";
	fwrite($socket, $msg);
}
echo "</div>";
	
?>