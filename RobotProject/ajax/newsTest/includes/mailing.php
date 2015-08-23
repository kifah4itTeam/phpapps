<?
if (!defined('GOOD')) die();
	function addNewMember($thisEmail , $name){
		$thisId = rand(106,5245);
		$thisUid = getUid($thisEmail , $thisId);
		$sqlQuery = "INSERT INTO mailing_list (email,uid,full_name ) VALUES ('$thisEmail','$thisUid','$name')";
		$result = MYSQL_QUERY($sqlQuery);
		if(!$result) return false;
		return true;
	}
	
	function unsubscribe($thisEmail , $thisUid){
		$sql = "UPDATE mailing_list SET sub='0' WHERE (email = '$thisEmail') AND (uid = '$thisUid')";
		$result = MYSQL_QUERY($sql);
		if($result) return true;
		return false;
	}
	
	
	function getUid($thisEmail , $thisId){
		$input = $thisEmail.getdate().$thisId;
		$thisUid = md5($input);
		return $thisUid;
	}
	
	function sendNewsletter($from,$newsTitle,$newsBody){
		
		$sql = "SELECT * FROM mailing_list WHERE active='1'" ;
		$result = MYSQL_QUERY($sql);
		$numberOfRows = MYSQL_NUMROWS($result);
		
		$i=0;
		while ($i < $numberOfRows){
			$thisEmail = MYSQL_RESULT($result,$i,"email");
			$thisUid = MYSQL_RESULT($result,$i,"uid");
			$thisName = MYSQL_RESULT($result,$i,"full_name");
			/* recipients */
			$to  = $thisEmail ; // note the comma
			/* subject */
			$subject = $newsTitle;

			/* message */
			$message  = "Dear $thisName <br>";
			$message .= $newsBody.
			'<br><br><a href="'._PREF.'modules/mailing_ilst/deleteMailing_list.php?thisEmailField='.$thisEmail.'&thisUidField='.$thisUid.'">
			To unsubscribe please click here</a>';
			
			/* To send HTML mail, you can set the Content-type header. */
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

			/* additional headers */
			$headers .= "To:".$thisEmail."\r\n";
			$headers .= "From: ".$from;

			/* and now mail it */
			$s = mail($to, $subject, $message, $headers); 
			$i++;
		}
		if($s){
			echo '<script>correctMessage("Message Sent successfuly");</script>';
		}else{
			echo '<script>errorMessage("Sorry message has not been sent, an error has occurred");</script>';
		}
		

	}

?>