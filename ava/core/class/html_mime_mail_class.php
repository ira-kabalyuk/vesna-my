<?php

class html_mime_mail{

	var $mime;
	var $html;
	var $body;
	var $do_html;
	var $multipart;
	var $html_text;
	var $html_images;
	var $image_types;
	var $build_params;
	var $headers;
	var $parts;
	var $charset;
	var $attachdir;




	function html_mime_mail($headers = ''){
		if(!defined('CRLF'))
			define('CRLF', "\n", TRUE);

		$this->html_images	= array();
		$this->headers		= array();
		$this->parts		= array();
		$this->image_types = array(
									'gif'	=> 'image/gif',
									'jpg'	=> 'image/jpeg',
									'jpeg'	=> 'image/jpeg',
									'jpe'	=> 'image/jpeg',
									'bmp'	=> 'image/bmp',
									'png'	=> 'image/png',
									'tif'	=> 'image/tiff',
									'tiff'	=> 'image/tiff',
									'swf'	=> 'application/x-shockwave-flash'
								  );
		$this->charset = 'utf-8';
		$this->build_params['html_encoding']	= 'quoted-printable';
		$this->build_params['text_encoding']	= '7bit';
		$this->build_params['text_wrap']		= 998;
		$this->headers[] = 'MIME-Version: 1.0';
		$this->attachdir=$_SERVER["DOCUMENT_ROOT"]."/admin/spamer/attach/";

		if($headers == '')
			return TRUE;

		if(is_string($headers))
			$headers = explode(CRLF, trim($headers));

		for($i=0; $i<count($headers); $i++){
			if(is_array($headers[$i]))
				for($j=0; $j<count($headers[$i]); $j++)
					if($headers[$i][$j] != '')
						$this->headers[] = $headers[$i][$j];

			if($headers[$i] != '')
				$this->headers[] = $headers[$i];
		}
		
	}

	function get_file($filename){

		if($fp = fopen($filename, 'rb')){
			$return = fread($fp, filesize($filename));
			fclose($fp);
			return $return;

		}else
			return FALSE;
	}



	function find_html_images($images_dir) {

		// Build the list of image extensions
		while(list($key,) = each($this->image_types))
			$extensions[] = $key;


		//preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', $this->html, $images);
		preg_match_all('/(\/[^\."=]*\.(jpg|gif|png|bmp))/', $this->html, $images);

		for($i=0; $i<count($images[1]); $i++){
		
			if(file_exists($images_dir.$images[1][$i])){
				$html_images[] = $images[1][$i];
				$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
			}
		}

		if(!empty($html_images)){

			// If duplicate images are embedded, they may show up as attachments, so remove them.
			$html_images = array_unique($html_images);
			sort($html_images);
	
			for($i=0; $i<count($html_images); $i++){
				if($image = $this->get_file($images_dir.$html_images[$i])){
					$content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
					$this->add_html_image($image, basename($html_images[$i]), $content_type);
				}
			}
		}
	}


	function add_html($html, $text, $images_dir = NULL){

		$this->do_html		= 1;
		$this->html			= $html;
		$this->html_text	= ($text == '') ? 'No text version was provided' : $text;

		if(isset($images_dir))
			$this->find_html_images($images_dir);
		
		if(is_array($this->html_images) AND count($this->html_images) > 0){
			for($i=0; $i<count($this->html_images); $i++)
				$this->html = str_replace($this->html_images[$i]['name'], 'cid:'.$this->html_images[$i]['cid'], $this->html);
		}
	}

	function add_html_image($file, $name = '', $c_type='application/octet-stream'){
		$this->html_images[] = array(
										'body'   => $file,
										'name'   => $name,
										'c_type' => $c_type,
										'cid'    => md5(uniqid(time()))
									);
	}


	function add_attachment($file, $name = '', $c_type='application/octet-stream'){
	
	$fl=$this->get_file($this->attachdir.$file);
	
		$this->parts[] = array(
								'body'   => $fl,
								'name'   => $name,
								'c_type' => $c_type
							  );
	}


