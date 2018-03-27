<?php
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	
/**************************************************************************/
/************* Script Works Generic Form Handler *** version v1.070321.0  */
/**************************************************************************/
/*************               EDIT THIS SECTION ONLY                       */
/************************************ start *******************************/

	$SendToCustomer		= "false";						//"true" to send email to customer if email exists in form email_recipient
	 													//"false" not to
	$EmailOwner		 	= "sisac@sisac.co.nz"; 			//Website owner address
	
	$SendToOwner		= "true";						//"true" to send email to owner $EmailOwner, "false" not to
	$EmailFrom			= "sisac@sisac.co.nz"; 			//Email from address
	$EmailSubject		= "Online Enquiry";		//email subject
	$ConfirmationPage	= "../thank-you.php";		//confirmation page, if .php - confirmation message is passed through
	
	/*=== Add two lines below for multiple recipient functionality ===*/
	$CCRecipients = ""; //Comma-separated list of CC recipients
	$BCCRecipients = ""; //Comma-separated list of BCC recipients
	
	
/************************************ stop ********************************/
/************* Script Works Generic Form Handler *** version v1.070321.0  */
/**************************************************************/
/*************               DO NOT EDIT BELOW THIS SECTION               */
/**************************************************************************/

/*========================================================================*/
/*========= Server-side validation for fields prefixed with "req_" =======*/
/*================== And multiple recipient functionality ================*/
$PostValues = $_POST;
$PostKeys	= array_keys ($_POST);
$KeyCount			= 0;
$DataSuccessful		= true;
foreach ($PostValues as $KeyValue) {
		$KeyName = $PostKeys[$KeyCount];
		if (preg_match("/email_recipient/",$KeyName)) {
			//Log the supplied email address for use as CC recipient if $SendToCustomer option is true.
			$EmailFrom = $EmailRecipient = $KeyValue;
		}
		if(substr($KeyName, 0, 4) == "req_" && $KeyValue == ""){
			//Required field is empty
			$DataSuccessful		= false;			
		}
		$KeyCount++;
}

if($DataSuccessful == false){
	//At least one required field is empty
	die("<br><b>Required field(s) not completed.  Please go back and complete all required fields before submitting.");
}

$emailHeaders = "";
if($SendToCustomer == "true"){
	if($CCRecipients == ""){
		$CCRecipients = $EmailFrom;
	}
	else{//Separate with comma if other CC recipients already defined.
		$CCRecipients .= ',' . $EmailFrom;
	}	
}
if($CCRecipients != ""){
	$emailHeaders	.= 'Cc: ' . $CCRecipients . "\r\n";
}
if($BCCRecipients != ""){
	$emailHeaders	.= 'Bcc: ' . $BCCRecipients . "\r\n";
}
/*======= End Server-side validation / Multiple recip functionality ======*/
/*========================================================================*/


