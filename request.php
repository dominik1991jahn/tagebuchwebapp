<?php
	include("Core/App.php");
	
	include("Core/Classes/Object.php");
	include("Core/Classes/Period.php");
	include("Core/Classes/Subject.php");
	include("Core/Classes/Teacher.php");
	include("Core/Classes/Class.php");
	include("Core/Classes/TeacherStatus.php");
	include("Core/Classes/Room.php");
	include("Core/Classes/Week.php");
	include("Core/Classes/Day.php");
	include("Core/Classes/Event.php");
	
	include("Core/Tools/HttpRequest.php");
	include("Core/Tools/Configuration.php");
	include("Core/Tools/RequestMapping.php");
	include("Core/Tools/RequestHandlerMapping.php");
	include("Core/Tools/ArrayTools.php");
	
	include("Core/Tools/Tunnel.php");
	
	App::Main();
?>
