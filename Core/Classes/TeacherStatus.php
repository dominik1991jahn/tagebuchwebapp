<?php
	class Digikabu_TeacherStatus
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Digikabu_Teacher)*/ $teacher;
		private /*(int)*/ $status;
		
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
		
		public function __get($name)
		{
			switch($name)
			{
				case "Teacher": return $this->getTeacher(); break;
				case "Status": return $this->getStatus(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Teacher": $this->setTeacher($value);  break;
				case "Status": $this->setStatus($value); break;
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Teacher
		
		public function getTeacher()
		{
			return $this->teacher;
		}
		
		public function setTeacher(Digikabu_Teacher $teacher)
		{
			$this->teacher = $teacher;
		}
		
		# Status
		
		public function /*(int)*/ getStatus()
		{
			return $this->status;
		}
		
		public function /*(void)*/ setStatus(Digikabu_TeacherStatus_Abstract $status)
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
		
		public static function /*(int)*/ GetStatusForName(/*(string)*/ $name)
		{
			switch($name)
			{
				case "NORMAL": return self::STATUS_NORMAL;
				case "SUBSTITUTION": return self::STATUS_SUBSTITUTION;
				case "ABSENCE": return self::STATUS_ABSENCE;
				default: return null;
			}
		}
		
		  //
		 // CONSTANTS
		//
		
		const /*(int)*/ STATUS_NORMAL = 1;
		const /*(int)*/ STATUS_SUBSTITUTION = 2;
		const /*(int)*/ STATUS_ABSENCE = 4;
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
	
	class Digikabu_TeacherStatus_Absence extends Digikabu_TeacherStatus_Abstract
	{
		public function Digikabu_TeacherStatus_Absence()
		{
			$this->status = Digikabu_TeacherStatus::STATUS_ABSENCE;
		}
	}
?>