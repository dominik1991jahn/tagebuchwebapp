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
		public function Digikabu_Teacher($abbreviation)
		{
			$this->abbreviation = $abbreviation;
		}
		
		  //
		 // METHODS
		//
		
		public function __Get($name)
		{
			switch($name)
			{
				case "Abbreviation": return $this->GetAbbreviation(); break;
				case "Name": return $this->GetName(); break;
			}
		}
		
		public function __Set($name,$value)
		{
			switch($name)
			{
				case "Abbreviation": $this->SetAbbreviation($value); break;
				case "Name": $this->SetName($value); break;
			}
		}
		
		public function toJSON()
		{
			
		}
		
		  //
		 // FUNCTIONS
		//
		
		
		  //
		 // GetTERS / SetTERS
		//
		
		# Abbreviation
		
		public function GetAbbreviation()
		{
			return $this->abbreviation;
		}
		
		public function SetAbbreviation(/*(string)*/ $abbreviation)
		{
			$this->abbreviation = $abbreviation;
		}
		
		# Name
		
		public function GetName()
		{
			return $this->name;
		}
		
		public function SetName(/*(string)*/ $name)
		{
			$this->name = $name;
		}
	}
?>