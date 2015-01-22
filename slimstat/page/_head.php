<?php

/*
 * SlimStat: simple web analytics
 * Copyright (C) 2010 Pieces & Bits Limited
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

@header( 'Content-Type: text/html; charset=UTF-8' );

?><!DOCTYPE html>
<html lang="<?php echo $config->language; ?>">
<head>
	<title><?php
	if ( $config->sitename == '' ) {
		echo 'SlimStat';
	} else {
		echo $i18n->hsc( 'core', 'title', $config->sitename );
	}
	?></title>
	<?php
	if ( $GLOBALS['is_handheld'] ) {
		echo "\t".'<meta name="viewport" content="width=360" />'."\n";
		echo "\t".'<link rel="stylesheet" href="_css/handheld.css" type="text/css" />'."\n";
	} else {
		echo "\t".'<link rel="stylesheet" href="_css/screen.css" type="text/css" />'."\n";
		echo "\t".'<!--[if lt IE 8]><link rel="stylesheet" href="_css/ie.css" type="text/css" /><![endif]-->'."\n";
		echo "\t".'<script type="text/javascript" src="_js/jquery-1.4.2.min.js"></script>'."\n";
		echo "\t".'<script type="text/javascript" src="_js/jquery-hashchange-1.2.min.js"></script>'."\n";
		echo "\t".'<script type="text/javascript" src="_js/screen.js"></script>'."\n";
	}
	?>
	<link rel="alternate" href="?format=rss" title="" type="application/rss+xml" />
</head>
<body id="<?php echo $query_string_page; ?>page">
<div id="container">

<div id="head">
<h1><a href="./"><?php
if ( $config->sitename == '' ) {
	echo 'SlimStat';
} else {
	echo $i18n->hsc( 'core', 'title', $config->sitename );
}
?></a></h1>
</div>

<?php

echo '<ul id="menu">'."\n";

if ( $config->slimstat_use_auth && $query_string_page != 'setup' ) {
	if ( check_login() ) {
		echo '<li><a href="?logout">'.$i18n->hsc( 'login', 'logout' ).'</a></li>';
	} else {
		echo '<li class="selected"><a href="?login">'.$i18n->hsc( 'login', 'menu' ).'</a></li>';
	}
}

if ( $query_string_page == 'setup' ) {
	echo '<li class="selected"><a href="?setup">Setup</a></li>';
} elseif ( $query_string_page == 'welcome' ) {
	echo '<li class="selected"><a href="?welcome">'.$i18n->hsc( 'welcome', 'menu' ).'</a></li>';
} elseif ( $config->slimstat_use_auth && !check_login() ) {
	// do nothing
} elseif ( $page_dir_handle = opendir( realpath( dirname( __FILE__ ) ) ) ) {
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
				$filter_url = filter_url( $filters, '&amp;' );
				if ( $filter_url == '' ) {
					echo '<a href="?'.htmlspecialchars( $page_dir_file ).'">';
				} else {
					echo '<a href="?page='.htmlspecialchars( $page_dir_file ).$filter_url.'">';
				}
			}
			$title = $i18n->hsc( $page_dir_file, 'menu' );
			if ( $title == 'menu' ) {
				$title = $page_dir_file;
			}
			echo ucwords( $title ).'</a></li>';
		}
	}
	closedir( $page_dir_handle );
}

echo '</ul>'."\n";