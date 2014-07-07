<?php
	class Digikabu_TeacherStatus extends Digikabu_Object
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Digikabu_Teacher)*/ $teacher;
		private /*(Digikabu_TeacherStatus_Abstract)*/ $status;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function Digikabu_Teacherstatus(Digikabu_Teacher $teacher, Digikabu_TeacherStatus_Abstract $status)
		{
			$this->teacher = $teacher;
			$this->status = $status;
		}
		
		  //
		 // METHODS
		//
		
		public function jsonSerialize()
		{
			return array(
				"Teacher" => $this->Teacher,
				"Status" => $this->Status
			);
		}
		
		public function __get($name)
		{
			switch($name)
			{
				case "Teacher": return $this->GetTeacher(); break;
				case "Status": return $this->GetStatus(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Teacher": $this->SetTeacher($value);  break;
				case "Status": $this->SetStatus($value); break;
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Teacher
		
		private function GetTeacher()
		{
			return $this->teacher;
		}
		
		private function SetTeacher(Digikabu_Teacher $teacher)
		{
			$this->teacher = $teacher;
		}
		
		# Status
		
		private function /*(int)*/ GetStatus()
		{
			return $this->status;
		}
		
		private function /*(void)*/ SetStatus(Digikabu_TeacherStatus_Abstract $status)
		{
			$this->status = $status;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function /*(string)*/ GetStatusName(/*(int)*/ $status)
		{
			switch($status)
			{
				case 1: return "NORMAL";
				case 2: return "SUBSTITUTION";
				case 4: return "ABSENCE";
				default: return null;
			}
		}
		
		public static function GetStatusForName($name)
		{
			switch($name)
			{
				case "NORMAL": return new Digikabu_TeacherStatus_Normal;
				case "ABSENT": return new Digikabu_TeacherStatus_Absent;
				case "SUBSTITUTION": return new Digikabu_TeacherStatus_Substitution;
				default: throw new Exception("Invalid Teacher Status '".$name."'");
			}
		}
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$attributes = $node->attributes();
			
			$teacher = (string) $node;
			$teacher = new Digikabu_Teacher($teacher);
			
			$status = (isset($attributes["status"]) ? strtoupper((string) $attributes["status"]) : "NORMAL");
			$status = self::GetStatusForName($status);
			
			$tstatus = new Digikabu_TeacherStatus($teacher, $status);
			
			return $tstatus;
		}
		
		  //
		 // CONSTANTS
		//
		
		const /*(int)*/ STATUS_NORMAL = "NORMAL";
		const /*(int)*/ STATUS_SUBSTITUTION = "SUBSTITUTION";
		const /*(int)*/ STATUS_ABSENT = "ABSENT";
	}

	abstract class Digikabu_TeacherStatus_Abstract
	{
		  //
		 // ATTRIBUTES
		//
		
		protected /*(int)*/ $status;
		
		  //
		 // METHODS
		//
		
		public function __toString()
		{
			return $this->status;
		}
	}
	
	class Digikabu_TeacherStatus_Normal extends Digikabu_TeacherStatus_Abstract
	{
		public function Digikabu_TeacherStatus_Normal()
		{
			$this->status = Digikabu_TeacherStatus::STATUS_NORMAL;
		}
	}
	
	class Digikabu_TeacherStatus_Substitution extends Digikabu_TeacherStatus_Abstract
	{
		public function Digikabu_TeacherStatus_Substitution()
		{
			$this->status = Digikabu_TeacherStatus::STATUS_SUBSTITUTION;
		}
	}
	
	class Digikabu_TeacherStatus_Absent extends Digikabu_TeacherStatus_Abstract
	{
		public function Digikabu_TeacherStatus_Absenr()
		{
			$this->status = Digikabu_TeacherStatus::STATUS_ABSENT;
		}
	}
?>