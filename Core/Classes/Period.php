<?php
	/**
	 * Repräsentiert eine Stunde
	 */
	 
	class Digikabu_Period
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $date;
		private /*(Digikabu_Subject)*/ $subject;
		private /*(int)*/ $start;
		private /*(int)*/ $duration;
		private /*(array:Digikabu_TeacherStatus)*/ $teachers;
		private /*(array:Digikabu_Room)*/ $rooms;
		private /*(string)*/ $information;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Period()
		{
			
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Date": return $this->getDate(); break;
				case "Subject": return $this->getSubject(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Date": $this->setDate($value);  break;
				case "Subject": $this->setSubject($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function doSomething() {}
		
		  //
		 // GETTERS / SETTERS
		//
		
		# Date
		
		public function getDate()
		{
			return $this->date;
		}
		
		public function setDate(/*(int)*/ $date)
		{
			$this->date = $date;
		}
		
		# Subject
		
		public function getSubject()
		{
			return $this->subject;
		}
		
		public function setSubject(Digikabu_Subject $subject)
		{
			$this->subject = $subject;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			var_dump($node);
		}
	}
?>