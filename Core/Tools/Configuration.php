<?php
	class Configuration
	{
		  //
		 // VARIABLES
		//
		
		private static /*(array<String, Mixed)*/ $parameters;
		
		  //
		 // FUNCTIONS
		//
		
		private static function InitializeConfiguration()
		{
			if(!(self::$parameters == null))
				return null;
			
			self::$parameters = array(); 
			
			$xmlConfig = simplexml_load_file("Core/Configuration/Configuration.xml");
			
			foreach($xmlConfig->children() as $childnode)
			{
				self::ImportFromXMLNode($childnode, null);
			}
		}
		
		private static function ImportFromXMLNode(SimpleXMLElement $node, $path = null)
		{
			$nodename = $node->getName();
			
			$childnodes = $node->children();
			
			if(!is_null($path))
			{
				$path = $path.".".$nodename;
			}
			else
			{
				$path = $nodename;
			}
			
			if(!count($childnodes))
			{	
				self::$parameters[$path] = (string) $node;
			}
			else
			{
				foreach($childnodes as $childnode)
				{
					self::ImportFromXMLNode($childnode, $path);
				}
			}
		}
		
		public static function GetConfigurationParameter($parameter)
		{
			self::InitializeConfiguration();
			
			if(!array_key_exists($parameter, self::$parameters))
			{
				return null;
			}
			
			return self::$parameters[$parameter];
		}
		
		public static function GetAllParameters()
		{
			self::InitializeConfiguration();
			
			return self::$parameters;
		}
	}
?>