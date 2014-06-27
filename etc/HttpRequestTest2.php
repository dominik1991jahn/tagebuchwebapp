<?php
	header("Content-Type: text/plain");
	
	$headers = getallheaders();
	$method = $_SERVER["REQUEST_METHOD"];
	$getdata = $_GET;
	$postdata = $_POST;
	var_dump($_POST);
	
	echo "HTTP Verb:\t".$method."\n\n";
	
	echo "===\n\nHTTP-Headers\n\n";
	
	foreach($headers as $name => $value)
	{
		echo $name.":\t".$value."\n";
	}
	
	echo "\n===\n\nGET-Data\n\n";
	
	var_dump($getdata);
	
	echo "\n===\n\nPOST-Data\n\n";
	
	var_dump($postdata);
	
	echo "\n===\n\nServer-Data\n\n";
	
	var_dump($_SERVER);
	
	$post_body = file_get_contents('php://input');
	var_dump($post_body);
?>