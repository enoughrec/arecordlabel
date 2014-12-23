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

?>
