<?php
header('Content-type: text/html; charset=utf-8');
//$mail = imap_open('{10.51.100.252:7110/pop3}INBOX', 'zags2d@r51.rosreestr.ru', 'javascript'); 


$mail = open_mailbox('10.51.100.252', 'zags2d', 'javascript')  or die("can't connect: ".imap_last_error()); 

/*$m=imap_body($mail,$read); 

$headers = imap_headers($mail);

$header = imap_header($message_number);
$body = imap_body($message_number);
*/


// берем объект заголовка для последнего сообщения в почтовом ящике
$last = imap_num_msg($mail);
echo "Сообщений: ". $last ."<br>";

if( $last <> 0 ){
	// берем список всех почтовых заголовков
//	$headers = imap_headers($mail);

	$header = imap_header($mail, $last);
	//echo "<br>Заголовок:<br>";
	//print_r($header);
	
	
	$subj = mb_decode_mimeheader(  $header->subject);
	echo "<br>subj='$subj'<br>";
	
	
	

	// выбираем тело для того же сообщения
	$body = imap_body($mail, $last);
	echo "<br>Тело:<br>";
	print_r($body);

	echo "<br>Тело:<br>";

	$Msgno = $n = $last;
	$mbox = $mail;


	$struct = imap_fetchstructure($mbox,$Msgno);
	$body = imap_fetchbody($mbox,$Msgno,1);	
	echo "<br><u>struct->encoding='".$struct->encoding."'</u><br>";
	print_r($struct);

	if (trim($struct->encoding)=='4')
		$body = imap_qprint($body);
	if (trim($struct->encoding)=='3')
		$body = imap_base64($body);
	$parms = $struct->parameters[0];
	$encoding = $parms->value;
	//$body = iconv($encoding,'windows-1251',$body);

//	print_r($body);









/*
	
	// выбираем текст для сообщения $n
	$st = imap_fetchstructure($mail, $n);
	if (!empty($st->parts)) {

		for ($i = 0, $j = count($st->parts); $i < $j; $i++) {
			$part = $st->parts[$i];
			if ($part->subtype == 'PLAIN') {

				$body = imap_fetchbody($mail, $n, $i+1);

				if ($part->encoding==4)
					$body = quoted_printable_decode($body);
				elseif ($part->encoding==3)
					$body = base64_decode($body);

			}
		}
	} else {
		$body = imap_body($mail, $n);

		$part = $st->parts;
		if ($part->encoding==4)
			$body = quoted_printable_decode($body);
		elseif ($part->encoding==3)
			$body = base64_decode($body);

	}	
	
*/
	print_r($body);
	
	//imap_delete($mail,$last);
	
	//getmsg($mail,$last);


}	
	
// закрываем соединение
imap_close($mail,CL_EXPUNGE);
	
	
function open_mailbox($servername, $userlogin, $userpassword) {
   $imaphost = "{".$servername.":7110/pop3/novalidate-cert"."}"; //."INBOX";
   //$imaphost = "{mydomain.com:995/pop3/ssl/novalidate-cert}";
   $imapmsgbox = imap_open ($imaphost, $userlogin, $userpassword);
   return $imapmsgbox;
}

function getmsg($mbox,$mid) {
    // input $mbox = IMAP stream, $mid = message id
    // output all the following:
    global $charset,$htmlmsg,$plainmsg,$attachments;
    $htmlmsg = $plainmsg = $charset = '';
    $attachments = array();

    // HEADER
    $h = imap_header($mbox,$mid);
    // add code here to get date, from, to, cc, subject...

    // BODY
    $s = imap_fetchstructure($mbox,$mid);
    if (!$s->parts)  // simple
        getpart($mbox,$mid,$s,0);  // pass 0 as part-number
    else {  // multipart: cycle through each part
        foreach ($s->parts as $partno0=>$p)
            getpart($mbox,$mid,$p,$partno0+1);
    }
}

function getpart($mbox,$mid,$p,$partno) {
    // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
    global $htmlmsg,$plainmsg,$charset,$attachments;

    // DECODE DATA
    $data = ($partno)?
        imap_fetchbody($mbox,$mid,$partno):  // multipart
        imap_body($mbox,$mid);  // simple
    // Any part may be encoded, even plain text messages, so check everything.
    if ($p->encoding==4)
        $data = quoted_printable_decode($data);
    elseif ($p->encoding==3)
        $data = base64_decode($data);

    // PARAMETERS
    // get all parameters, like charset, filenames of attachments, etc.
    $params = array();
    if ($p->parameters)
        foreach ($p->parameters as $x)
            $params[strtolower($x->attribute)] = $x->value;
    if ($p->dparameters)
        foreach ($p->dparameters as $x)
            $params[strtolower($x->attribute)] = $x->value;

    // ATTACHMENT
    // Any part with a filename is an attachment,
    // so an attached text file (type 0) is not mistaken as the message.
    if ($params['filename'] || $params['name']) {
        // filename may be given as 'Filename' or 'Name' or both
        $filename = ($params['filename'])? $params['filename'] : $params['name'];
        // filename may be encoded, so see imap_mime_header_decode()
        $attachments[$filename] = $data;  // this is a problem if two files have same name
    }

    // TEXT
    if ($p->type==0 && $data) {
        // Messages may be split in different parts because of inline attachments,
        // so append parts together with blank row.
        if (strtolower($p->subtype)=='plain')
            $plainmsg .= trim($data) ."\n\n";
        else
            $htmlmsg .= $data ."<br><br>";
        $charset = $params['charset'];  // assume all parts are same charset
    }

    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
    elseif ($p->type==2 && $data) {
        $plainmsg .= $data."\n\n";
    }

    // SUBPART RECURSION
    if ($p->parts) {
        foreach ($p->parts as $partno0=>$p2)
            getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
    }
}


?>