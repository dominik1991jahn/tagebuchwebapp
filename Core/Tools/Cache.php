<?php
	class Cache
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<String, CacheObject)*/ $cache;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->InitializeCache();
		}
		
		  //
		 // METHODS
		//
		
		private function InitializeCache()
		{
			if(!($this->cache == null))
				return null;
			
			$this->cache = array(); 
			
			$xmlCache = simplexml_load_file("Core/Configuration/Cache.xml");
			
			foreach($xmlCache->children() as $childnode)
			{
				$cacheobject = CacheObject::ImportFromXMLNode($childnode, null);
				
				$this->cache[$cacheobject->Identifier] = $cacheobject;
			}
		}
		
		public function GetFromCache($function, $parameters, $controlsum)
		{
			foreach($this->cache as $cacheobject)
			{
				if($cacheobject->Function <> $function) continue;
				if(!ArrayTools::Equals($parameters, $cacheobject->Parameters)) continue;
				if($controlsum <> $cacheobject->ControlSum) continue;
				
				$cacheFile = "Cache/".$cacheobject->Identifier.".json";
				
				return file_get_contents($cacheFile);
				
				break;
			}
		}
		
		public function AddToCache(CacheObject $cacheobject, $data)
		{
			$xmlCache = simplexml_load_file("Core/Configuration/Cache.xml");
			
			$isAlreadyCached = false;
			
			foreach($xmlCache->children() as $childnode)
			{
				if($childnode->getName() <> $cacheobject->Function)
				{
					continue;
				}
				
				$cacheobject2 = CacheObject::ImportFromXMLNode($childnode, null);
				
				if(!ArrayTools::Equals($cacheobject->Parameters, $cacheobject2->Parameters))
				{
					continue;
				}
				
				$childnode = $cacheobject->ControlSum;
				$isAlreadyCached = true;
			}
			
			if(!$isAlreadyCached)
			{
				$xmlCacheObject = $xmlCache->addChild($cacheobject->Function, $cacheobject->ControlSum);
				foreach($cacheobject->Parameters as $param => $value)
				{
					$xmlCacheObject->addAttribute($param, $value);
				}
			}
			
			$xmlCache->asXML("Core/Configuration/Cache.xml");
			
			file_put_contents("Cache/".$cacheobject->Identifier.".json", $data);
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(Cache)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetInstance()
		{
			if(self::$instance == null)
			{
				self::$instance = new Cache;
			}
			
			return self::$instance;
		}
	}
?>