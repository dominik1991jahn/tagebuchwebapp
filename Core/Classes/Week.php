<?php
	class Digikabu_Week
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $status;
		private /*(int)*/ $from;
		private /*(int)*/ $to;
		private /*(array:Digikabu_Day)*/ $days;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Week($from, $to)
		{
			$this->from = $from;
			$this->to = $to;
			$this->days = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddDay(Digikabu_Day $day)
		{
			$this->days[] = $day;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
			
			$from = strtotime((string) $attributes["from"]);
			$to = strtotime((string) $attributes["to"]);
			
			$week = new Digikabu_Week($from, $to);
			
			foreach($node->children() as $xday)
			{
				$day = Digikabu_Day::FromXMLNode($xday);
				
				$week->AddDay($day);
			}
			
			return $week;
		}
	}
?>