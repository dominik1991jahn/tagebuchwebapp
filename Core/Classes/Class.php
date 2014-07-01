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
		
		public function __Get($name)
		{
			switch($name)
			{
				case "Description": return $this->GetDescription(); break;
			}
		}
		
		public function __Set($name,$value)
		{
			switch($name)
			{
				case "Description": $this->SetDescription($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		  //
		 // GetTERS / SetTERS
	    //
		
		# Description
		
		public function GetDescription()
		{
			return $this->description;
		}
		
		public function SetDescription(/*(string)*/ $description)
		{
			$this->description = $description;
		}
	}
?>