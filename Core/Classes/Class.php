<?php
	/**
	 * Repräsentiert eine Schulklasse
	 */
	 
	class Digikabu_Class
	{
		private /*(string)*/ $bez;//
		// Constructor
		//
		public function Digikabu_Class()
		{
			
		}
		
		//
		// Methoden
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Bez": return $this->getBez(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Bez": $this->setBez($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		//
		// Getter / Setter
		//
		
		# Bez
		
		public function getBez()
		{
			return $this->bez;
		}
		
		public function setBez(/*(string)*/ $bez)
		{
			$this->bez = $bez;
		}
	}
?>