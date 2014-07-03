<?php
	class Tunnel
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $username;
		private /*(string)*/ $password;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Tunnel($username, $password)
		{
			$this->username = $username;
			$this->password = $password;
		}
		
		  //
		 // METHODS
		//
		
		public function GetSchedule()
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForClass",array("Class" => "IT11a", "From" => "2014-06-23", "To" => "2014-06-29"));
			$request = new HttpRequest("GET", $url);
			
			$request->SetAuthorization($this->username.": ".md5($this->password));
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			
			$weeks = array();
			foreach($xresponse->children() as $xweek)
			{
				$week = Digikabu_Week::FromXMLNode($xweek);
				
				$weeks[] = $week;
			}
			print_r($weeks);
			//print json_encode($weeks);
		}
		
		public function GetClassList()
		{
			$url = RequestMapping::GetURLForRequest("RetrieveClassList");
			$request = new HttpRequest("GET", $url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$classes = array();
			
			
			
			
		}
		
		public function GetTeacherList()
		{
			
		}
		
		public function GetSubjectList()
		{
			
		}
	}
?>