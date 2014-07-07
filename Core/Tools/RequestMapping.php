<?php
	class RequestMapping
	{
		  //
		 // VARIABLES
		//
		
		private static /*(array<String, Mixed>)*/ $mappings;
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetURLForRequest($request, $parameters = array())
		{
			self::InitializeMapping();
			
			if(!array_key_exists($request, self::$mappings))
			{
				return null;
			}
			
			$url = self::$mappings[$request];
			
			foreach(Configuration::GetAllParameters() as $param => $value)
			{
				$parameters["Configuration:".$param] = $value;
			}
			
			foreach($parameters as $name => $value)
			{
				$url = str_replace("{".$name."}", $value, $url);
			}
			
			return $url;
		}
		
		private static function InitializeMapping()
		{
			if(!(self::$mappings == null))
			{
				return null;
			}
			
			self::$mappings = array();
			
			$xmlMapping = simplexml_load_file(Configuration::GetConfigurationParameter("FileMapping.RequestMapping"));
			
			foreach($xmlMapping->children() as $xmlMap)
			{
				self::ImportFromXMLNode($xmlMap, null);
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
				self::$mappings[$path] = (string) $node;
			}
			else
			{
				foreach($childnodes as $childnode)
				{
					self::ImportFromXMLNode($childnode, $path);
				}
			}
		}
		
	}
?>