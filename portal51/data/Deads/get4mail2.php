<?php
header('Content-type: text/html; charset=utf-8');


$connect = open_mailbox('10.51.100.252', 'zags2d', 'javascript')  or die("can't connect: ".imap_last_error()); 

	//print_r($body);
	
	
$mails = imap_search($connect, 'UNSEEN');

foreach( $mails as $mail ){


$structure = imap_fetchstructure($connect, $mail);
	$boundary = '';
	if ($structure->ifparameters) {
		foreach ($structure->parameters as $param)	{
			if (strtolower($param->attribute) == 'boundary')
				$boundary = $param->value;
		}
	}
	
	$parts = array();
	// Get allparts to $parts
	getParts($structure, $parts);
	 
	if ($structure->type == 1) {
		$parts = array();
	// Get allparts to $parts
		getParts($structure, $parts);
		
		$email['body'] = imap_fetchbody($connect, $mail, '1');
		$email['body'] = imap_utf8((getPlain($email['body'], $boundary)));
		$email['body'] = iconv('KOI8-R', 'utf-8', $email['body']);
		// Get attach
		$i = 0;

		foreach ($parts as $part) {
		// Not text or multipart
			if ($part['type'] > 1) {
				$file = imap_fetchbody($connect, $mail, $i);
				$email['files'][] = array('content'  => base64_decode($file),
										'filename' => $part['params'][0]['val'],
										'size'     => $part['bytes']);
			}
			$i++;
		}
	} else {
		$email['body'] = imap_body($connect, $mail);
		$email['body'] = imap_utf8((getPlain($email['body'], $boundary)));
		$email['body'] = iconv('KOI8-R', 'utf-8', $email['body']);
	}	
	
	$header = imap_header($connect, $mail);
	
	$email['subject'] = imap_utf8($header->subject);
	if (isset($header->to[0]->personal))
		$email['to']['personal'] = imap_utf8($header->to[0]->personal);
	else
		$email['to']['personal'] = '';
	$email['to']['mailbox'] = imap_utf8($header->to[0]->mailbox);
	$email['to']['host'] = imap_utf8($header->to[0]->host);

	if (isset($header->from[0]->personal))
		$email['from']['personal'] = imap_utf8($header->from[0]->personal);
	else
		$email['from']['personal'] = '';
	$email['from']['mailbox'] = imap_utf8($header->from[0]->mailbox);
	$email['from']['host'] = imap_utf8($header->from[0]->host);
	$email['maildate'] = imap_utf8($header->MailDate);
	$email['date'] = strtotime(imap_utf8($header->date));
	$email['udate'] = imap_utf8($header->udate);
	$email['size'] = imap_utf8($header->Size);
	$email['id'] = md5($header->message_id);
	
	print_r($email);
	
	
	
}
	
	
	
	
	
	
	
	
	
	
	
// закрываем соединение
//imap_close($mail); //,CL_EXPUNGE);


function getPlain($str, $boundary)	{
	$lines = explode("\n", $str);
	$plain = false;
	$res = '';
	$start = false;

	foreach ($lines as $line) {
		if (strpos($line, 'text/plain') !== false) $plain = true;
		if (strlen($line) == 1 && $plain) {
			$start = true;
			$plain = false;
			continue;
		}

		if ($start && strpos($line, 'Content-Type') !== false) $start = false;
		if ($start)
			$res .= $line;
	}

	$res = substr($res, 0, strpos($res, '--' . $boundary));
	$res = base64_decode($res == '' ? $str : $res);
	return $res;
}



function getParts($object, & $parts) {
	// Object is multipart
	if ($object->type == 1) {
		foreach ($object->parts as $part){
			getParts($part, $parts);
		}
	} else	{
		$p['type'] = $object->type;
		$p['encode'] = $object->encoding;
		$p['subtype'] = $object->subtype;
		$p['bytes'] = $object->bytes;
		if ($object->ifparameters == 1) {
			foreach ($object->parameters as $param) {
				$p['params'][] = array('attr' => $param->attribute,	'val'  => $param->value);
			}
		}
		
		if ($object->ifdparameters == 1) {
			foreach ($object->dparameters as $param) {
				$p['dparams'][] = array('attr' => $param->attribute, 'val'  => $param->value);
			}
		}
		$p['disp'] = null;
		if ($object->ifdisposition == 1) {
			$p['disp'] = $object->disposition;
		}
		$parts[] = $p;
	}
}



	
	
function open_mailbox($servername, $userlogin, $userpassword) {
   $imaphost = "{".$servername.":7110/pop3/novalidate-cert"."}"; //."INBOX";
   //$imaphost = "{mydomain.com:995/pop3/ssl/novalidate-cert}";
   $imapmsgbox = imap_open ($imaphost, $userlogin, $userpassword);
   return $imapmsgbox;
}



?>