<?php

@header( 'Content-Type: text/html; charset=UTF-8' );

?><!DOCTYPE html>
<html lang="en">
<head>
	<title>SlimStat<?php
	if ( $config->sitename != '' ) {
		echo ' for '.htmlspecialchars( $config->sitename );
	}
	?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="_css/screen.css" type="text/css" />
	<!--[if lt IE 8]><link rel="stylesheet" href="_css/ie.css" type="text/css" /><![endif]-->
	<link rel="alternate" href="?format=rss" title="" type="application/rss+xml" />
</head>
<body>
<div id="container">

<div id="head">
<h1><a href="./">SlimStat<?php
if ( $config->sitename != '' ) {
	echo ' for '.htmlspecialchars( $config->sitename );
}
?></a></h1>
</div>

<?php

echo '<ul id="menu">'."\n";

if ( $config->slimstat_use_auth && $query_string_page != 'setup' ) {
	if ( check_login() ) {
		echo '<li><a href="?logout">Log out</a></li>';
	} else {
		echo '<li class="selected"><a href="?login">Log in</a></li>';
	}
}

if ( $query_string_page == 'setup' ) {
	echo '<li class="selected"><a href="?setup">Setup</a></li>';
} elseif ( $query_string_page == 'welcome' ) {
	echo '<li class="selected"><a href="?welcome">Welcome</a></li>';
} elseif ( $page_dir_handle = opendir( '/home/sceneorg/ps/public_html/enough/slimstat/' ) ) {
	while ( false !== ( $page_dir_file = readdir( $page_dir_handle ) ) ) {
		if ( $page_dir_file{0} != '.' &&
		     !strstr( $page_dir_file, '_' ) &&
		     $page_dir_file != 'welcome.php' &&
		     $page_dir_file != 'setup.php' &&
		     $page_dir_file != 'login.php' ) {
			$page_dir_file = str_replace( '.php', '', $page_dir_file );
			if ( $query_string_page == $page_dir_file ) {
				echo '<li class="selected">';
			} else {
				echo '<li>';
			}
			
			if ( $page_dir_file == 'details' ) {
				echo '<a href="./'.filter_url( $filters ).'">';
			} else {
				echo '<a href="?page='.htmlspecialchars( $page_dir_file ).filter_url( $filters, '&amp;' ).'">';
			}
			echo ucwords( htmlspecialchars( $page_dir_file ) ).'</a></li>';
		}
	}
	closedir( $page_dir_handle );
}

echo '</ul>'."\n";