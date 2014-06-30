<?php
	class Digikabu_Day
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $status;
		private /*(int)*/ $date;
		private /*(array:Digikabu_Period)*/ $periods;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_day($date)
		{
			$this->date = $date;
			$this->periods = array();
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
				
			$date = strtotime((string) $attributes["date"]);
			
			$day = new Digikabu_Day($date);
			
			return $day;
		}
	}
?>