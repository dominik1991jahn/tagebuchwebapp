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
			$request->SetAuthorization("Basic ".base64_encode($this->username.":".$this->password));
			$request->SetAccept("application/xml");
			
			return $request;
		}
		
		public function CheckLoginCredentials()
		{
			return json_encode(array("DataCorrect" => (bool) mt_rand(0,1)));
		}
		
		public function GetClassList($year)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveClassList", array("Year" => $year));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
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
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
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
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
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
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
			$subjects = array();
			
			foreach ($xresponse->children()as $xsubject) 
			{
				$subject = Digikabu_Class::FromXMLNode($xsubject);
				$subjects[] = $subject;
			}
			
			return json_encode($subjects);
		}
		
		public function GetScheduleForClass($class, $date)
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForClass",array("Class"=>$class, "Date" => $date));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
			$days = array();
			foreach($xresponse->children() as $xday)
			{
				$day = Digikabu_Day::FromXMLNode($xday);
				
				$days[] = $day;
			}
			
			print json_encode($days, JSON_PRETTY_PRINT);
		}
		
		public function GetScheduleForTeacher($teacher, $from)
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForTeacher",array("Teacher"=>$teacher, "From" => $from));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
			$weeks = array();
			foreach($xresponse->children() as $xweek)
			{
				$week = Digikabu_Week::FromXMLNode($xweek);
				
				$weeks[] = $week;
			}
			
			print json_encode($weeks);
		}
		
		public function GetEvents($class, $year, $type)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveClassEvents",array("Class"=>$class, "Year" => $year));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 200)
			{
				print json_encode($this->HTTPError($request->HTTPStatusCode));
				return;
			}
			
			$xresponse = simplexml_load_string($request->ResponseBody);
			
			$today = strtotime(date("Y-m-d")." 00:00:00");
			
			$events = array();
			$previousEvent = null;
			foreach($xresponse->children() as $xevent)
			{
				$event = Digikabu_Event::FromXMLNode($xevent);
				
				if($type == "past" && ($event->From <= $today && $event->To <= $today))
				{
					continue;
				}
				else if($type == "future" && ($event->From >= $today))
				{
					continue;
				}
				
				if(!is_null($previousEvent) && $event->Description == $previousEvent->Description)
				{
					$previousEvent->To = $event->From;
				}
				else
				{
					$events[] = $event;
					$previousEvent = $event;
				}
			}
			
			print json_encode($events);
		}
		
		private function HTTPError($code)
		{
			$message = null;
			
			switch($code)
			{
				case "400": $message = "Bad request"; break;
				case "404": $message = "Resource not found"; break;
				case "401": $message = "Unauthorized"; break;
				case "500": $message = "API-Server: Internal Server Error"; break;
			}
			
			$data = array("code" => $code, "message" => $message);
			
			return $data;
		}
	}
?>