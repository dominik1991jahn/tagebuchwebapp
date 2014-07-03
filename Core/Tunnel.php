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
			
			foreach ($xresponse->children()as $xclass) 
			{
				$class = Digikabu_Class::FromXMLNode($xclass);
				$classes[] = $class;
			}
			print_r($classes);
			
		}
		
		public function GetTeacherList()
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForTeacher",array("Teacher"=>"BAR","From" => "2014-06-23", "To"=>"2014-06-29"));
			$request = new HttpRequest("GET", $url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$teachers = array();
			
			foreach ($xresponse->children()as $xteacher) 
			{
				$teacher = Digikabu_Class::FromXMLNode($xteacher);
				$teachers[] = $teacher;
			}
			print_r($teachers);
		}
		
		public function GetSubjectList()
		{
			$url = RequestMapping::GetURLForRequest("RetrieveSubjectListForClass",array("Class"=>"BFI11a"));
			$request = new HttpRequest("GET", $url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$subjects = array();
			
			foreach ($xresponse->children()as $xsubject) 
			{
				$subject = Digikabu_Class::FromXMLNode($xsubject);
				$subjects[] = $subject;
			}
			print_r($subjects);
		}
	}
?>