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
			
			$result = $request->SendRequest();
			
			echo $result;
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