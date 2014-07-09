<?php
	class Digikabu_Week extends Digikabu_Object
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
			
			$this->from = date("Y-m-d",$from);
			$this->to = date("Y-m-d",$to);
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
				"Days" => $this->Days
			);
		}
		
		  //
		 // METHODS
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Type": return $this->GetType();
				case "From": return $this->GetFrom();
				case "Days": return $this->GetDays();
				default: throw new InvalidArgumentException("Field ".__CLASS__.".".$field." not defined");
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetType()
		{
			return $this->type;
		}
		
		private function GetFrom()
		{
			return $this->from;
		}
		
		private function GetDays()
		{
			return $this->days;
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
	
	abstract class Digikabu_WeekType implements JSONSerializable
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
			return (string) $this->type;
		}
		
		public function jsonSerialize()
		{
			return (string) $this;
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