<?php
	function TestMethod($a, $b, $c)
	{
		echo "\$a => ".$a."<br/>\$c => ".$c."<br/>\$b => ".$b;
	}
	
	$method = "TestMethod";
	$params = array("c" => "!","a" => "Hallo", "b" => "Welt");
	
	call_user_func_array($method, $params);
	
	$rmethod = new ReflectionFunction($method);
	
	$params = OrderParameters($rmethod->getParameters(), $params);
	
	call_user_func_array($method, $params);
	
	function OrderParameters($order, $parameters)
	{
		$result = array();
		
		foreach($order as $param)
		{
			$param = $param->getName();
			$result[$param] = $parameters[$param];
		}
		
		return $result;
	}
?>