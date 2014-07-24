<?php
	header("Content-Type: text/cache-manifest");
	
	$files = array();
	$files = array_merge(glob("js/*.*"),glob("css/*.*"),glob("img/*.*"),glob("img/icons-png/*.*"),glob("img/icons-svg/*.*"));
	$files[] = "index.html";
	
	$lastchange = 0;
	#$lastchange = time();
	
	foreach($files as $file)
	{
		$modified = filemtime($file);
		
		if($lastchange < $modified)
		{
			$lastchange = $modified;
		}
	}
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
	echo "#".date("Y-m-d H:i:s",$lastchange);
?>