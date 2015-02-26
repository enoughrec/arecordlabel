<?
 
 

function strtr_utf8($str, $from, $to) {
    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    return strtr($str, $mapping);
}


function replacetitlechars($title) {
	return strtr_utf8($title, "ÝÁáàäãâéèëêíìïîóòöõôúùüûý'[]:.,/()|#&>! ", "yaaaaaaeeeeiiiiooooouuuuy_______________");
	//return strtr($title, "ä", "a");
}


function scrappingTracks($remotedir) {
	$audiolist = array();
	$doc = new DOMDocument();
	@$doc->loadHTMLFile($remotedir);
	$links = $doc->getElementsByTagName('a');
	foreach ($links as $link) {
		$attrs = $link->attributes;
		$href = $attrs->getNamedItem('href')->nodeValue;
		$filetype = substr($href,-4);
		if (($filetype == ".mp3") || ($filetype == ".ogg")) {
			//print("<br>found a sound file: ".$href);
			$thisfile = $remotedir."/".$href;
			
			unset($foundbefore);
			$foundbefore = false;
			foreach ($audiolist as $al) {
				//print("<br>comparing: ".$al." with ".$thisfile);
				if (strcmp($al,$thisfile) == 0) $foundbefore = true;
			}
			
			if (!$foundbefore) {
				print("<br>listed a sound file: ".$thisfile);
				array_push($audiolist, $thisfile);
			}
		}
	}
	return $audiolist;
}

?>