	function quoted_printable_encode($input , $line_max = 76){
	
		$lines	= preg_split("/(?:\r\n|\r|\n)/", $input);
		$eol	= CRLF;
		$escape	= '=';
		$output	= '';
		
		while(list(, $line) = each($lines)){

			$linlen	 = strlen($line);
			$newline = '';

			for($i = 0; $i < $linlen; $i++){
				$char = substr($line, $i, 1);
				$dec  = ord($char);

				if(($dec == 32) AND ($i == ($linlen - 1)))				// convert space at eol only
					$char = '=20';

				elseif($dec == 9)
					;				// Do nothing if a tab.

				elseif(($dec == 61) OR ($dec < 32 ) OR ($dec > 126))
					$char = $escape.strtoupper(sprintf('%02s', dechex($dec)));
	
				if((strlen($newline) + strlen($char)) >= $line_max){	// CRLF is not counted
					$output  .= $newline.$escape.$eol;					// soft line break; " =\r\n" is okay
					$newline  = '';
				}
				$newline .= $char;
			} // end of for
			$output .= $newline.$eol;
		}
		return $output;
	}


	function get_encoded_data($data, $encoding){

		$return = '';

		switch($encoding){

			case '7bit':
				$return .=	'Content-Transfer-Encoding: 7bit'.CRLF.CRLF.
							chunk_split($data, $this->build_params['text_wrap']);
				break;

			case 'quoted-printable':
				$return .=	'Content-Transfer-Encoding: quoted-printable'.CRLF.CRLF.
							$this->quoted_printable_encode($data);
				break;

			case 'base64':
				$return .=	'Content-Transfer-Encoding: base64'.CRLF.CRLF.
							chunk_split(base64_encode($data));
				break;
		}

		return $return;
	}

/***************************************
** Builds html part of email.
***************************************/

	function build_html($orig_boundary){
		$sec_boundary = '=_'.md5(uniqid(time()));
		$thr_boundary = '=_'.md5(uniqid(time()));

		if(count($this->html_images) == 0){
			$this->multipart .= '--'.$orig_boundary.CRLF.
								'Content-Type: multipart/alternative;'.CRLF.chr(9).'boundary="'.$sec_boundary.'"'.CRLF.CRLF.
								'--'.$sec_boundary.CRLF.
								'Content-Type: text/plain; charset="'.$this->charset.'"'.CRLF.
								$this->get_encoded_data($this->html_text, $this->build_params['text_encoding']).CRLF.
								'--'.$sec_boundary.CRLF.
								'Content-Type: text/html; charset="'.$this->charset.'"'.CRLF.
								$this->get_encoded_data($this->html, $this->build_params['html_encoding']).CRLF.
								'--'.$sec_boundary.'--'.CRLF.CRLF;

		}else{

			$this->multipart .= '--'.$orig_boundary.CRLF.
								'Content-Type: multipart/related;'.CRLF.chr(9).'boundary="'.$sec_boundary.'"'.CRLF.CRLF.
								'--'.$sec_boundary.CRLF.
								'Content-Type: multipart/alternative;'.CRLF.chr(9).'boundary="'.$thr_boundary.'"'.CRLF.CRLF.
								'--'.$thr_boundary.CRLF.
								'Content-Type: text/plain; charset="'.$this->charset.'"'.CRLF.
								$this->get_encoded_data($this->html_text, $this->build_params['text_encoding']).CRLF.
								'--'.$thr_boundary.CRLF.
								'Content-Type: text/html; charset="'.$this->charset.'"'.CRLF.
								$this->get_encoded_data($this->html, $this->build_params['html_encoding']).CRLF.
								'--'.$thr_boundary.'--'.CRLF;

			for($i=0; $i<count($this->html_images); $i++){
				$this->multipart .= '--'.$sec_boundary.CRLF;
				$this->build_html_image($i);
			}

			$this->multipart .= '--'.$sec_boundary.'--'.CRLF;
		}
	}


