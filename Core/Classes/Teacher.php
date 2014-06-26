<?php
	/**
	 * Repräsentiert einen Lehrer
	 */
	 
	class Digikabu_Teacher
	{
		private /*(string)*/ $kurz;
		private /*(string)*/ $name;
		
		//
		// Constructor
		//
		public function Digikabu_Teacher()
		{
			
		}
		
		//
		// Methoden
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Kurz": return $this->getKurz(); break;
				case "Name": return $this->getName(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Kurz": $this->setKurz($value); break;
				case "Name": $this->setName($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		//
		// Getter / Setter
		//
		
		# Kurz
		
		public function getKurz()
		{
			return $this->kurz;
		}
		
		public function setKurz(/*(string)*/ $kurz)
		{
			$this->kurz = $kurz;
		}
		
		# Name
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setName(/*(string)*/ $name)
		{
			$this->name = $name;
		}
	}
?>