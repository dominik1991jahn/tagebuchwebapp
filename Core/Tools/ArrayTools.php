<?php
	class ArrayTools
	{
		/**
		 * Do NOT use with associative Arrays!
		 */
		public static function GetSubset($array, $startat, $items = 0)
		{
			$result = array();
			
			$maxindex = count($array);
			
			if($items > 0)
			{
				$maxindex = $startat + $items;
			}
			
			for($item = $startat; $item < $maxindex; $item++)
			{
				$result[] = $array[$item];
			}
			
			return $result;
		}
		
		public static function MergeKeysAndValues($keys, $values)
		{
			$result = array();
			
			for($item = 0; $item < count($keys); $item++)
			{
				$key = $keys[$item];
				$value = $values[$item];
				
				$result[$key] = $value;
			}
			
			return $result;
		}
		
		public static function ReorderElements($order, $elements)
		{
			$result = array();
			
			foreach($order as $key)
			{
				$result[$key] = $elements[$key];
			}
			
			return $result;
		}
		
		public static function Equals($array1, $array2)
		{
			if(count($array1) <> count($array2))
			{
				return false;
			}
			
			$keys = array_keys($array1);
			
			for($i = 0; $i < count($keys); $i++)
			{
				if(!array_key_exists($keys[$i], $array2))
				{
					return false;
				}
				
				$value1 = $array1[$keys[$i]];
				$value2 = $array2[$keys[$i]];
				
				if($value1 <> $value2)
				{
					return false;
				}
			}
			
			return true;
		}
	}
?>