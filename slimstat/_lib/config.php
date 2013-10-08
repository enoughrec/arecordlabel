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

if ( get_magic_quotes_gpc() ) {
	foreach ( array_keys( $_GET ) as $key ) {
		$_GET[$key] = stripslashes( $_GET[$key] );
	}
	foreach ( array_keys( $_POST ) as $key ) {
		$_POST[$key] = stripslashes( $_POST[$key] );
	}
	foreach ( array_keys( $_COOKIE ) as $key ) {
		$_COOKIE[$key] = stripslashes( $_COOKIE[$key] );
	}
	$_REQUEST = array_merge( $_GET, $_POST );
}

class SlimStatConfig {
	/** Whether SlimStat is enabled */
	var $enabled = true;
	
	/** Database connection */
	var $db_server = 'localhost'; // Leave as localhost unless you know otherwise
	var $db_username = 'sql'; // The username used to access your database
	var $db_password = 'sql'; // The password used to access your database
	var $db_database = 'ps'; // The database containing SlimStat’s tables
	
	/** Database tables */
	var $tbl_hits = 'slimstat_hits'; // Hits table
	var $tbl_visits = 'slimstat_visits'; // Visits table
	var $tbl_cache = 'slimstat_cache'; // Cache table
	
	/** The full name of your site */
	var $sitename = '';
	
	/** Username/password required to login to SlimStat */
	var $slimstat_use_auth = false;
	var $slimstat_username = '';
	var $slimstat_password = '';
	
	/** Timezone */
	var $timezone = 'Europe/London';
	// var $timezone = 'America/New_York';
	
	/** Which language to use. Default is 'en-gb' */
	var $language = 'en-gb';
	
	/** Which URL to use for WHOIS lookups */
	var $whoisurl = 'http://whois.domaintools.com/%i';
	
	/** Don’t log hits from referring domains containing these words */
	var $spam_words = array(
		'roulette', 'gambl', 'vegas', 'poker', 'casino', 'blackjack', 'omaha',
		'stud', 'hold', 'slot', 'bet', 'pills', 'cialis', 'viagra', 'xanax',
		'watches', 'loans', 'phentermine', 'naked', 'cam', 'sex', 'nude',
		'loan', 'mortgage', 'financ', 'rates', 'debt', 'dollar', 'cash',
		'traffic', 'babes', 'valium' );
	
	var $time_fields = array(
		'yr' => 'Year',
		'mo' => 'Month',
		'dy' => 'Day',
		'hr' => 'Hour',
		'mi' => 'Minute' );
	
	var $hit_fields = array(
		'resource' => 'Pages',
		'country' => 'Countries',
		'language' => 'Languages',
		'browser' => 'Browsers',
		'version' => 'Versions',
		'platform' => 'Operating systems',
		'resolution' => 'Screen sizes' );
	
	var $visit_fields = array(
		'remote_ip' => 'Visitors',
		'search_terms' => 'Search terms',
		'domain' => 'Domains',
		'referrer' => 'Referrers',
		'start_resource' => 'Entry pages',
		'end_resource' => 'Exit pages',
		'hits' => 'Hits' );
	
	/** Don’t log hits from these IP ranges */
	var $ignored_ips = array();// '192.168.', '10.', '127.' );
	
	/** Whether to record user-agent strings in the database. The database
	will be smaller if this is disabled */
	var $log_user_agents = true;
	
	/** Whether to log hits from crawlers. The database will be smaller if
	this is disabled */
	var $log_crawlers = false;
	
	/** Maximum number of minutes between hits in a visit */
	var $visit_length = 30;
	
	function SlimStatConfig() {
		SlimStat::set_timezone( $this->timezone );
		
		if ( file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/_i18n/'.preg_replace( "[^A-Za-z\-]", '', $this->language ).'.php' ) ) {
			require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_i18n/'.preg_replace( "[^A-Za-z\-]", '', $this->language ).'.php' );
		} else { // fall back on en-gb
			$this->language = 'en-gb';
			require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_i18n/en-gb.php' );
		}
		
		if ( mb_strlen( $this->language ) == 5 ) {
			setlocale( LC_ALL, mb_substr( $this->language, 0, 2 ).'_'.mb_strtoupper( mb_substr( $this->language, 3, 2 ) ).'.utf8' );
		} else {
			setlocale( LC_ALL, mb_substr( $this->language, 0, 2 ).'_'.mb_strtoupper( mb_substr( $this->language, 0, 2 ) ).'.utf8' );
		}
	}
	
	function &get_instance() {
		static $instance = array();
		if ( empty( $instance ) ) {
			$instance[] =& new SlimStatConfig();
		}
		return $instance[0];
	}
}