	function build_html_image($i){
		$this->multipart .= 'Content-Type: '.$this->html_images[$i]['c_type'];

		if($this->html_images[$i]['name'] != '')
			$this->multipart .= '; name="'.$this->html_images[$i]['name'].'"'.CRLF;
		else
			$this->multipart .= CRLF;

		$this->multipart .= 'Content-ID: <'.$this->html_images[$i]['cid'].'>'.CRLF;
		$this->multipart .= $this->get_encoded_data($this->html_images[$i]['body'], 'base64').CRLF;
	}

/***************************************
** Builds a single part of a multipart
** message.
***************************************/

	function build_part($input){
		$message_part  = '';
		$message_part .= 'Content-Type: '.$input['c_type'];
		if($input['name'] != '')
			$message_part .= ';'.CRLF.chr(9).'name="'.$input['name'].'"'.CRLF;
		else
			$message_part .= CRLF;

		// Determine content encoding.
		if($input['c_type'] == 'text/plain'){
			$message_part.= $this->get_encoded_data($input['body'], 'quoted-printable').CRLF;

		}elseif($input['c_type'] == 'message/rfc822'){
			$message_part .= 'Content-Disposition: attachment'.CRLF;
			$message_part .= $this->get_encoded_data($input['body'], '7bit').CRLF;

		}else{
			$message_part .= 'Content-Disposition: attachment; filename="'.$input['name'].'"'.CRLF;
			$message_part .= $this->get_encoded_data($input['body'], 'base64').CRLF;
		}

		return $message_part;
	}

/***************************************
** Builds the multipart message from the
** list ($this->_parts). $params is an
** array of parameters that shape the building
** of the message. Currently supported are:
**
** $params['html_encoding'] - The type of encoding to use on html. Valid options are
**                            "7bit", "quoted-printable" or "base64" (all without quotes).
**                            7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
** $params['text_encoding'] - The type of encoding to use on plain text Valid options are
**                            "7bit", "quoted-printable" or "base64" (all without quotes).
**                            Default is 7bit
** $params['text_wrap']     - The character count at which to wrap 7bit encoded data. By
**                            default this is 998.
***************************************/

	function build_message($params = array()){

		if(count($params) > 0)
		@reset($params);
		while(list($key, $value) = each($params))
				$this->build_params[$key] = $value;

		$boundary = '=_'.md5(uniqid(time()));

		// Determine what needs building
		$do_html  = (isset($this->do_html) AND $this->do_html == 1) ? 1 : 0;
		$do_text  = (isset($this->body)) ? 1 : 0;
		$do_parts = (count($this->parts) > 0) ? 1 : 0;

		// Need to make this a multipart email?
		if($do_html OR $do_parts){
			$this->headers[] = 'Content-Type: multipart/mixed; charset="'.$this->charset.'"; '.CRLF.chr(9).'boundary="'.$boundary.'"';
			$this->multipart = "This is a MIME encoded message.".CRLF.CRLF;

			// Build html parts
			if($do_html)
				$this->build_html($boundary);

			// Build plain text part
			elseif($do_text)
				$this->multipart .= '--'.$boundary.CRLF.$this->build_part(array('body' => $this->body, 'name' => '', 'c_type' => 'text/plain'));

		// No attachments or html, plain text
		}elseif($do_text AND !$do_parts){
			$this->headers[] = 'Content-Type: text/plain;'.CRLF.chr(9).'charset="'.$this->charset.'"';
			$this->multipart = $this->body.CRLF.CRLF;
		}

		// Build all attachments
		if($do_parts)
			for($i=0; $i<count($this->parts); $i++)
				$this->multipart.= '--'.$boundary.CRLF.$this->build_part($this->parts[$i]);

		// Add closing boundary
		$this->mime = ($do_parts OR $do_html) ? $this->multipart.'--'.$boundary.'--'.CRLF : $this->multipart;
	}

/***************************************
** Sends the mail.
***************************************/

