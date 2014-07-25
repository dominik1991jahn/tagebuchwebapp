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
		private /*(int)*/ $splitperiod;
		private /*(string)*/ $class;
		
		private /*(Digikabu_Period)*/ $previous;
		private /*(Digikabu_Period)*/ $next;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Period()
		{
			$this->type = new Digikabu_PeriodType_Normal;
			$this->teachers = array();
			$this->rooms 	= array();
			$this->splitperiod = 0;
		}
		
		  //
		 // METHODS
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Type"		  : return $this->GetType();
				case "Date"		  : return $this->GetDate();
				case "Subject"	  : return $this->GetSubject();
				case "Previous"	  : return $this->GetPrevious();
				case "Next"		  : return $this->GetNext();
				case "Start"	  : return $this->GetStart();
				case "Duration"	  : return $this->GetDuration();
				case "Teachers"	  : return $this->GetTeachers();
				case "Rooms"	  : return $this->GetRooms();
				case "Information": return $this->GetInformation();
				case "SplitPeriod": return $this->GetSplitPeriod();
				case "Class": return $this->GetClass();
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Type"		  : $this->setType(       $value); break;
				case "Date"		  : $this->setDate(		  $value); break;
				case "Subject"	  : $this->setSubject(	  $value); break;
				case "Start"	  : $this->SetStart(	  $value); break;
				case "Duration"	  : $this->SetDuration(	  $value); break;
				case "Previous"   : $this->SetPrevious(	  $value); break;
				case "Next"		  : $this->SetNext(		  $value); break;
				case "Information": $this->SetInformation($value); break;
				case "SplitPeriod": $this->SetSplitPeriod($value); break;
				case "Class": $this->SetClass($value); break;
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
				"Type"	      => $this->Type,
				"Subject"	  => $this->Subject,
				"Start" 	  => $this->Start,
				"Duration"	  => $this->Duration,
				"Teachers"	  => $this->Teachers,
				"Rooms" 	  => $this->Rooms,
				"Information" => $this->Information,
				"SplitPeriod" => $this->SplitPeriod,
				"Class" => $this->Class
			);
		}
		
		  //
		 // GetTERS / SETTERS
		//
		
		# Type
		
		private function GetType()
		{
			return $this->type;
		}
		
		private function SetType(Digikabu_PeriodType $value)
		{
			$this->type = $value;
		}
		
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
		
		# SplitPeriod
		
		private function GetSplitPeriod()
		{
			return $this->splitperiod;
		}
		
		private function SetSplitPeriod($value)
		{
			$value = (int) $value;
			
			if($value<0) $value = -1;
			else if($value>0) $value = 1;
			
			$this->splitperiod = $value;
		}
		
		# Class
		
		private function GetClass()
		{
			return $this->class;
		}
		
		private function SetClass($value)
		{
			$this->class = $value;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$period = new Digikabu_Period;
			
			$nodes = $node->children();
			
			$periodType = new Digikabu_PeriodType_Normal;
			
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
						$information = (string) $childnode;
						$period->Information = $information;
						break;
					
					case "Teachers":
						
						$teachers = array();
						$t = -1;
						
						$teachers_normal = 0;
						$teachers_absent = 0;
						$teachers_substitutes = 0;
						
						foreach($childnode->children() as $xteacher)
						{
							$t++;
							
							if((string)$xteacher == "")
							{
								if($t == 2)
								{
									//$periodType = new Digikabu_PeriodType_Substitution;
									$teachers[0]->Status = new Digikabu_TeacherStatus_Absent;
									$teachers_absent++;
									$teachers_normal--;
								}
								else if($t == 3)
								{
									//$periodType = new Digikabu_PeriodType_Substitution;
									$teachers[1]->Status = new Digikabu_TeacherStatus_Absent;
									$teachers_absent++;
									$teachers_normal--;
								}
							}
							else
							{
								$teacher = Digikabu_Teacher::FromXMLNodeFromSchedule($xteacher);
								
								if ($teacher->Abbreviation == "admin") { continue; }
								
								switch ($t)
								{
									case 0:
										$teachers[0] = new Digikabu_TeacherStatus($teacher, new Digikabu_TeacherStatus_Normal);
										$teachers_normal++;
										break;
									case 1:
										$teachers[1] = new Digikabu_TeacherStatus($teacher, new Digikabu_TeacherStatus_Normal);
										$teachers_normal++;
										break;
									case 2: 
										//$periodType = new Digikabu_PeriodType_Substitution;
										$teachers[2] = new Digikabu_TeacherStatus($teacher, new Digikabu_TeacherStatus_Substitute);
										$teachers[0]->Status = new Digikabu_TeacherStatus_Absent;
										$teachers_substitutes++;
										$teachers_absent++;
										$teachers_normal--;
										break;
									case 3:
										//$periodType = new Digikabu_PeriodType_Substitution;
										$teachers[3] = new Digikabu_TeacherStatus($teacher, new Digikabu_TeacherStatus_Substitute);
										if(isset($teachers[1]) && $teachers[1] instanceof Digikabu_Teacher)
										{
											$teachers[1]->Status = new Digikabu_TeacherStatus_Absent;
										}
										$teachers_substitutes++;
										$teachers_absent++;
										$teachers_normal--;
										break;
								}
							}
						}

						if(!$teachers_normal && !$teachers_substitutes)
						{
							$periodType = new Digikabu_PeriodType_Canceled;
						}
						else if($teachers_substitutes)
						{
							$periodType = new Digikabu_PeriodType_Substitution;
						}
						
						foreach($teachers as $teacher)
						{
							$period->AddTeacher($teacher);
						}
						
						break;
					
					case "Rooms":
						
						foreach($childnode->children() as $xroom)
						{
							$room = Digikabu_Room::FromXMLNode($xroom);
							
							if ($room == NULL) { continue; }
							
							$period->AddRoom($room);
						}
						
						break;
					
					case "Class":
						
						$class = (string) $childnode;
						$period->Class = $class;
						
						break;
				}
			}
			/*$s = array(new Digikabu_PeriodType_Normal,new Digikabu_PeriodType_Canceled,new Digikabu_PeriodType_Substitution);
			$t = mt_rand(0,2);
			$t = $s[$t];*/
			
			//var_dump($type);
			$period->Type = $periodType;//"NORMAL";//$type;
			
			return $period;
		}
		
		  //
		 // CONSTANTS
		//
		
		const TYPE_NORMAL = "NORMAL";
		const TYPE_CANCELED = "CANCELED";
		const TYPE_SUBSTITUTION = "SUBSTITUTION";
	}
	
	abstract class Digikabu_PeriodType implements JSONSerializable
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
	
	class Digikabu_PeriodType_Normal extends Digikabu_PeriodType
	{
		public function __construct()
		{
			$this->type = Digikabu_Period::TYPE_NORMAL;
		}
	}
	
	class Digikabu_PeriodType_Canceled extends Digikabu_PeriodType
	{
		public function __construct()
		{
			$this->type = Digikabu_Period::TYPE_CANCELED;
		}
	}
	
	class Digikabu_PeriodType_Substitution extends Digikabu_PeriodType
	{
		public function __construct()
		{
			$this->type = Digikabu_Period::TYPE_SUBSTITUTION;
		}
	}
?>