
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
	/*
	print('<pre>Debugging things : ');
	print_r($db);
	print("</pre>\n");
	*/
	die('SQL error... sorry ! ^^; I\'m on it !');
}


unset($submitok);
// check the submitted data
if($_POST["cat"] && $_POST["artist"] && $_POST["album"] && $_POST["code"])
{
  //echo "\n1";
  
  if($_POST["code"]!="enrc0cmeb") $errormessage[]="wrong code";
  
  if(!$_POST["artist"])
    $errormessage[]="no artist name ?!";
  
  if(!$_POST["album"])
    $errormessage[]="no album name ?!";
    
  if(!$_POST["shortnfo"])
    $errormessage[]="no short album info ?!";
    
  /*if(!$_POST["reviews"])
    $errormessage[]="no long album info ?!";
    
  if(!$_POST["new"])
    $errormessage[]="no number of copies ?!";*/
  
  // if everything is ok
  if(!$errormessage)
    $submitok=true;
}


// insert the submitted prod
if($submitok){
		
	  $query = "INSERT INTO enralbums SET ";
	  $query.= "cat='".$_POST["cat"]."', ";
	  $query.= "artist='".addslashes($_POST["artist"])."', ";
	  $query.= "album='".addslashes($_POST["album"])."', ";
	  $query.= "shortnfo='".addslashes($_POST["shortnfo"])."', ";
	  $query.= "reviews='', ";
	  $query.= "new='2', ";
	  $query.= "release_date='".$_POST["release_date"]."'";
	  mysqli_query($dbl, $query);
	  //print("->".$query."<-");
	  //$lastid=mysql_insert_id();
	  
	  if ($_POST["tag1"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag1"]."'");
	  if ($_POST["tag2"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag2"]."'");
	  if ($_POST["tag3"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag3"]."'");
	  if ($_POST["tag4"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag4"]."'");
	  if ($_POST["tag5"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag5"]."'");
	  if ($_POST["tag6"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag6"]."'");
	  if ($_POST["tag7"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag7"]."'");
	  if ($_POST["tag8"]) mysqli_query($dbl, "INSERT INTO enrtags SET cat='".$_POST["cat"]."', tag='".$_POST["tag8"]."'");

}

?>


<form action="insr.php" method="post" enctype="multipart/form-data">
<p class="text">
<? if($submitok): ?>
 has been added, <a href="edr.php?cat=<? print($_POST["cat"]); ?>">edit here</a><br />
<? endif; ?>
<? if($errormessage): ?>
 errors found<br />
 <? for($i=0;$i<count($errormessage);$i++): ?>
  <? if($i%2): ?>
   <b>- <font color="#FF8888"><? print($errormessage[$i]); ?></font></b><br />
  <? else: ?>
  <b>- <font color="#FF8888"><? print($errormessage[$i]); ?></font></b><br />
  <? endif; ?>
 <? endfor; ?>
 <br />
<? endif; ?>

  <table>
  	<tr>
	 <td>cat:</td>
	 <td><input type="text" name="cat" value="<? print($_POST["cat"]); ?>" size="12"><br ></td>
	</tr>
	<tr>
	 <td>artist name:</td>
	 <td><input type="text" name="artist" value="<? print($_POST["artist"]); ?>" size="40"><br ></td>
	</tr>
	<tr>
	 <td>album name:</td>
	 <td><input type="text" name="album" value="<? print($_POST["album"]); ?>" size="40"><br /></td>
	</tr>
	<tr>
	 <td>short album info:</td>
	 <td><TEXTAREA name="shortnfo" rows="10" cols="50"><? print($_POST["shortnfo"]); ?></TEXTAREA><br /></td>
	</tr>
	<tr>
	 <td>tags:</td>
	 <td>
		<input type="text" name="tag1" value="<? print($_POST["tag1"]); ?>" size="15">
		<input type="text" name="tag2" value="<? print($_POST["tag2"]); ?>" size="15">
		<input type="text" name="tag3" value="<? print($_POST["tag3"]); ?>" size="15">
		<input type="text" name="tag4" value="<? print($_POST["tag4"]); ?>" size="15">
		<input type="text" name="tag5" value="<? print($_POST["tag5"]); ?>" size="15">	
		<input type="text" name="tag6" value="<? print($_POST["tag6"]); ?>" size="15">
		<input type="text" name="tag7" value="<? print($_POST["tag7"]); ?>" size="15">
		<input type="text" name="tag8" value="<? print($_POST["tag8"]); ?>" size="15">
	 <br /></td>
	</tr>
	<tr>
	 <td>release date:</td>
	 <td><input type="text" name="release_date" value="<? print($_POST["release_date"]); ?>" size="20"><br /></td>
	</tr>
	<tr>
	 <td><br /></td>
	 <td>
	  YYYY-MM-DD format<br />
	 </td>
	</tr>
	<tr>
	 <td><input type="password" name="code" value="" size="5">
	 <input type="submit" value="submit">
  	 <td><br /></td>
 	</tr>
</table></p>
</form>

<br /><br />
</body>
</html>
