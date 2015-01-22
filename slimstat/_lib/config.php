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

require_once ('../auth.php');

class SlimStatConfig {
//	global $db;
	/** Whether SlimStat is enabled */
	var $enabled = true;
		
	/** Database tables */
	var $tbl_hits = 'slimstat_hits'; // Hits table
	var $tbl_visits = 'slimstat_visits'; // Visits table
	var $tbl_cache = 'slimstat_cache'; // Cache table
	
	/** The full name of your site */
	var $sitename = 'Enough Records';
	
	/** Username/password required to login to SlimStat */
	var $slimstat_use_auth = false;
	var $slimstat_username = '';
	var $slimstat_password = '';
	
	/** Timezone. Must be one of PHP's supported timezones.
	  * See http://php.net/manual/en/timezones.php for a list. */
	var $timezone = 'Europe/London';
	
	/** Which language to use, e.g. 'en-gb'.
	  * There must be a corresponding ini file in the _i18n directory.
	  * If left blank, SlimStat will use the browser’s language. */
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
	
	/** Don’t log hits from these IP ranges */
	var $ignored_ips = array();// '192.168.', '10.', '127.' );
	
	/** Anonymise IPs with a mask. 255.255.255.255 means no masking.
	0.0.0.0 will disable tracking IPs. **/
	var $anonymise_ip_mask = '255.255.255.0';
	
	/** Whether to record user-agent strings in the database.
	  * The database will be smaller if this is disabled */
	var $log_user_agents = true;
	
	/** Whether to log hits from crawlers.
	  * The database will be smaller if this is disabled */
	var $log_crawlers = false;
	
	/** Maximum number of minutes between hits in a visit */
	var $visit_length = 30;
	
	function SlimStatConfig() {
		//global $config;
		global $slimstat, $db;
		$slimstat->set_timezone( $this->timezone );
		
		$this->db_server = $db["host"];
		$this->db_username = $db["user"];
		$this->db_database = $db["database"];
		$this->db_password = $db["password"];
		
		// detect language if necessary
		
		if ( $this->language == '' ) {
			$language = $slimstat->determine_language();
			if ( strlen( $language ) == 5 ) {
				$this->language = $language;
			} else {
				if ( $handle = opendir( realpath( dirname( dirname( __FILE__ ) ) ).'/_i18n' ) ) {
					while ( false !== ( $file = readdir( $handle ) ) ) {
						if ( $file{0} != '.' && strstr( $file, '.' ) ) {
							list( $filename, $extn ) = explode( '.', $file, 2 );
							if ( $extn == 'ini' && strtolower( substr( $filename, 0, 2 ) ) == $language ) {
								$this->language = $filename;
								break;
							}
						}
					}
					closedir( $handle );
				}
			}
		}
		
		// check if i18n file exists
		
		if ( !file_exists( realpath( dirname( dirname( __FILE__ ) ) ).'/_i18n/'.preg_replace( "[^A-Za-z\-]", '', $this->language ).'.ini' ) ) {
			$this->language = 'en-gb';
		}
		
		require_once( realpath( dirname( __FILE__ ) ).'/i18n.php' );
		
		// set locale
		
		if ( mb_strlen( $this->language ) == 5 ) {
			$locale = mb_substr( $this->language, 0, 2 ).'_'.mb_strtoupper( mb_substr( $this->language, 3, 2 ) );
		} else {
			$locale = mb_substr( $this->language, 0, 2 ).'_'.mb_strtoupper( mb_substr( $this->language, 0, 2 ) );
		}
		
		if ( setlocale( LC_ALL, $locale.'.utf8' ) === false ) {
			setlocale( LC_ALL, $locale );
		}
	}
	
	/*var $instance;
	
	function &get_instance() {
		global $instance;
		
		$instance = array();
		if ( empty( $instance ) ) {
			// Assigning the return value of new by reference is deprecated in PHP 5.3
			//if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
				$instance[] = new SlimStatConfig();
			//} else {
			//	$instance[] =& new SlimStatConfig();
			//}
		}
		return $instance[0];
	}*/
}