	function send($to_addr, $from_addr='', $subject = '',$to_name='', $from_name='', $headers = ''){

		$to		= ($to_name != '')   ? '"'.$to_name.'" <'.$to_addr.'>' : $to_addr;
		$from_addr= ($from_addr !='') ? $from_addr : $this->build_params['from'];
		$from	= ($from_name != '') ? '"'.$from_name.'" <'.$from_addr.'>' : $from_addr;
        $subject= ($subject !='') ? $subject : $this->build_params['subject'];
		
		
		if(is_string($headers))
			$headers = explode(CRLF, trim($headers));

		for($i=0; $i<count($headers); $i++){
			if(is_array($headers[$i]))
				for($j=0; $j<count($headers[$i]); $j++)
					if($headers[$i][$j] != '')
						$xtra_headers[] = $headers[$i][$j];

			if($headers[$i] != '')
				$xtra_headers[] = $headers[$i];
		}
		if(!isset($xtra_headers))
			$xtra_headers = array();

	//	return $this->webmail($from, $to, $subject, $this->mime, 'From: '.$from.CRLF.implode(CRLF, $this->headers).CRLF.implode(CRLF, $xtra_headers));
			return mail($to, $subject, $this->mime, 'From: '.$from.CRLF.implode(CRLF, $this->headers).CRLF.implode(CRLF, $xtra_headers));
	}



	function get_rfc822($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){

		// Make up the date header as according to RFC822
		$date = 'Date: '.date('D, d M y H:i:s');

		$to   = ($to_name   != '') ? 'To: "'.$to_name.'" <'.$to_addr.'>' : 'To: '.$to_addr;
		$from = ($from_name != '') ? 'From: "'.$from_name.'" <'.$from_addr.'>' : 'From: '.$from_addr;

		if(is_string($subject))
			$subject = 'Subject: '.$subject;

		if(is_string($headers))
			$headers = explode(CRLF, trim($headers));

		for($i=0; $i<count($headers); $i++){
			if(is_array($headers[$i]))
				for($j=0; $j<count($headers[$i]); $j++)
					if($headers[$i][$j] != '')
						$xtra_headers[] = $headers[$i][$j];

			if($headers[$i] != '')
				$xtra_headers[] = $headers[$i];
		}

		if(!isset($xtra_headers))
			$xtra_headers = array();

		return $date.CRLF.$from.CRLF.$to.CRLF.$subject.CRLF.implode(CRLF, $this->headers).CRLF.implode(CRLF, $xtra_headers).CRLF.CRLF.$this->mime;
	}

function my_get($fp, $out){

$line=fgets($fp, 1024);
	//		echo $out."<br>";
	//		echo ":".$line."<br>";
}

function webmail($from,$adr,$subj, $body, $headers){
$out="";
$fp = fsockopen("l2b.ua", 25,$errno, $errstr, 30);
if (!$fp) {
 //   echo "SERVER: $errstr ($errno)<br />\n";
} else {
//echo "OK<br>";
$this->my_get($fp, $out);
$out = "HELO ".$from." \n";
    fwrite($fp, $out);
	 $this->my_get($fp, $out);

    $out="MAIL FROM: $from \n";
	fwrite($fp,$out );
	$this->my_get($fp, $out);


	$out="RCPT TO: ".$adr." \n";
	fwrite($fp,$out );
	 $this->my_get($fp, $out);
		
	$out="DATA \n";
	fwrite($fp,$out );
	 $this->my_get($fp, $out);

	$out = $headers;
	//$out .="reply-to: $from \n ";
	//$out.="From: $from \n";
	//$out.="Sender:l2b.info \n";
	$out.="To:$adr \n";
	$out.="Subject: ".$subj." \n";
	
	$out.="\n ";
	$out.=$body;
	
	$out .=" \n.\n";
	
	fwrite($fp,$out );
	$this->my_get($fp, $out);


	$out="quit\n";
	fwrite($fp,$out );
	$out=fgets($fp);
	

    fclose($fp);
return $out;

}
}


} // End of class.
?>
