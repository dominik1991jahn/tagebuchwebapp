<?php
	/**
	 * Repräsentiert ein Fach
	 */
	 
	class Digikabu_Subject
	{
		  //
		 // ATTRIBUTES
		//
		 
		private /*(string)*/ $name;
		private /*(string)*/ $description;
			  
		  //
		 // CONSTRUCTORS
		//		
		
		public function Digikabu_Subject()
		{
		}

		 	  
		  //
		 // METHODS
		//
		public function __get($name)
		{
			switch($name)
			{
				case "Name": return $this->getName(); break;
				case "Description": return $this->getDescription(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Name": $this->setName($value);  break;
				case "Description": $this->setDescription($value); break;
			}
		}
		 
		  //
		 // FUNCTIONS
		//		  
		  
		  
		  //
		 // GETTERS & SETTERS
		//
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setName(/*(string)*/ $name)
		{
			return $this->name = $name;
		}
		
		public function getDescription()
		{
			return $this->description;
		}
		
		public function setDescription(/*(string)*/ $description)
		{
			$this->description = $description;
		}
		
		public function __toString()
		{
			return "I'm a subject";
		}
	}
?>