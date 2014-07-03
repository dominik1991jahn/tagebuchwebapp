<?php
	class App
	{
		public static function Main()
		{
			echo "<h1>Hello App!</h1>";
			
			var_dump(Configuration::GetConfigurationParameter("ServerURL"));
			var_dump(RequestMapping::GetURLForRequest("Schedule.RetrieveForClass"));
			
			$queryString = $_SERVER["QUERY_STRING"];
			
			$requestHandler = RequestHandler::GetHandlerForRequestURI("GET", $queryString);
			$tunnel = new Tunnel("dominik","o!saycanyouseemypasswordintheclear");
			
			$tunnel->GetSchedule();
		}
	}
?>