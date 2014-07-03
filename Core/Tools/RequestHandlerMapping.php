<?php
	class RequestHandler
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $method;
		private /*(string)*/ $url;
		private /*(string)*/ $handler;
		private /*(array<int, string>)*/ $fields;
		private /*(array<int, string>)*/ $parameters;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function RequestHandler($method, $url, $handler, $fields = array())
		{
			$this->method = $method;
			$this->url = $url;
			$this->handler = $handler;
			$this->fields = $fields;
			$this->parameters = array();
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Method": return $this->GetMethod();
				case "URL": return $this->GetURL();
				case "Handler": return $this->GetHandler();
				case "Fields": return $this->GetFields();
				case "Parameters": return $this->GetParameters();
				default: throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Parameters": return $this->SetParameters($value);
				default: throw new InvalidArgumentException("Field '".$field."' not defined or read-only");
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# METHOD
		
		private function GetMethod()
		{
			return $this->method;
		}
		
		private function SetMethod($value)
		{
			$this->method = $value;
		}
		
		# URL
		
		private function GetURL()
		{
			return $this->url;
		}
		
		private function SetURL($value)
		{
			$this->url = $value;
		}
		
		# HANDLER
		
		private function GetHandler()
		{
			return $this->handler;
		}
		
		private function SetHandler($value)
		{
			$this->handler = $handler;
		}
		
		# FIELDS
		
		private function GetFields()
		{
			return $this->fields;
		}
		
		# PARAMETERS
		
		private function GetParameters()
		{
			return $this->parameters;
		}
		
		private function SetParameters($value)
		{
			$this->parameters = $value;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(array<RequestHandler>)*/ $handlers;
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetHandlerForRequestURI($url)
		{
			self::InitializeRequestHandlerMapping();
			
			$matchingHandler = null;
			
			foreach(self::$handlers as $handler)
			{
				$parameters = array();
				if(preg_match("#^".$handler->URL."\$#", $url, $parameters))
				{
					$matchingHandler = $handler;
					
					$parameters = ArrayTools::GetSubset($parameters,1);
					$parameters = ArrayTools::MergeKeysAndValues($handler->Fields, $parameters);
					
					$matchingHandler->Parameters = $parameters;
					break;
				}
			}
			
			var_dump($matchingHandler);
			
			return $matchingHandler;
		}
		
		private static function InitializeRequestHandlerMapping()
		{
			if(!is_null(self::$handlers))
				return null;
			
			self::$handlers = array();
			
			$xmlHandlers = simplexml_load_file(Configuration::GetConfigurationParameter("FileMapping.RequestHandlerMapping"));
			
			foreach($xmlHandlers->children() as $xmlHandler)
			{
				$attributes = $xmlHandler->attributes();
				
				$method = (string) $attributes["method"];
				$url = (string) $attributes["url"];
				$handler = (string) $attributes["handler"];
				
				$xmlFields = $xmlHandler->children();
				
				$fields = array();
				
				if(count($xmlFields))
				{
					$fieldIndex = 0;
					foreach($xmlFields as $xmlField)
					{
						$name = (string) $xmlField;
						
						$fields[$fieldIndex] = $name;
						
						$fieldIndex++;
					}
				}
				
				$requestHandler = new RequestHandler($method, $url, $handler, $fields);
				
				self::$handlers[] = $requestHandler;
			}
		}
	}
?>