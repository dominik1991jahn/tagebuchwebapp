function loginHandler()
	{
		loginname = $("#loginname").val();
		password = $("#password").val();
		expires = (new Date((new Date().getTime()+(30*86400000)))).toString();
		
		document.cookie = "loginname="+loginname+";expires = "+expires;
		document.cookie = "password="+password+";expires = "+expires;
		
		document.location = 'index.html';
		
		return false;
	}
	
	function classSelectHandler()
	{
		selectedClass = $("#classSelection").val();
		mode = selectedClass.substring(0,1);
		
		if(mode == "c") mode = "class";
		else if(mode == "t") mode = "teacher";
		
		selectedClass = selectedClass.substring(2).toLowerCase();
		
		expires = (new Date((new Date().getTime()+(300*86400000)))).toString();
		document.cookie = "class="+selectedClass+";expires = "+expires;
		document.cookie = "displaymode="+mode+";expires = "+expires;
		
		document.location = 'index.html';
		
		return false;
	}