<?php
	/**
	 * Repräsentiert eine Stunde
	 */
	 
	class Digikabu_Event extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $date;
		private /*(string)*/ $description;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct()
		{
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Date"		  : return $this->GetDate();
				case "Description": return $this->GetDescription();
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Date"		  : $this->SetDate(		  $value); break;
				case "Description": $this->SetDescription(	  $value); break;
			}
		}
		
		public function jsonSerialize()
		{
			return array(
				"Date"	      => date("Y-m-d",$this->Date),
				"Description"	  => $this->Description
			);
		}
		
		  //
		 // GetTERS / SETTERS
		//
		
		# Date
		
		private function GetDate()
		{
			return $this->date;
		}
		
		private function SetDate(/*(int)*/ $date)
		{
			$this->date = $date;
		}
		
		# Description
		
		private function GetDescription()
		{
			return $this->description;
		}
		
		private function SetDescription($value)
		{
			$this->description = $value;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$event = new Digikabu_Event;
			
			$nodes = $node->children();
			$xmlAttributes = $node->attributes();
			
			$text = null;
			$date = null;
			
			foreach($nodes as $node)
			{
				switch($node->getName())
				{
					case "Text": $text = (string) $node; break;
				}
			}
			
			$date = (isset($xmlAttributes["date"]) ? strtotime((string) $xmlAttributes["date"]) : null);
			
			$event->Description = $text;
			$event->Date = $date;
			
			return $event;
		}
	}
?>