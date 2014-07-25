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
			$url = RequestMapping::GetURLForRequest("RetrieveClassList", array("Year" => $year));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
				
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array("year" => $year);
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
				
					$classes = array();
					
					foreach ($xresponse as $xclass) 
					{
						$class = Digikabu_Class::FromXMLNode($xclass);
						$classes[] = $class;
					}
					
					$response = json_encode(array("code" => 200, "data" => $classes), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	
			
			return $response;
		}
		
		public function GetTeacherList()
		{
			$url = RequestMapping::GetURLForRequest("RetrieveTeacherListForClass");
			$request = $this->PassThroughTunnel("GET",$url);
				
			$request->SendRequest();
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array();
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
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
				
					$response = json_encode(array("code" => 200, "data" => $teachers), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	
			
			return $response;
		}

		public function GetSubjectListForClass($class)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveSubjectListForClass",array("Class"=>$class));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array("class" => $class);
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
					$subjects = array();
					
					foreach ($xresponse->children()as $xsubject) 
					{
						$subject = Digikabu_Class::FromXMLNode($xsubject);
						$subjects[] = $subject;
					}
					
					$response = json_encode(array("code" => 200, "data" => $subjects), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	
		
			return $response;
		}
		
		public function GetScheduleForClass($class, $date)
		{
			$requestCode = 200;
			
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForClass",array("Class"=>$class, "Date" => $date));
			$request = $this->PassThroughTunnel("GET",$url);
			
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array("class" => $class, "date" => $date);
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
					$days = array();
					foreach($xresponse->children() as $xday)
					{
						$day = Digikabu_Day::FromXMLNode($xday);
						
						$days[] = $day;
					}
					
					$response = json_encode(array("code" => 200, "data" => $days), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	
			
			return $response;
		}
		
		public function GetScheduleForTeacher($teacher, $date)
		{
			$url = RequestMapping::GetURLForRequest("Schedule.RetrieveForTeacher",array("Teacher"=>$teacher, "Date" => $date));
			$request = $this->PassThroughTunnel("GET",$url);
				
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array("teacher" => $teacher, "date" => $date);
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
					$days = array();
					foreach($xresponse->children() as $xday)
					{
						$day = Digikabu_Day::FromXMLNode($xday);
						
						$days[] = $day;
					}
					
					$response = json_encode(array("code" => 200, "data" => $days), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	

			return $response;
		}
		
		public function GetEvents($class, $year, $type)
		{
			$url = RequestMapping::GetURLForRequest("RetrieveClassEvents",array("Class"=>$class, "Year" => $year));
			$request = $this->PassThroughTunnel("GET",$url);
				
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 401)
			{
				// If all went well or we get any error other than 401 (unauthorized), we check back with the cache
				$cacheparameters = array("class" => $class, "year" => $year, "type" => $type);
				$cachemethod = __FUNCTION__;
				$cachecontrolsum = md5($request->ResponseBody);
				
				$notModified = false;
				$requestNew = (substr(Cache::GetInstance()->GetCacheControl(),0,8) == "no-cache" ? true : false);
				
				$notModified = Cache::GetInstance()->CheckStatus($cachemethod, $cacheparameters, $cachecontrolsum);
				
				// We need to do this here so we can also check if the response is a valid XML-document
				// If it's not, we fall back to the cached version
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				if(!$requestNew && (!$xresponse || $request->HTTPStatusCode <> 200 || $notModified))
				{
					$response = json_encode($this->HTTPError(304));
				}
				else
				{
					if(!$xresponse)
					{
						$fromCache = Cache::GetInstance()->GetFromCache($cachemethod, $cacheparameters);
						$xresponse = simplexml_load_string($fromCache);
					}
					
					/*
					 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
					 */
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
					
					$response = json_encode(array("code" => 200, "data" => $events), JSON_PRETTY_PRINT);
					
					$cacheobject = new CacheObject($cachemethod, $cacheparameters, $cachecontrolsum);
					Cache::GetInstance()->AddToCache($cacheobject, $request->ResponseBody);
				}
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
			}	

			return $response;
		}

		public function CheckPermissions()
		{
			$url = RequestMapping::GetURLForRequest("CheckPermissions");
			$request = $this->PassThroughTunnel("GET",$url);
				
			$request->SendRequest();
			
			if($request->HTTPStatusCode <> 401)
			{
				$xresponse = simplexml_load_string($request->ResponseBody);
				
				/*
				 * Either we requested a non-cached version, or the file has been updated since the last time they were requested
				 */
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
				
				$response = json_encode(array("code" => 200, "data" => $permission), JSON_PRETTY_PRINT);
			}
			else
			{
				// If we get a 401, we won't access the cache!
				$response = json_encode($this->HTTPError(401));
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
	}
?>