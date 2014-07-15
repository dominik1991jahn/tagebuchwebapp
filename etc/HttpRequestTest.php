<?php
	$url = (isset($_POST["url"]) ? $_POST["url"] : null);
	$verb = (isset($_POST["verb"]) ? $_POST["verb"] : "GET");
	$data = (isset($_POST["data"]) ? $_POST["data"] : null);
	$headers = (isset($_POST["headers"]) ? $_POST["headers"] : null);
	$username = (isset($_POST["username"]) ? $_POST["username"] : null);
	$password = (isset($_POST["password"]) ? $_POST["password"] : null);
	
	include("../Core/Tools/HttpRequest.php");
?><!DOCTYPE html>
<html>
	<head>
		<title>HTTP-Request Test</title>
	</head>
	<body>
		<form method="post" action="HttpRequestTest.php">
			<p><strong>URL:</strong> <input type="text" name="url" value="<?php echo $url; ?>" style="width:600px"/></p>
			<p><strong>Verb:</strong> <select name="verb">
										<option <?php echo ($verb == "GET" ? "selected=\"selected\"" : null); ?>>GET</option>
										<option <?php echo ($verb == "POST" ? "selected=\"selected\"" : null); ?>>POST</option>
										<option <?php echo ($verb == "PUT" ? "selected=\"selected\"" : null); ?>>PUT</option>
										<option <?php echo ($verb == "DELETE" ? "selected=\"selected\"" : null); ?>>DELETE</option>
									</select></p>
			<p><strong>Data:</strong> (name=value, separate with \n):<br/><textarea name="data" style="width:300px;height:150px"><?php echo $data; ?></textarea></p>
			<p><strong>Headers:</strong> (name: value, separate with \n):<br/><textarea name="headers" style="width:300px;height:150px"><?php echo $headers; ?></textarea></p>
			<p><strong>Username:</strong> <input type="text" name="username" value="<?php echo $username; ?>" /></p>
			<p><strong>Password:</strong> <input type="password" name="password" value="<?php echo $password; ?>" /></p>
			<p><input type="submit" name="submit" value="Submit Request" /></p>
		</form>
		
		<?php
				
			if(isset($_POST["submit"]))
			{
				//$url = "http://localhost/tagebuchwebapp/etc/HttpRequestTest2.php?".$url;
				
				$data_raw = explode("\n",$data);
				$data = new RequestData;
				
				if(count($data_raw))
				{
					foreach($data_raw as $line)
					{
						$line = explode("=",$line);
						
						if(count($line)<2) continue;
						
						$name = $line[0];
						$value = trim($line[1]);
						
						$data->AddParameter($name, $value);
					}
				}
				
				$headers = "Authorization:Basic ".base64_encode($username.":".$password)."\n".$headers;
				$headers = trim($headers);
				$headers_raw = explode("\n",$headers);
				$headers = new HeaderData;
				
				if(count($headers_raw))
				{
					foreach($headers_raw as $line)
					{
						$line = explode(":",$line);
						
						if(count($line)<2) continue;
						$name = $line[0];
						$value = rtrim($line[1]);
						
						$headers->AddHeader($name, $value);
					}
				}
				
				$request = new HttpRequest($verb, $url, $data, $headers);
				
				$request->SendRequest();
				
				echo "<pre style=\"border: 2px solid #d00;background-color:#fd0\"><code>".htmlentities(wordwrap($request->ResponseBody,150,"\n"))."</code></pre>";
				
				echo "<h4>Response Headers</h4>
				
				<ul>";
				
				foreach($request->ResponseHeaders as $header)
				{
					echo '<li>'.$header.'</li>';
				}
				
				echo '</ul>';
				
				echo "<h4>Request Headers</h4>
				
				<ul>";
				
				foreach($request->Header->Headers as $header => $value)
				{
					echo '<li><strong>'.$header.':</strong> '.$value.'</li>';
				}
				
				echo '</ul>';
			}
		?>
	</body>
</html>
