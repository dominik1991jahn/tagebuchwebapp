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