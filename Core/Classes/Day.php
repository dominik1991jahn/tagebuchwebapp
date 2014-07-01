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
		 // METHODS
		//
		
		public function AddPeriod(Digikabu_Period $period)
		{
			$this->periods[] = $period;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
				
			$date = strtotime((string) $attributes["date"]);
			
			$day = new Digikabu_Day($date);
			
			foreach($node->children() as $xperiod)
			{
				$period = Digikabu_Period::FromXMLNode($xperiod);
				
				$day->AddPeriod($period);
			}
			
			return $day;
		}
	}
?>