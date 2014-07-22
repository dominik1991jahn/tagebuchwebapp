<?php
	/**
	 * Repräsentiert eine Stunde
	 */
	 
	class Digikabu_Event extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $from;
		private /*(int)*/ $to;
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
				case "From"		  : return $this->GetFrom();
				case "To"		  : return $this->GetTo();
				case "Description": return $this->GetDescription();
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "From"		  : $this->SetFrom($value); break;
				case "To"		  : $this->SetTo($value); break;
				case "Description": $this->SetDescription($value); break;
			}
		}
		
		public function jsonSerialize()
		{
			return array(
				"From"	      => date("Y-m-d",$this->From),
				"To"	      => (!is_null($this->To) ? date("Y-m-d",$this->To) : null),
				"Description"	  => $this->Description
			);
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		# From
		
		private function GetFrom()
		{
			return $this->from;
		}
		
		private function SetFrom(/*(int)*/ $value)
		{
			$this->from = $value;
		}
		
		# To
		
		private function GetTo()
		{
			return $this->to;
		}
		
		private function SetTo(/*(int)*/ $value)
		{
			$this->to = $value;
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
			$from = null;
			$to = null;
			
			foreach($nodes as $node)
			{
				switch($node->getName())
				{
					case "Text": $text = (string) $node; break;
				}
			}
			
			$from = (isset($xmlAttributes["date"]) ? strtotime((string) $xmlAttributes["date"]) : null);
			
			$event->Description = $text;
			$event->From = $from;
			$event->To = $to;
			
			return $event;
		}
	}
?>