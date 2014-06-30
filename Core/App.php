<?php
	class App
	{
		public static function Main()
		{
			echo "<h1>Hello App!</h1>";
			
			$s = new Digikabu_Period;
			
			$s->Date = time();
			$s->Subject = new Digikabu_Subject;
			
			echo $s->Date.": ".$s->Subject;
		}
	}
?>