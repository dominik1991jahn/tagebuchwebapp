<?php
	/**
	 * Repräsentiert eine Stunde
	 */
	 
	class Digikabu_Period extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Digikabu_PeriodType)*/ $type;
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
				case "Date": return $this->GetDate(); break;
				case "Subject": return $this->GetSubject(); break;
				case "Previous": return $this->GetPrevious(); break;
				case "Next": return $this->GetNext(); break;
				case "Start": return $this->GetStart();
				case "Duration": return $this->GetDuration();
				case "Teachers": return $this->GetTeachers();
				case "Rooms": return $this->GetRooms();
				case "Information": return $this->GetInformation();
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Date": $this->setDate($value);  break;
				case "Subject": $this->setSubject($value); break;
				case "Start": $this->SetStart($value); break;
				case "Duration": $this->SetDuration($value); break;
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
		
		public function jsonSerialize()
		{
			return array(
				"Type" => $this->Type,
				"Subject" => $this->Subject,
				"Start" => $this->Start,
				"Duration" => $this->Duration,
				"Teachers" => $this->Teachers,
				"Rooms" => $this->Rooms,
				"Information" => $this->Information
			);
		}
		
		  //
		 // GetTERS / SETTERS
		//
		
		# Date
		
		private function GetDate()
		{
			return $this->date;
		}
		
		private function SetDate(/*(int)*/ $date)
		{
			$this->date = $date;
		}
		
		# Subject
		
		private function GetSubject()
		{
			return $this->subject;
		}
		
		private function SetSubject(Digikabu_Subject $subject)
		{
			$this->subject = $subject;
		}
		
		# Previous
		
		private function GetPrevious()
		{
			return $this->previous;
		}
		
		private function SetPrevious(Digikabu_Period $previous)
		{
			$this->previous = $previous;
		}
		
		# Next
		
		private function GetNext()
		{
			return $this->next;
		}
		
		private function SetNext(Digikabu_Period $next)
		{
			$this->next = $next;
		}
		
		# Start
		
		private function GetStart()
		{
			return $this->start;
		}
		private function SetStart(/*(int)*/$start)
		{
			$this->start=$start;
		}
		
		
		# Duration
		
		private function GetDuration()
		{
			return $this->duration;
		}
		
		private function SetDuration(/*(int)*/ $duration)
		{
			$this->duration=$duration;
		}
		# Teachers
		
		private function GetTeachers()
		{
			return $this->teachers;
		}
		
		private function SetTeachers(/*(array:Digikabu_TeacherStatus)*/ $teachers)
		{
			$this->teachers=$teachers;
		}
		# Rooms
		
		private function GetRooms()
		{
			return $this->rooms;
		}
		
		private function SetRooms(/*(array:Digikabu_Room)*/ $rooms)
		{
			$this->rooms=$rooms;
		}
		# Information
		
		private function GetInformation()
		{
			return $this->information;
		}
		
		private function SetInformation(/*(string)*/$information)
		{
			$this->information=$information;
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
					
					case "End":
						$end = (int) $childnode;
						
						$duration = $end-$period->start+1;
						
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