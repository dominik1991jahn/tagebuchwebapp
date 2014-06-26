<?php
	/**
	 * Repräsentiert ein Klassenzimmer
	 */
	 
	class Digikabu_Room extends Digikabu_Object
	{
		private /*(int)*/ $number;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Room()
		{
			
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Number": return $this->getNumber(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Number": $this->setNumber($value);  break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		
		  //
		 // GETTERS / SETTERS
		//
		
		# Number
		
		public function getNumber()
		{
			return $this->number;
		}
		
		public function setDate(/*(int)*/ $number)
		{
			$this->number = $number;
		}
		
	}
?>