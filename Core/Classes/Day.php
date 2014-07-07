<?php
	class Digikabu_Day extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Digikabu_DayType)*/ $type;
		private /*(int)*/ $date;
		private /*(array:Digikabu_Period)*/ $periods;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_day($date, Digikabu_DayType $type = null)
		{
			if($type == null) $type = new Digikabu_DayType_Normal;
			
			$this->date = $date;
			$this->type = $type;
			$this->periods = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddPeriod(Digikabu_Period $period)
		{
			$this->periods[] = $period;
		}
		
		public function jsonSerialize()
		{
			return array(
				"Type" => $this->Type,
				"Date" => $this->Date,
				"Periods" => $this->Periods
			);
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Type": return $this->GetType();
				case "Date": return $this->GetDate();
				case "Periods": return $this->GetPeriods();
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetType()
		{
			return $this->type;
		}
		
		private function GetDate()
		{
			return $this->date;
		}
		
		private function GetPeriods()
		{
			return $this->periods;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
				
			$date = strtotime((string) $attributes["date"]);
			$type = (isset($attributes["type"]) ? strtoupper((string) $attributes["type"]) : "NORMAL");
			$type = Digikabu_Day::GetTypeFromName($type);
			
			$day = new Digikabu_Day($date, $type);
			
			foreach($node->children() as $xperiod)
			{
				$period = Digikabu_Period::FromXMLNode($xperiod);
				
				$day->AddPeriod($period);
			}
			
			return $day;
		}
		
		public static function GetTypeFromName($name)
		{
			switch($name)
			{
				case "NORMAL": return new Digikabu_DayType_Normal;
				case "HOLIDAY": return new Digikabu_DayType_Holiday;
				default: throw new Exception("Day Type '".$name."' not defined");
			}
		}
		
		  //
		 // CONSTANTS
		//
		
		const TYPE_NORMAL = "NORMAL";
		const TYPE_HOLIDAY = "HOLIDAY";
	}
	
	abstract class Digikabu_DayType
	{
		  //
		 // ATTRIBUTES
		//
		
		protected /*(int)*/ $type;
		
		  //
		 // METHODS
		//
		
		public function __toString()
		{
			return $this->type;
		}
	}
	
	class Digikabu_DayType_Normal extends Digikabu_DayType
	{
		public function Digikabu_DayType_Normal()
		{
			$this->type = Digikabu_Day::TYPE_NORMAL;
		}
	}
	
	class Digikabu_DayType_Holiday extends Digikabu_DayType
	{
		public function Digikabu_DayType_Holiday()
		{
			$this->type = Digikabu_Day::TYPE_HOLIDAY;
		}
	}
?>