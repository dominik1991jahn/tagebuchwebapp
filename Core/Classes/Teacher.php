<?php
	/**
	 * Repräsentiert einen Lehrer
	 */
	 
	class Digikabu_Teacher extends Digikabu_Object
	{
		private /*(string)*/ $abbreviation;
		private /*(string)*/ $name;
		
		  //
		 // CONSTRUCTOR
		//

		public function Digikabu_Teacher()
		{
			switch(func_num_args())
			{
				case 0: break;
				case 2:
					$abbreviation = func_get_arg(0);
					$name = func_get_arg(1);
					
					if(!is_string($abbreviation) || !is_string($name))
					{
						throw new InvalidArgumentException("Overload (".gettype($abbreviation).", ".gettype($name).") not defined");
					}
			
					$this->abbreviation = $abbreviation;
					$this->name = $name;
					break;
				
				default: throw new InvalidArgumentException("Unsupported method call");
			}
		}
		
		  //
		 // METHODS
		//
		
		public function jsonSerialize()
		{
			return array(
				"Abbreviation" => $this->Abbreviation,
				"Name" => $this->Name
			);
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($name)
		{
			switch($name)
			{
				case "Abbreviation": return $this->GetAbbreviation(); break;
				case "Name": return $this->GetName(); break;
			}
		}
		
		public function __set($name,$value)
		{
			switch($name)
			{
				case "Abbreviation": $this->SetAbbreviation($value); break;
				case "Name": $this->SetName($value); break;
			}
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function FromXMLNode(SimpleXMLElement $node)
		{
			$teacher = new Digikabu_Teacher();
			
			$name = (string)$node;
			
			$teacher->Abbreviation=$name;
			
			return $teacher;
			/*$teacher = new Digikabu_Teacher();
			
			$nodes = $node->children();
			
			foreach($nodes as $childnode)
			{
				switch($childnode->getName())
				{
					case "Code":
						$abbreviation = (string) $childnode;
						$teacher->Abbreviation = $abbreviation;						
						break;
					
					case "Name":
						$name = (string) $childnode;
						$teacher->Name = $name;
						break;
				}
			}		
			return $teacher;*/
		}
		public static function FromXMLNodeFromSchedule(SimpleXMLElement $node)
		{
			$teacher = new Digikabu_Teacher();
			
			$name = (string)$node;
			
			$teacher->Abbreviation=$name;
			
			return $teacher;
		}
		
		  //
		 // GetTERS / SetTERS
		//
		
		# Abbreviation
		
		public function GetAbbreviation()
		{
			return $this->abbreviation;
		}
		
		public function SetAbbreviation(/*(string)*/ $abbreviation)
		{
			$this->abbreviation = $abbreviation;
		}
		
		# Name
		
		public function GetName()
		{
			return $this->name;
		}
		
		public function SetName(/*(string)*/ $name)
		{
			$this->name = $name;
		}
	}
?>