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
		
		public function CheckStatus($function, $parameters, $controlsum)
		{
			foreach($this->cache as $cacheobject)
			{
				if($cacheobject->Function <> $function) continue;
				if(!ArrayTools::Equals($parameters, $cacheobject->Parameters)) continue;
				if($controlsum <> $cacheobject->ControlSum) continue;
				
				// Modified
				return true;
			}
			
			
			// Not modified
			return false;
		}
		
		public function GetFromCache($function, $parameters)
		{
			foreach($this->cache as $cacheobject)
			{
				if($cacheobject->Function <> $function) continue;
				if(!ArrayTools::Equals($parameters, $cacheobject->Parameters)) continue;
				
				$cacheFile = "Cache/".$cacheobject->Identifier;
				
				return file_get_contents($cacheFile);
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
			
			$path = $cacheobject->Identifier;
			
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
			
			file_put_contents("Cache/".$cacheobject->Identifier, $data);
		}
		
		public function GetCacheControl()
		{
			$headers = getallheaders();
			
			$cachecontrol = (isset($headers["Cache-Control"]) ? $headers["Cache-Control"] : null);
			#return "no-cache";
			return $cachecontrol;
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