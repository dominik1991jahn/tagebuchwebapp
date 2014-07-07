<?php
	class App
	{
		public static function Main()
		{
			header("Content-Type: application/json");
			
			// Get the requested function
			$queryString = $_SERVER["QUERY_STRING"];
			$requestHandler = RequestHandler::GetHandlerForRequestURI("GET", $queryString);
			
			// Establish a connection to the API-Server
			$tunnel = new Tunnel("dominik","o!saycanyouseemypasswordintheclear");
			
			// Merge Request and Connection
			print $requestHandler->PerformRequest($tunnel);
		}
	}
?>