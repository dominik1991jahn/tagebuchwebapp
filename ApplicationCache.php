<?php
	date_default_timezone_set("Europe/Berlin");
	
	header("Content-Type: text/cache-manifest");
	
	$files = array(
		"index.html",
		"img/icons-svg/arrow-l-white.svg",
		"img/icons-svg/arrow-r-white.svg",
		"img/icons-svg/gear-white.svg",
		"img/icons-svg/carat-d-black.svg");
	$files = array_merge($files, glob("js/*.*"),glob("css/*.*"),glob("img/*.*"));
	
	$lastchange = 0;
	$filesize = 0;
	
	foreach($files as $file)
	{
		$modified = filemtime($file);
		$filesize += filesize($file);
		
		if($lastchange < $modified)
		{
			$lastchange = $modified;
		}
	}
	
	$filesize /= 1024;
	$filesize = (int) $filesize;
	#$lastchange = time();
?>
CACHE MANIFEST

CACHE:
<?php
foreach($files as $file)
{
	echo $file."\n";
}
?>

NETWORK:
*

<?php
	//if anything has changed, add this comment:
	//echo "#".$dateOfLastChange;
	echo "#".date("Y-m-d H:i:s",$lastchange)."\n#".$filesize." kByte";
?>