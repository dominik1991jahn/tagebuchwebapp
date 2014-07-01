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
		
		private /*(Digikabu_Period)*/ $previous;
		private /*(Digikabu_Period)*/ $next;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Period()
		{
			$this->teachers = array();
			$this->rooms = array();
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
				case "Previous": return $this->GetPrevious(); break;
				case "Next": return $this->GetNext(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Date": $this->setDate($value);  break;
				case "Subject": $this->setSubject($value); break;
				case "Previous": $this->SetPrevious($value); break;
				case "Next": $this->SetNext($value); break;
			}
		}
		
		public function AddTeacher(Digikabu_TeacherStatus $tstatus)
		{
			$this->teachers[] = $tstatus;
		}
		
		public function AddRoom(Digikabu_Room $room)
		{
			$this->rooms[] = $room;
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
		
		# Previous
		
		public function GetPrevious()
		{
			return $this->previous;
		}
		
		public function SetPrevious(Digikabu_Period $previous)
		{
			$this->previous = $previous;
		}
		
		# Next
		
		public function GetNext()
		{
			return $this->next;
		}
		
		public function SetNext(Digikabu_Period $next)
		{
			$this->next = $next;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$period = new Digikabu_Period;
			
			$nodes = $node->children();
			
			foreach($nodes as $childnode)
			{
				switch($childnode->getName())
				{
					case "Subject":
						$subject = (string) $childnode;
						$subject = new Digikabu_Subject($subject);
						
						$period->Subject = $subject;
						break;
					
					case "Start":
						$start = (int) $childnode;
						$period->Start = $start;
						break;
					
					case "Duration":
						$duration = (int) $childnode;
						$period->Duration = $duration;
						break;
					
					case "Description":
						$description = (string) $childnode;
						$period->Description = $description;
						break;
					
					case "Teachers":
						
						foreach($childnode->children() as $xteacher)
						{
							$teacherstatus = Digikabu_TeacherStatus::FromXMLNode($xteacher);
							
							$period->AddTeacher($teacherstatus);
						}
						
						break;
					
					case "Rooms":
						
						foreach($childnode->children() as $xroom)
						{
							$room = Digikabu_Room::FromXMLNode($xroom);
							
							$period->AddRoom($room);
						}
						
						break;
				}
			}
			
			return $period;
		}
	}
?>