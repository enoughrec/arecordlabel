<?php

/*
 * SlimStat: simple web analytics
 * Copyright (C) 2009 Pieces & Bits Limited
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
// set to E_ALL for debugging
error_reporting( E_ERROR );
require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/config.php' );
require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/i18n.php' );
require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/functions.php' );
if ( array_key_exists( 'page', $_GET ) ) {
	$query_string_page = preg_replace( "/[^A-Za-z]/", '', $_GET['page'] );
} elseif ( array_key_exists( 'QUERY_STRING', $_SERVER ) ) {
	$query_string_page = preg_replace( "/[^A-Za-z]/", '', $_SERVER['QUERY_STRING'] );
} else {
	$query_string_page = '';
}

if ( $query_string_page == 'js' ) {
	// no need to connect to database, so do this first
	require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/js.php' );
	exit;
}

if ( !file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/page/'.$query_string_page.'.php' ) ) {
	$query_string_page = '';
}
$config =& SlimStatConfig::get_instance();
$i18n = SlimStatI18n::get_instance();

$is_iphone = is_iphone();
if ( file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/page/setup.php' ) ) {
	$query_string_page = 'setup';
	@include_once( '/home/sceneorg/ps/public_html/enough/slimstat/page/setup.php' );
	exit;
}

if ( $config->slimstat_use_auth ) {
	session_start();
	if ( $query_string_page == 'logout' ) {
		logout();
	}
	if ( !( $query_string_page == 'login' ) && !check_login() ) {
		request_login();
		exit;
	}
}
$connection = SlimStat::connect();
ob_start( 'ob_gzhandler' );

if ( $query_string_page == '' || !file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/page/'.$query_string_page.'.php' ) ) {
	$query_string_page = 'details';
}
require_once( '/home/sceneorg/ps/public_html/enough/slimstat/page/'.$query_string_page.'.php' );

if ( function_exists( 'render_page' ) ) {
	render_page();
}

function page_head() {
	global $config, $filters, $query_string_page;
	
	include( '/home/sceneorg/ps/public_html/enough/slimstat/page/_head.php' );
}

function page_foot() {
	global $config;
	
	include( '/home/sceneorg/ps/public_html/enough/slimstat/page/_foot.php' );
}

function filter_url( $_filters, $_first_separator='?' ) {
	$shown_first = false;
	$str = '';
	$cleaned_filters = $_filters;
	
	if ( array_key_exists( 'yr', $_filters ) && $_filters['yr'] == date( 'Y' ) ) {
		unset( $cleaned_filters['yr'] );
		if ( array_key_exists( 'mo', $_filters ) && $_filters['mo'] == date( 'n' ) ) {
			unset( $cleaned_filters['mo'] );
		}
	}
	
	foreach ( $cleaned_filters as $key => $value ) {
		if ( $shown_first ) {
			$str .= '&amp;';
		} else {
			$str .= $_first_separator;
			$shown_first = true;
		}
		$str .= 'filter_'.urlencode( $key ).'='.urlencode( $value );
	}
	
	return $str;
}

function format_number( $_number, $_dp=1 ) {
	$i18n =& SlimStatI18n::get_instance();
	$str = number_format( $_number, $_dp, $i18n->decimal_point, $i18n->thousands_separator );
	if ( $str == '0'.$i18n->decimal_point.'0' && $_dp == 1 ) {
		$str2 = number_format( $_number, 2, $i18n->decimal_point, $i18n->thousands_separator );
		if ( $str2 != '0'.$i18n->decimal_point.'00' ) {
			return $str2;
		}
	}
	return $str;
}

/**
 * Detects whether a user is logged in
 */
function is_logged_in() {
	$config =& SlimStatConfig::get_instance();
	
	if ( $config->slimstat_use_auth ) {
		return ( isset( $_SESSION ) && array_key_exists( 'slimstatuser', $_SESSION ) && $_SESSION['slimstatuser'] == true );
	} else {
		return true;
	}
}

function check_login() {
	if ( is_logged_in() ) {
		set_login_cookie();
		return true;
	}
	
	$config =& SlimStatConfig::get_instance();
	
	if ( !$config->slimstat_use_auth ) {
		return true;
	}
	
	if ( isset( $_COOKIE['slimstatuser'] ) &&
	     $_COOKIE['slimstatuser'] == sha1( $config->slimstat_username.' '.$config->slimstat_password.' '.$_SERVER['REMOTE_ADDR'] ) ) {
		$_SESSION['slimstatuser'] = true;
		set_login_cookie();
	} elseif ( isset( $_SERVER['PHP_AUTH_USER'] ) && $_SERVER['PHP_AUTH_USER'] == $config->slimstat_username &&
	     isset( $_SERVER['PHP_AUTH_PW'] ) && $_SERVER['PHP_AUTH_PW'] == $config->slimstat_password ) {
		$_SESSION['slimstatuser'] = true;
		set_login_cookie();
	} elseif ( isset( $_POST['username'] ) && $_POST['username'] == $config->slimstat_username &&
	           isset( $_POST['password'] ) && $_POST['password'] == $config->slimstat_password ) {
		$_SESSION['slimstatuser'] = true;
		set_login_cookie();
	}
	
	return is_logged_in();
}

function request_login() {
	if ( array_key_exists( 'format', $_GET ) && $_GET['format'] == 'rss' ) {
		// send www-auth before http header to keep old IE versions happy
		header( 'WWW-Authenticate: Basic realm="SlimStat"' );
		header( 'Status: 401 Unauthorized' );
	} else {
		header( 'Status: 302 Moved Temporarily' );
		header( 'Location: '.dirname( $_SERVER['PHP_SELF'] ).'/?login' );
	}
	
	exit();
}

function set_login_cookie() {
	if ( !is_logged_in() ) {
		return;
	}
	
	$config =& SlimStatConfig::get_instance();
	
	if ( !$config->slimstat_use_auth ) {
		return;
	}
	
	$cookie = sha1( $config->slimstat_username.' '.$config->slimstat_password.' '.$_SERVER['REMOTE_ADDR'] );
	@setcookie( 'slimstatuser', $cookie, time() + 31536000, '/', '' );
}

function logout() {
	global $config;
	
	session_destroy();
	
	@setcookie( 'slimstatuser', '', time() + 31536000, '/', '' );
	
	request_login();
}

function sp2nb( $_str ) {
	return str_replace( ' ', '&nbsp;', $_str );
}

function is_iphone() {
	return strstr( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) || strstr( $_SERVER['HTTP_USER_AGENT'], 'MobileSafari' );
}
