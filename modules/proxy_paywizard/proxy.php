<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: soapaction,content-type,content-length,connection");
header("Access-Control-Allow-Credentials: true");
header("Content-Type:text/xml; charset=\"utf-8\"");
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );

    $action = $_POST['action'];
    $target = $_POST['url'];
    $envelope = $_POST['envelope'];
    $soapUser = $_POST['soapUser'];
    $soapPassword = $_POST['soapPassword'];

    $headers = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Accept: text/xml",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        "SOAPAction: ".$action,
        "Content-length: ".strlen($envelope),
    );
	

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $target);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);

	if($_SERVER['REQUEST_METHOD']=="POST") {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
	} else if($_SERVER['REQUEST_METHOD']=="GET"){
		curl_setopt($soap_do, CURLOPT_POSTFIELDS, null);
		curl_setopt($soap_do, CURLOPT_POST, false);
		curl_setopt($soap_do, CURLOPT_HTTPGET, true);
	}
    $response = curl_exec($ch);		
	if($response === false)
		echo 'Curl error: ' . curl_error($ch);
    curl_close($ch);
    echo($response);
 ?>