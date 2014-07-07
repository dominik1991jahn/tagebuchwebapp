<?php
	class Digikabu_Week# implements JSONSerializable
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Digikabu_WeekType)*/ $type;
		private /*(int)*/ $from;
		private /*(int)*/ $to;
		private /*(array:Digikabu_Day)*/ $days;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Week($from, $to, Digikabu_WeekType $type = null)
		{
			if($type == null) $type = new Digikabu_WeekType_Normal;
			
			$this->from = $from;
			$this->to = $to;
			$this->type = $type;
			$this->days = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddDay(Digikabu_Day $day)
		{
			$this->days[] = $day;
		}
		
		public function jsonSerialize()
		{
			return array(
				"Type" => $this->Type,
				"From" => $this->From,
				"To" => $this->To
			);
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
			
			$from = strtotime((string) $attributes["from"]);
			$to = strtotime((string) $attributes["to"]);
			$type = (isset($attributes["type"]) ? strtoupper((string) $attributes["type"]) : "NORMAL");
			$type = Digikabu_Week::GetTypeFromName($type);
			
			$week = new Digikabu_Week($from, $to, $type);
			
			foreach($node->children() as $xday)
			{
				$day = Digikabu_Day::FromXMLNode($xday);
				
				$week->AddDay($day);
			}
			
			return $week;
		}
		
		public static function GetTypeFromName($name)
		{
			switch($name)
			{
				case "NORMAL": return new Digikabu_WeekType_Normal;
				case "VACATIONS": return new Digikabu_WeekType_Vacations;
				default: throw new Exception("Week Type '".$name."' not defined");
			}
		}
		
		  //
		 // CONSTANTS
		//
		
		const TYPE_NORMAL = "NORMAL";
		const TYPE_VACATIONS = "VACATIONS";
	}
	
	abstract class Digikabu_WeekType
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
	
	class Digikabu_WeekType_Normal extends Digikabu_WeekType
	{
		public function Digikabu_WeekType_Normal()
		{
			$this->type = Digikabu_Week::TYPE_NORMAL;
		}
	}
	
	class Digikabu_WeekType_Vacations extends Digikabu_WeekType
	{
		public function Digikabu_WeekType_Vacations()
		{
			$this->type = Digikabu_Week::TYPE_VACATIONS;
		}
	}
?>