//Script Works Ltd http://www.scriptworks.co.nz spam injection protection
// Mail function to be used on post for items that appear in mail headers
 function MailHeaderFilter($KeyValue) {
  $ProtectedKeyValues = array ("\r", "\n", "content-type:", "content-transfer", "content-disposition");
  $SpamAlert = false;
  foreach ($ProtectedKeyValues as $Protected) {
   if (eregi($Protected,$KeyValue)) {
    $SpamAlert = true;
   }
  }
  return $SpamAlert;
 }
 
 // Mail function to be used on post for body content
 function MailBodyFilter($KeyValue) {
  $ProtectedKeyValues = array ("content-type:", "content-transfer", "content-disposition");
  $SpamAlert = false;
  foreach ($ProtectedKeyValues as $Protected) {
   if (eregi($Protected,$KeyValue)) {
    $SpamAlert = true;
   }
  }
  return $SpamAlert;
 }

	$PostValues = $_POST;
	$PostKeys	= array_keys ($_POST);

	$KeyCount			= 0;
	$RecipientKey		= 0;
	$ConfirmationKey	= 0;
	
	$EmailRecipient	= "";
	//$EmailFrom		= "";
	//$EmailSubject		= "";
	
	//$ConfirmationPage	= "";
	$EmailContent 		= "";

	$DataSuccessful		= true;

	$MaxKey = 0;
	foreach ($PostValues as $KeyValue) {
		$KeyName = $PostKeys[$KeyCount];
		//echo "<br/>Field name: $KeyName" ;
		if (preg_match("/email_recipient/",$KeyName)) {
			$EmailFrom = $EmailRecipient = $KeyValue;
		}
		
		if ($KeyName=="email_from") {
			die("Email From should not be passed as a form variable. Refer instructions."); //$EmailFrom = $KeyValue;
		}
		else if ($KeyName=="email_subject") {
			die("Email Subject should not be passed as a form variable. Refer instructions."); //$EmailSubject = $KeyValue;
		}
		else if ($KeyName=="confirmation_page") {
			die("Confirmation Page should not be passed as a form variable. Refer instructions."); //$ConfirmationPage = $KeyValue;
		}
		else {
			$FieldName = preg_split ("/[\d]+/", $KeyName, -1, PREG_SPLIT_DELIM_CAPTURE);
			preg_match("/[\d]+/", $KeyName, $FieldNumber);

			$FieldPosition = intval($FieldNumber[0]);
			if (isset($FieldsArray[$FieldPosition])) {
				$DataSuccessful		= false;
				echo "Field already seems to exist for position $FieldPosition.<br />";
			}
			else {
				$MaxKey = ($MaxKey < $FieldPosition) ? $FieldPosition : $MaxKey;
				$FieldName = str_replace("_", " ", strtoupper($FieldName[0]));
				$FieldsArray[$FieldPosition] = array("Order"=>$FieldPosition, "Name"=>$FieldName, "Value"=>$KeyValue);
			}
		}

		$KeyCount++;
	}


	for ($i = 1; $i <= $MaxKey; $i++) {
		$FieldNumber	= $FieldsArray[$i];
		$EmailContent	.= $FieldNumber["Name"].": ".$FieldNumber["Value"]."\n\r";
	}

	
	
	if ($EmailRecipient == "" && $SendToCustomer=="true") {
		echo "Recipient Email address has not been set. Refer instructions.<br />";
		$DataSuccessful		= false;
	}

	if ($EmailFrom == "") {
		echo "The Email From address has not been set. Refer instructions.<br />";
		$DataSuccessful		= false;
	}

	if ($EmailSubject == "") {
		echo "The Email Subject address has not been set. Refer instructions.<br />";
		$DataSuccessful		= false;
	}

	if ($ConfirmationPage == "") {
		echo "Confirmation webpage address has not been set. Refer instructions.<br />";
		$DataSuccessful		= false;
	}


	if ($DataSuccessful) {
  
		$emailTo		= $EmailOwner;
		$emailBody		= $EmailContent;
		$emailHeaders	.= "From: $EmailFrom";
		$emailHeaders	.= "\nMIME-Version: 1.0\r\n";
		$emailHeaders	.= "Content-type: text/plain; charset=iso-8859-1\r\n";
	
		//Script Works Ltd http://www.scriptworks.co.nz spam injection protection
  		if (MailHeaderFilter($emailTo)) { die("Spam injection identified."); }
		if (MailHeaderFilter($EmailFrom)) { die("Spam injection identified."); }
		if (MailBodyFilter($EmailContent)) { die("Spam injection identified."); }
		
		mail($emailTo, $EmailSubject, $emailBody, $emailHeaders);	
		redirect($ConfirmationPage);
	}


// Redirect to a potentially relative path, append session id to URL if needed
function redirect($url)
{
	$uArray = parse_url($url);
	if(!isset($uArray["scheme"]))
	{
		// it's a relative path...
		global $_SERVER;
		if (defined("SID") && SID != "")
		{
			if (strstr($url, "?"))
				$url .= "&".SID;
			else
				$url .= "?".SID;
		}
		if (substr($url, 0, 1) != "/")
		{
			$path_str = dirname($_SERVER['PHP_SELF']) . "/" . $url;
			$strArr = preg_split("/(\/)/", $path_str, -1, PREG_SPLIT_NO_EMPTY);
			$pwdArr="";
			for($i=0,$j=0;$i<count($strArr);$i++)
			{
				if($strArr[$i]!="..")
				{
					if($strArr[$i]!=".")
					{
						$pwdArr[$j]=$strArr[$i];
						$j++;
					}
				}
				else
				{
					array_pop($pwdArr);
					$j--;
				}
			}
			$url="/".implode("/",$pwdArr);
		}
		$url = ($_SERVER["HTTPS"] == "on" ? "https" : "http") . "://" 
			.$_SERVER['HTTP_HOST']
			.$url;
	}
	header("Location: ". $url);
}
?>
