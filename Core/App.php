<?php
	class App
	{
		public static function Main()
		{
			header("Content-Type: application/json");
			
			date_default_timezone_set("Europe/Berlin");
			
			// Get the requested function
			# HTTP-Verb (GET/POST/PUT/DELETE)
			$httpMethod = $_SERVER["REQUEST_METHOD"];
			
			# Query-String (contains the URI)
			$queryString = $_SERVER["QUERY_STRING"];
			
			# Request the corresponding Request Handler
			$requestHandler = RequestHandler::GetHandlerForRequestURI($httpMethod, $queryString);
			
			/*
			 * Login Information
			 */
			
			$username = (isset($_COOKIE["loginname"]) ? $_COOKIE["loginname"] : null);
			$password = (isset($_COOKIE["password"]) ? $_COOKIE["password"] : null);
			
			// Establish a connection to the API-Server with the login credentials
			$tunnel = new Tunnel($username, $password);
			
			if(!$username || !$password)
			{
				$response = json_encode($tunnel->HTTPError(401));
			}
			else
			{
				// Merge Request and Connection and perform the request
				$response = $requestHandler->PerformRequest($tunnel);
			}
			
			// Print to response
			print $response;
			
			/*
			 * That's all there is to do...
			 */
		}
	}
?>