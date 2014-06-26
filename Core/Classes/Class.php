<?php
	/**
	 * Repräsentiert eine Schulklasse
	 */
	 
	class Digikabu_Class extends Digikabu_Object
	{
		private /*(string)*/ $description;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Class()
		{
			
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Description": return $this->getDescription(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Description": $this->setDescription($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		  //
		 // GETTERS / SETTERS
	    //
		
		# Description
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription(/*(string)*/ $description)
		{
			$this->description = $description;
		}
	}
?>