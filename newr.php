
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">

<html>
<head>
<title>enr</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<link rel="icon" href="favicon.ico">
<link rel="SHORTCUT ICON" href="favicon.ico">
</head>
<body>

<?

require('auth.php');

$dbl = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
if(!$dbl) {
	die('SQL error... sorry ! ^^; I\'m on it !');
}

if ($clean)
{ 
	echo "cleaning..";
	$query = "update enralbums set new = 1 where new = 2";
	$result = mysqli_query($dbl, $query);
}

$query = "select * from enralbums where new > 1 order by cat";
$result = mysqli_query($dbl, $query);
while($tmp = mysqli_fetch_array($result)) {
  $catal[] = $tmp;
}

include ("misc.php");

if ($dbl) mysqli_close($dbl);

?>

<!-- dada html & industrialpt & noerror -->
<!--
<p><br />Some new releases are available for download:<br />
<br />
<? 
for ($i = 0; $i < count($catal); $i++):
	$title = $catal[$i]["cat"];
	if (strncmp($catal[$i]["cat"],'enrc',4) != 0) $title.="_".str_replace(' ', '_', strtolower($catal[$i]["artist"]));
	$title.="_-_";
	$title.=str_replace(' ', '_', strtolower($catal[$i]["album"]));
	
	$title = replacetitlechars($title);
	
	if ($catal[$i]["cat"] == "enrmp203") $title = "enrmp203_proyecto_de_prueba___void_null_-_proyecto_de_prueba___void_null_";
	
	print("<img src=\"http://enoughrecords.scene.org/covers/".$catal[$i]["cat"].".jpg\"/><br />");
	print($catal[$i]["cat"]." - ".$catal[$i]["artist"]." - ".$catal[$i]["album"]."<br />");
	print($catal[$i]["shortnfo"]."<br />");
	print("<a href=\"ftp://ftp.scene.org/pub/music/groups/enough_records/".$title.".zip\">download</a><br />");
	print("<br />");
endfor;
?>
<br /></p>
-->
<!-- blogger (demojournal, bpf) -->
<!--
<p>Some new releases are available for download:
<? 
for ($i = 0; $i < count($catal); $i++):
	$title = $catal[$i]["cat"];
	if (strncmp($catal[$i]["cat"],'enrc',4) != 0) $title.="_".str_replace(' ', '_', strtolower($catal[$i]["artist"]));
	$title.="_-_";
	$title.=str_replace(' ', '_', strtolower($catal[$i]["album"]));
	
	$title = replacetitlechars($title);

	if ($catal[$i]["cat"] == "enrmp203") $title = "enrmp203_proyecto_de_prueba___void_null_-_proyecto_de_prueba___void_null_";
	
	print("<img src=\"http://enoughrecords.scene.org/covers/".$catal[$i]["cat"].".jpg\"/>\n");
	print($catal[$i]["cat"]." - ".$catal[$i]["artist"]." - ".$catal[$i]["album"]."\n");
	print($catal[$i]["shortnfo"]."\n");
	print("<a href=\"ftp://ftp.scene.org/pub/music/groups/enough_records/".$title.".zip\">download</a>\n");
	print("\n\n");
endfor;
?>
</p>
-->
<!-- bbcode (bpf forum, forumsons, versus, blackplanet) -->
<!--
Some new releases are available for download:
<? 
for ($i = 0; $i < count($catal); $i++):
	$title = $catal[$i]["cat"];
	if (strncmp($catal[$i]["cat"],'enrc',4) != 0) $title.="_".str_replace(' ', '_', strtolower($catal[$i]["artist"]));
	$title.="_-_";
	$title.=str_replace(' ', '_', strtolower($catal[$i]["album"]));
	
	$title = replacetitlechars($title);

	if ($catal[$i]["cat"] == "enrmp203") $title = "enrmp203_proyecto_de_prueba___void_null_-_proyecto_de_prueba___void_null_";
	
	print("[img]http://enoughrecords.scene.org/covers/".$catal[$i]["cat"].".jpg[/img]\n");
	print($catal[$i]["cat"]." - ".$catal[$i]["artist"]." - ".$catal[$i]["album"]."\n");
	$short = str_replace('<a href="', '[url=', $catal[$i]["shortnfo"]);
	//$short = str_replace('<a hreF="', '[url=', $catal[$i]["shortnfo"]);
	$short = str_replace('">', ']', $short);
	$short = str_replace('</a>', '[/url]', $short);
	print($short."\n");
	print("[url=ftp://ftp.scene.org/pub/music/groups/enough_records/".$title.".zip]download[/url]\n");
	print("\n\n");
endfor;
?>
-->
<!-- lastfm -->

Some new releases are available for download:
<pre>
<? 
for ($i = 0; $i < count($catal); $i++):
	$title = $catal[$i]["cat"];
	if (strncmp($catal[$i]["cat"],'enrc',4) != 0) $title.="_".str_replace(' ', '_', strtolower($catal[$i]["artist"]));
	$title.="_-_";
	$title.=str_replace(' ', '_', strtolower($catal[$i]["album"]));
	$title = replacetitlechars($title);
	print("[img]http://enoughrecords.scene.org/covers/".$catal[$i]["cat"].".jpg[/img]\n");
	print($catal[$i]["cat"]." - [artist]".$catal[$i]["artist"]."[/artist] - [album artist=".$catal[$i]["artist"]."]".$catal[$i]["album"]."[/album]\n\n");
	$short = str_replace('<a href="', '[url=', $catal[$i]["shortnfo"]);
	$short = str_replace('">', ']', $short);
	$short = str_replace('</a>', '[/url]', $short);
	print($short."\n\n");
	print("[url=http://archive.scene.org/pub/music/groups/enough_records/".$title.".zip]download link[/url]\n");
	print("\n\n");
endfor;
?>
</pre>

</body>
</html>
