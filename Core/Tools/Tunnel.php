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
		
		private function PassThroughTunnel($verb, $url)
		{
			$request = new HttpRequest($verb, $url);
			$request->SetAuthorization($this->username.": ".md5($this->password));
			
			return $request;
		}
		
		public function GetClassList()
		{
			$url = RequestMapping::GetURLForRequest("RetrieveClassList");
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			$classes = array();
			
			foreach ($xresponse as $xclass) 
			{
				$class = Digikabu_Class::FromXMLNode($xclass);
				$classes[] = $class;
			}
			
			return json_encode($classes);
		}
		
		public function GetTeacherList($class)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveTeacherListForClass", array("Class" => $class));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$teachers = array();
			
			foreach ($xresponse->children()as $xteacher) 
			{
				$teacher = Digikabu_Teacher::FromXMLNode($xteacher);
				$teachers[] = $teacher;
			}
			
			return json_encode($teachers);
		}
		
		public function GetTeacherListForTeachers()
		{
			$url = RequestMapping::GetURLForRequest("RetrieveTeacherListForTeachers");
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$teachers = array();
			
			foreach ($xresponse->children()as $xteacher) 
			{
				$teacher = Digikabu_Teacher::FromXMLNode($xteacher);
				$teachers[] = $teacher;
			}
			
			return json_encode($teachers);
		}
		
		public function GetSubjectListForClass($class)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveSubjectListForClass",array("Class"=>$class));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$subjects = array();
			
			foreach ($xresponse->children()as $xsubject) 
			{
				$subject = Digikabu_Class::FromXMLNode($xsubject);
				$subjects[] = $subject;
			}
			
			return json_encode($subjects);
		}
		
		public function GetScheduleForClass($class, $from)
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForClass",array("Class"=>$class, "From" => $from));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$weeks = array();
			foreach($xresponse->children() as $xweek)
			{
				$week = Digikabu_Week::FromXMLNode($xweek);
				
				$weeks[] = $week;
			}
			
			print json_encode($weeks);
		}
		
		public function GetScheduleForTeacher($teacher, $from)
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForTeacher",array("Teacher"=>$teacher, "From" => $from));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$xresponse = simplexml_load_string($request->SendRequest());
			
			$weeks = array();
			foreach($xresponse->children() as $xweek)
			{
				$week = Digikabu_Week::FromXMLNode($xweek);
				
				$weeks[] = $week;
			}
			
			print json_encode($weeks);
		}
	}
?>