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
		
		public function GetClassList($year)
		{
			$fromCache = $this->FromCache("GetClassList/".$year);
			$cacheparameters = array("year" => $year);
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
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
				
				$response = json_encode($classes, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetTeacherList()
		{
			$fromCache = $this->FromCache("GetTeacherList");
			$cacheparameters = array();
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
			{
				$url = RequestMapping::GetURLForRequest("RetrieveTeacherListForClass");
				$request = $this->PassThroughTunnel("GET",$url);
				
				$request->SendRequest();
				
				if($request->HTTPStatusCode <> 200)
				{
					print json_encode($this->HTTPError($request->HTTPStatusCode));
					return;
				}
				
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				$teachers = array();
				$names = array();
				foreach ($xresponse->children()as $xteacher) 
				{
					$teacher = Digikabu_Teacher::FromXMLNode($xteacher);
					
					if(!in_array($teacher->Abbreviation, $names))
					{
						$teachers[] = $teacher;
						$names[] = $teacher->Abbreviation;
					}
				}
				
				$response = json_encode($teachers, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetTeacherListForTeachers()
		{
			$fromCache = $this->FromCache("GetTeacherListForTeachers");
			
			$cacheparameters = array();
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
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
				
				$response = json_encode($teachers, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetSubjectListForClass($class)
		{
			$fromCache = $this->FromCache("GetSubjectListForClass/".$class);
			
			$cacheparameters = array("class" => $class);
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
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
				
				$response = json_encode($subjects, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetScheduleForClass($class, $date)
		{
			$fromCache = $this->FromCache("GetScheduleForClass/".$class."/".$date);
			
			$cacheparameters = array("class"=>$class,"date"=>$date);
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
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
				
				$response = json_encode($days, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetScheduleForTeacher($teacher, $date)
		{
			$fromCache = $this->FromCache("GetScheduleForTeacher/".$teacher."/".$date);
			$cacheparameters = array("teacher" => $teacher, "date" => $date);
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
			{
				$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForTeacher",array("Teacher"=>$teacher, "Date" => $date));
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
				
				$response = json_encode($days, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
					if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
					{
						return json_encode($this->HTTPError(304));
					}
					
					$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
					Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		public function GetEvents($class, $year, $type)
		{
			$fromCache = $this->FromCache("GetEvents/".$class."/".$year."/".$type);
			$cacheparameters = array("class" => $class, "year" => $year, "type" => $type);
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
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
					
					if($type == "past" && ($event->From > $today))
					{
						continue;
					}
					else if($type == "future" && ($event->From < $today))
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
				
				$response = json_encode($events, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}

			return $response;
		}

		public function CheckPermissions()
		{
			$fromCache = $this->FromCache("CheckPermissions");
			$cacheparameters = array();
			
			if(!is_null($fromCache))
			{
				$response = $fromCache;
			}
			else
			{
				$url = RequestMapping::GetURLForRequest("CheckPermissions");
				$request = $this->PassThroughTunnel("GET",$url);
				
				$request->SendRequest();
				
				if($request->HTTPStatusCode <> 200)
				{
					print json_encode($this->HTTPError($request->HTTPStatusCode));
					return;
				}
				
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				$permission = false;
				
				foreach($xresponse->children() as $xNode)
				{
					switch($xNode->getName())
					{
						case "IsTeacher":
							$permission = (string) $xNode;
							$permission = ($permission == "true" ? true : false);
							break;
							
					}
				}
				
				$response = json_encode($permission, JSON_PRETTY_PRINT);
			}
			
			if(substr(Cache::GetInstance()->GetCacheControl(),0,8) <> "no-cache")
			{
				$controlsum = md5($response);
				
				if(Cache::GetInstance()->GetFromCache(substr(__METHOD__,8), $cacheparameters, $controlsum))
				{
					return json_encode($this->HTTPError(304));
				}
				
				$cacheobject = new CacheObject(substr(__METHOD__,8), $cacheparameters, $controlsum);	
				Cache::GetInstance()->AddToCache($cacheobject, $response);
			}
			
			return $response;
		}
		
		private function HTTPError($code)
		{
			$message = null;
			
			switch($code)
			{
				case "304": $message = "Not modified"; break;
				case "400": $message = "Bad request"; break;
				case "404": $message = "Resource not found"; break;
				case "401": $message = "Unauthorized"; break;
				case "500": $message = "API-Server: Internal Server Error"; break;
			}
			
			$data = array("code" => $code, "message" => $message);
			
			return $data;
		}
		
		/*private function cache($data, $path)
		{
			return;
			if(!file_exists($path))
			{
				$pathseg = explode("/",$path);
				
				if(count($pathseg)>1)
				{
					$dir = "Cache/";
					for($i=0;$i<count($pathseg)-1;$i++)
					{
						$dir .= $pathseg[$i]."/";
						
						if(!is_dir($dir)) mkdir($dir);
					}
				}
				
				file_put_contents("Cache/".$path.".json", $data);
			}
		}*/
		
		private function FromCache($path)
		{
			//return;
			if(file_exists("Cache/".$path.".json"))
			{
				return file_get_contents("Cache/".$path.".json");
			}
		}
	}
?>