<?php

if ( isset( $_GET["ref"] ) && isset( $_GET["res"] ) ) {
	$_SERVER["HTTP_REFERER"] = $_GET["ref"];
	
	$url = @parse_url( $_GET["res"] );
	if ( isset( $url["path"] ) ) {
		if ( isset( $url["query"] ) ) {
			$_SERVER["REQUEST_URI"] = $url["path"]."?".$url["query"];
		} else {
			$_SERVER["REQUEST_URI"] = $url["path"];
		}
		@include_once( "http://enoughrecords.scene.org/slimstat/inc.stats.php" );
	}
}

header( "Content-Type: image/gif" );
readfile( "pixel.gif" );

?>
