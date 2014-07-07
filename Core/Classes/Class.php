<?php
	/**
	 * Repräsentiert eine Schulklasse
	 */
	 
	class Digikabu_Class extends Digikabu_Object
	{
		private /*(string)*/ $name;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Class(/*(string)*/$name)
		{
			$this->name = $name;
		}
		
		  //
		 // METHODS
		//
		
		public function jsonSerialize()
		{
			return array(
				"Name" => $this->Name
			);
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Name": return $this->GetName(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Name": $this->SetName($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$name = (string) $node;
			
			$class = new Digikabu_Class($name);
			
			return $class;
		}
		
		  //
		 // GetTERS / SetTERS
	    //
		
		# name
		
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