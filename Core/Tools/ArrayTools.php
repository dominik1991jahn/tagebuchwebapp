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
	}
?>