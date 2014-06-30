<?php
	/**
	 * Repräsentiert einen Lehrer
	 */
	 
	class Digikabu_Teacher extends Digikabu_Object
	{
		private /*(string)*/ $abbreviation;
		private /*(string)*/ $name;
		
		  //
		 // CONSTRUCTOR
		//
		public function Digikabu_Teacher()
		{
			
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Abbreviation": return $this->getAbbreviation(); break;
				case "Name": return $this->getName(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "abbreviation": $this->setAbbreviation($value); break;
				case "Name": $this->setName($value); break;
			}
		}
		
		public function toJSON()
		{
			
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		  //
		 // GETTERS / SETTERS
		//
		
		# Abbreviation
		
		public function getAbbreviation()
		{
			return $this->abbreviation;
		}
		
		public function setAbbreviation(/*(string)*/ $abbreviation)
		{
			$this->abbreviation = $abbreviation;
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