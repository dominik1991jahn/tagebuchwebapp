<?php
	$url = (isset($_POST["url"]) ? $_POST["url"] : null);
	$verb = (isset($_POST["verb"]) ? $_POST["verb"] : "GET");
	$data = (isset($_POST["data"]) ? $_POST["data"] : null);
	$headers = (isset($_POST["headers"]) ? $_POST["headers"] : null);
	
	include("../Core/Tools/HttpRequest.php");
?><!DOCTYPE html>
<html>
	<head>
		<title>HTTP-Request Test</title>
	</head>
	<body>
		<form method="post" action="HttpRequestTest.php">
			<p><strong>URL:</strong> HttpRequestTest2.php?<input type="text" name="url" value="<?php echo $url; ?>" /></p>
			<p><strong>Verb:</strong> <select name="verb">
										<option <?php echo ($verb == "GET" ? "selected=\"selected\"" : null); ?>>GET</option>
										<option <?php echo ($verb == "POST" ? "selected=\"selected\"" : null); ?>>POST</option>
										<option <?php echo ($verb == "PUT" ? "selected=\"selected\"" : null); ?>>PUT</option>
										<option <?php echo ($verb == "DELETE" ? "selected=\"selected\"" : null); ?>>DELETE</option>
									</select></p>
			<p><strong>Data:</strong> (name=value, separate with \n):<br/><textarea name="data" style="width:300px;height:150px"><?php echo $data; ?></textarea></p>
			<p><strong>Headers:</strong> (name=value, separate with \n):<br/><textarea name="headers" style="width:300px;height:150px"><?php echo $headers; ?></textarea></p>
			<p><input type="submit" name="submit" value="Submit Request" /></p>
		</form>
		
		<?php
				
			if(isset($_POST["submit"]))
			{
				$url = "http://localhost/tagebuchwebapp/etc/HttpRequestTest2.php?".$url;
				
				$data_raw = explode("\n",$data);
				$data = new RequestData;
				
				if(count($data_raw))
				{
					foreach($data_raw as $line)
					{
						$line = explode("=",$line);
						
						$name = $line[0];
						$value = trim($line[1]);
						
						$data->AddParameter($name, $value);
					}
				}
				
				$headers_raw = explode("\n",$headers);
				$headers = new HeaderData;
				
				if(count($headers_raw))
				{
					foreach($headers_raw as $line)
					{
						$line = explode("=",$line);
						
						$name = $line[0];
						$value = rtrim($line[1]);
						
						$headers->AddHeader($name, $value);
					}
				}
				
				$request = new HttpRequest($verb, $url, $data, $headers);
				
				$result = $request->SendRequest();
				
				echo "<pre style=\"border: 2px solid #d00;background-color:#fd0\"><code>".$result."</code></pre>";
			}
		?>
	</body>
</html>