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
			$request = new HttpRequest("GET", "http://bsz0/~jahnd/digikabuwebapp/ResponseExamples/Schedule/Students/Response%20Woechentlich%20%28Schueler%29.xml");
			
			$request->SetAuthorization($this->username.": ".md5($this->password));
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			foreach($xresponse->children() as $xweek)
			{
				$week = Digikabu_Week::FromXMLNode($xweek);
				echo "<pre>";
				var_dump($week);
				echo "</pre><hr/>";
			}
		}
		
		public function GetClassList()
		{
			
		}
		
		public function GetTeacherList()
		{
			
		}
		
		public function GetSubjectList()
		{
			
		}
	}
?>