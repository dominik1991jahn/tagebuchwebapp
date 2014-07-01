<?php
	/**
	 * Repräsentiert ein Fach
	 */
	 
	class Digikabu_Subject extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		 
		private /*(string)*/ $name;
		private /*(string)*/ $description;
			  
		  //
		 // CONSTRUCTOR
		//		
		
		public function Digikabu_Subject()
		{
		}

		 	  
		  //
		 // METHODS
		//
		
		public function __Get($name)
		{
			switch($name)
			{
				case "Name": return $this->GetName(); break;
				case "Description": return $this->GetDescription(); break;
			}
		}
		
		public function __Set($name,$value)
		{
			switch($name)
			{
				case "Name": $this->SetName($value);  break;
				case "Description": $this->SetDescription($value); break;
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
		
		# Name
		
		public function GetName()
		{
			return $this->name;
		}
		
		public function SetName(/*(string)*/ $name)
		{
			return $this->name = $name;
		}
		
		# Description
		
		public function GetDescription()
		{
			return $this->description;
		}
		
		public function SetDescription(/*(string)*/ $description)
		{
			$this->description = $description;
		}
		
		public function __toString()
		{
			return "I'm a subject";
		}
	}
?>