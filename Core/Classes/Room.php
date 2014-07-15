<?php
	/**
	 * Repräsentiert ein Klassenzimmer
	 */
	 
	class Digikabu_Room extends Digikabu_Object
	{
		private /*(string)*/ $room;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Room($room = NULL)
		{
			$this->Room = $room;
		}
		
		  //
		 // METHODS
		//
		
		public function jsonSerialize()
		{
			return $this->Room;
		}
		
		public function __get($name)
		{
			switch($name)
			{
				case "Room": return $this->GetRoom(); break;
			}
		}
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Room": $this->SetRoom($value);  break;
			}
		}
		
		public function toJSON()
		{
			
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$room = (string) $node;
			if (empty($room)) { return NULL; }
			$room = new Digikabu_Room($room);
			
			return $room;
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		# Room
		public function GetRoom()
		{
			return $this->room;
		}
		
		public function SetRoom(/*(int)*/ $room)
		{
			$this->room = $room;
		}
	}
?>