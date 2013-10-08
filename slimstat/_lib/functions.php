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

require_once( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/config.php' );

class SlimStat {
	/**
	 * Database connection
	 */
	function connect() {
		$config =& SlimStatConfig::get_instance();
		
		return @mysql_connect( $config->db_server, $config->db_username, $config->db_password );
	}
	
	/** Application version */
	function app_version() {
		return '2.0';
	}
	
	/**
	 * Confirms the existence of the GeoIP database (http://www.maxmind.com/)
	 */
	function is_geoip_installed() {
		return ( file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/geoip.php' ) &&
		         file_exists( '/home/sceneorg/ps/public_html/enough/slimstat/_lib/GeoIP.dat' ) );
	}
	
	function set_timezone( $_tz ) {
		if ( function_exists( 'date_default_timezone_set' ) ) {
			date_default_timezone_set( $_tz );
		} else {
			putenv( 'TZ='.$_tz );
		}
	}
	
	function esc( $_str ) {
		if ( version_compare( phpversion(), '4.3.0', '>=' ) ) {
			return mysql_real_escape_string( $_str );
		} else {
			return mysql_escape_string( $_str );
		}
	}
	
	function parse_version( $_raw_version, $_parts=2 ) {
		$version_numbers = explode( '.', $_raw_version );
		$value = '';

		for ( $x=0; $x<$_parts; $x++ ) {
			if ( sizeof( $version_numbers ) > $x ) {
				if ( $value != '' ) {
					$value .= '.';
				}
				$value .= $version_numbers[$x];
			}
		}

		return $value;
	}

}
