<?php
	class CacheObject
	{
		
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $identifier;
		private /*(string)*/ $function;
		private /*(array<String,String>)*/ $parameters;
		private /*(string)*/ $controlsum;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct(/*(string)*/ $function, /*(array<String,String)*/ $parameters, /*(string)*/ $controlsum)
		{
			$this->function = $function;
			$this->parameters = $parameters;
			$this->controlsum = $controlsum;
			
			$identifier = $function."/".implode("/",$parameters);
			
			$this->identifier = $identifier;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Function": return $this->GetFunction();
				case "Identifier": return $this->GetIdentifier();
				case "ControlSum": return $this->GetControlSum();
				case "Parameters": return $this->GetParameters();
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetFunction()
		{
			return $this->function;
		}
		
		private function GetIdentifier()
		{
			return $this->identifier;
		}
		
		private function GetControlSum()
		{
			return $this->controlsum;
		}
		
		private function GetParameters()
		{
			return $this->parameters;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function ImportFromXMLNode(SimpleXMLElement $node)
		{
			$method = $node->getName();
			$parameters = array();
			$controlsum = (string) $node;
			
			$xmlAttributes = $node->attributes();
			
			foreach($xmlAttributes as $xmlAttribute)
			{
				$parameter = $xmlAttribute->getName();
				$value = (string) $xmlAttribute;
				
				$parameters[$parameter] = $value;
			}
			
			return new CacheObject($method, $parameters, $controlsum);
		}
	}
?>