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

include_once( realpath( dirname( __FILE__ ) ).'/_lib/config.php' );
include_once( realpath( dirname( __FILE__ ) ).'/_lib/functions.php' );

class SlimStatRecord {
	var $config;
	
	function SlimStatRecord() {
		$this->config =& SlimStatConfig::get_instance();
		
		if ( !$this->config->enabled ) {
			return;
		}
		
		// ignore hits from users who are logged in to slimstat
		if ( isset( $_COOKIE['slimstatuser'] ) &&
		     $_COOKIE['slimstatuser'] == SlimStat::build_cookie( $this->config->slimstat_username, $this->config->slimstat_password ) ) {
			return;
		}
		
		$data = array();
		$data['remote_ip'] = mb_substr( $this->_determine_remote_ip(), 0, 15 );
		// check whether to ignore this hit
		foreach ( $this->config->ignored_ips as $ip ) {
			if ( mb_strpos( $data['remote_ip'], $ip ) === 0 ) {
				return;
			}
		}
		
		$data['referrer'] = ( isset( $_SERVER['HTTP_REFERER'] ) ) ? $_SERVER['HTTP_REFERER'] : '';
		$url = parse_url( $data['referrer'] );
		$data['referrer'] = mb_substr( SlimStat::utf8_encode( $data['referrer'] ), 0, 255 );
		
		$data['country']  = $this->_determine_country( $data['remote_ip'] ); // always 2 chars, no need to truncate
		$data['language'] = mb_substr( SlimStat::determine_language(), 0, 255 );
		$data['domain']   = ( isset( $url['host'] ) ) ? mb_eregi_replace( '^www.', '', $url['host'] ) : '';
		$data['domain']   = mb_substr( $data['domain'], 0, 255 );
		
		$data['search_terms'] = mb_substr( SlimStat::utf8_encode( $this->_determine_search_terms( $url ) ), 0, 255 );
		
		$data['resolution'] = '';
		if ( array_key_exists( 'slimstat_resolution', $GLOBALS ) ) {
			$data['resolution'] = mb_substr( $GLOBALS['slimstat_resolution'], 0, 10 );
		}
		$data['title'] = '';
		if ( array_key_exists( 'slimstat_title', $GLOBALS ) ) {
			$data['title'] = mb_substr( SlimStat::utf8_encode( $GLOBALS['slimstat_title'] ), 0, 255 );
		}
		
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$data['resource'] = $_SERVER['REQUEST_URI'];
		} elseif ( isset( $_SERVER['SCRIPT_NAME'] ) && isset( $_SERVER['QUERY_STRING'] ) ) {
			$data['resource'] = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];
		} elseif ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
			$data['resource'] = $_SERVER['SCRIPT_NAME'];
		} elseif ( isset( $_SERVER['PHP_SELF'] ) && isset( $_SERVER['QUERY_STRING'] ) ) {
			$data['resource'] = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		} elseif ( isset( $_SERVER['PHP_SELF'] ) ) {
			$data['resource'] = $_SERVER['PHP_SELF'];
		} else {
			$data['resource'] = '';
		}
		$data['resource'] = mb_substr( SlimStat::utf8_encode( $data['resource'] ), 0, 255 );
		
		$browser = $this->_parse_user_agent( $_SERVER['HTTP_USER_AGENT'] );
		$data['platform'] = mb_substr( $browser['platform'], 0, 50 );
		$data['browser']  = mb_substr( $browser['browser'], 0, 50 );
		$data['version']  = mb_substr( SlimStat::parse_version( $browser['version'] ), 0, 15 );
		
		// check whether to ignore this hit
		if ( $data['browser'] == 'Crawler' && $this->config->log_crawlers == false ) {
			return;
		}
		
		$ts = time();
		$time = array(
			'mi' => floor( gmdate( 'i', $ts ) / 15 ) * 15,
			'hr' => gmdate( 'H', $ts ),
			'dy' => gmdate( 'j', $ts ),
			'mo' => gmdate( 'n', $ts ),
			'yr' => gmdate( 'Y', $ts )
		);
		
		$connection = SlimStat::connect();
		if ( !$connection ) {
			return;
		}
		
		// attempt to detect spam
		
		foreach ( $this->config->spam_words as $spam_word ) {
			if ( stristr( $data['referrer'], $spam_word ) ) {
				return;
			}
		}
		
		$domain_array = explode( '-', $data['domain'] );
		if ( sizeof( $domain_array ) > 2 ) {
			return;
		}
		
		if ( mb_strlen( $data['domain'] ) >= 25 &&
		     ( !isset( $_SERVER['SERVER_NAME'] ) ||
		       $data['domain'] != mb_eregi_replace( '^www.', '', $_SERVER['SERVER_NAME'] ) ) ) {
			return;
		}
		
		// record this hit
		
		// attempt to update hit table
		// if mysql_affected_rows() == 0, do insert
		
		$query = 'UPDATE `'.SlimStat::esc( $this->config->db_database ).'`.`'.SlimStat::esc( $this->config->tbl_hits ).'` ';
		$query .= ' SET `hits` = `hits` + 1 ';
		$prefix = 'WHERE ';
		foreach ( $data as $key => $value ) {
			$query .= $prefix.'`'.SlimStat::esc( $key ).'`';
			if ( $value == null || $value == '' ) {
				$query .= ' IS NULL ';
			} else {
				$query .= '=\''.SlimStat::esc( $value ).'\' ';
			}
			$prefix = 'AND ';
		}
		foreach ( $time as $key => $value ) {
			$query .= $prefix.'`'.SlimStat::esc( $key ).'`=\''.SlimStat::esc( $value ).'\' ';
		}
		$query .= 'LIMIT 1';
		// error_log( $query );
		@mysql_query( $query, $connection );
		// error_log( mysql_error( $connection ) );
		
		if ( @mysql_affected_rows( $connection ) == 0 ) {
			$query = 'INSERT INTO `'.SlimStat::esc( $this->config->db_database ).'`.`'.SlimStat::esc( $this->config->tbl_hits ).'` ( `';
			$query .= implode( '`, `', array_keys( $data ) ).'`, `'.implode( '`, `', array_keys( $time ) ).'`, `hits` ) VALUES ( ';
			foreach ( array_values( $data ) as $value ) {
				if ( $value == null || $value == '' ) {
					$query .= 'NULL, ';
				} else {
					$query .= '\''.SlimStat::esc( $value ).'\', ';
				}
			}
			foreach ( array_values( $time ) as $value ) {
				$query .= '\''.SlimStat::esc( $value ).'\', ';
			}
			$query .= '\'1\' )';
			// error_log( $query );
			@mysql_query( $query, $connection );
			// error_log( mysql_error( $connection ) );
		}
		
		// attempt to update visit table
		// if mysql_affected_rows() == 0, and referrer == this domain, look on /24 subnet 
		// if mysql_affected_rows() == 0, do insert
		
		if ( $this->config->log_user_agents == true ) {
			$data['user_agent'] = SlimStat::esc( mb_substr( $_SERVER['HTTP_USER_AGENT'], 0, 255 ) );
		}
		
		$prev_ts = $ts - ( $this->config->visit_length * 60 );
		$prev_time = array(
			'mi' => floor( gmdate( 'i', $prev_ts ) / 15 ) * 15,
			'hr' => gmdate( 'H', $prev_ts ),
			'dy' => gmdate( 'j', $prev_ts ),
			'mo' => gmdate( 'n', $prev_ts ),
			'yr' => gmdate( 'Y', $prev_ts )
		);
		
		$query = 'UPDATE `'.SlimStat::esc( $this->config->db_database ).'`.`'.SlimStat::esc( $this->config->tbl_visits ).'`';
		$query .= ' SET `hits` = `hits` + 1, `resource` = CONCAT( `resource`, \''.$time['yr'].' '.$time['mo'].' '.$time['dy'].' '.$time['hr'].' '.gmdate( 'i' ).' '.gmdate( 's' ).' '.$data['resource'].' '.$data['title'].'\n\' )';
		$query .= ', `end_resource`=\''.SlimStat::esc( $data['resource'] ).'\', `end_ts`=\''.SlimStat::esc( $ts ).'\'';
		$query .= ', `duration`=('.SlimStat::esc( $ts ).' - `start_ts`)';
		foreach ( $time as $key => $value ) {
			$query .= ', `end_'.$key.'`=\''.SlimStat::esc( $value ).'\'';
		}
		$query .= ' WHERE `remote_ip`=\''.SlimStat::esc( $data['remote_ip'] ).'\'';
		if ( $this->config->log_user_agents == true ) {
			$query .= ' AND `user_agent`=\''.SlimStat::esc( $data['user_agent'] ).'\'';
		} else {
			foreach ( array( 'browser', 'version', 'platform' ) as $key ) {
				if ( $data[$key] == null || $data[$key] == '' ) {
					$query .= ' AND `'.$key.'` IS NULL';
				} else {
					$query .= ' AND `'.$key.'`=\''.SlimStat::esc( $data[$key] ).'\'';
				}
			}
		}
		$query .= ' AND ( `end_yr`>\''.$prev_time['yr'].'\' OR ( `end_yr`=\''.$prev_time['yr'].'\' AND (';
		$query .= ' `end_mo`>\''.$prev_time['mo'].'\' OR ( `end_mo`=\''.$prev_time['mo'].'\' AND (';
		$query .= ' `end_dy`>\''.$prev_time['dy'].'\' OR ( `end_dy`=\''.$prev_time['dy'].'\' AND (';
		$query .= ' `end_hr`>\''.$prev_time['hr'].'\' OR ( `end_hr`=\''.$prev_time['hr'].'\' AND';
		$query .= ' `end_mi`>=\''.$prev_time['mi'].'\' ) ) ) ) ) ) ) )';
		$query .= ' LIMIT 1';
		
		// error_log( $query );
		@mysql_query( $query, $connection );
		// error_log( mysql_error( $connection ) );
		
		if ( @mysql_affected_rows( $connection ) == 0 && $data['domain'] == mb_eregi_replace( '^www.', '', $_SERVER['SERVER_NAME'] ) ) {
			list( $a, $b, $c, $d ) = explode( '.', $data['remote_ip'], 4 );
			$subnet_ip = $a.'.'.$b.'.'.$c.'.';
			
			$query = 'UPDATE `'.SlimStat::esc( $this->config->db_database ).'`.`'.SlimStat::esc( $this->config->tbl_visits ).'`';
			$query .= ' SET `hits` = `hits` + 1, `resource` = CONCAT( `resource`, \''.$time['yr'].' '.$time['mo'].' '.$time['dy'].' '.$time['hr'].' '.gmdate( 'i' ).' '.gmdate( 's' ).' '.$data['resource'].' '.$data['title'].'\n\' )';
			$query .= ', `end_resource`=\''.SlimStat::esc( $data['resource'] ).'\', `end_ts`=\''.SlimStat::esc( $ts ).'\'';
			$query .= ', `duration`=('.SlimStat::esc( $ts ).' - `start_ts`)';
			foreach ( $time as $key => $value ) {
				$query .= ', `end_'.$key.'`=\''.SlimStat::esc( $value ).'\'';
			}
			$query .= ' WHERE `remote_ip` LIKE \''.SlimStat::esc( $subnet_ip ).'%\'';
			if ( $this->config->log_user_agents == true ) {
				$query .= ' AND `user_agent`=\''.SlimStat::esc( $data['user_agent'] ).'\'';
			} else {
				foreach ( array( 'browser', 'version', 'platform' ) as $key ) {
					if ( $data[$key] == null || $data[$key] == '' ) {
						$query .= ' AND `'.$key.'` IS NULL';
					} else {
						$query .= ' AND `'.$key.'`=\''.SlimStat::esc( $data[$key] ).'\'';
					}
				}
			}
			$query .= ' AND ( `end_yr`>\''.$prev_time['yr'].'\' OR ( `end_yr`=\''.$prev_time['yr'].'\' AND (';
			$query .= ' `end_mo`>\''.$prev_time['mo'].'\' OR ( `end_mo`=\''.$prev_time['mo'].'\' AND (';
			$query .= ' `end_dy`>\''.$prev_time['dy'].'\' OR ( `end_dy`=\''.$prev_time['dy'].'\' AND (';
			$query .= ' `end_hr`>\''.$prev_time['hr'].'\' OR ( `end_hr`=\''.$prev_time['hr'].'\' AND';
			$query .= ' `end_mi`>=\''.$prev_time['mi'].'\' ) ) ) ) ) ) ) )';
			$query .= ' LIMIT 1';
		
			// error_log( $query );
			@mysql_query( $query, $connection );
			// error_log( mysql_error( $connection ) );
		}
		
		if ( @mysql_affected_rows( $connection ) == 0 ) {
			$query = 'INSERT INTO `'.SlimStat::esc( $this->config->db_database ).'`.`'.SlimStat::esc( $this->config->tbl_visits ).'` ( ';
			foreach ( array_keys( $data ) as $key ) {
				if ( $key == 'title' ) {
					// do nothing
				} elseif ( $key == 'resource' ) {
					$query .= '`start_resource`, `end_resource`, ';
				} else {
					$query .= '`'.$key.'`, ';
				}
			}
			foreach ( array_keys( $time ) as $key ) {
				$query .= '`start_'.$key.'`, `end_'.$key.'`, ';
			}
			$query .= '`hits`, `resource`, `start_ts`, `end_ts` ) VALUES ( ';
			foreach ( $data as $key => $value ) {
				if ( $key == 'title' ) {
					// do nothing
				} elseif ( $key == 'resource' ) {
					$query .= '\''.SlimStat::esc( $value ).'\', \''.SlimStat::esc( $value ).'\', ';
				} elseif ( $value == null || $value == '' ) {
					$query .= 'NULL, ';
				} else {
					$query .= '\''.SlimStat::esc( $value ).'\', ';
				}
			}
			foreach ( array_values( $time ) as $value ) {
				$query .= '\''.SlimStat::esc( $value ).'\', \''.SlimStat::esc( $value ).'\', ';
			}
			$query .= '\'1\', \''.$time['yr'].' '.$time['mo'].' '.$time['dy'].' '.$time['hr'].' '.gmdate( 'i' ).' '.gmdate( 's' ).' '.$data['resource'].' '.$data['title'].'\n\', \''.SlimStat::esc( $ts ).'\', \''.SlimStat::esc( $ts ).'\' )';
			// error_log( $query );
			@mysql_query( $query, $connection );
			// error_log( mysql_error( $connection ) );
		}
		
	}
	
	/**
	 * Determines the visitor’s IP address.
	 */
	function _determine_remote_ip() {
		$remote_addr = $_SERVER['REMOTE_ADDR'];
		if ( ( $remote_addr == '127.0.0.1' || $remote_addr == '::1' || $remote_addr == $_SERVER['SERVER_ADDR'] ) &&
		     array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {
			// There may be multiple comma-separated IPs for the X-Forwarded-For header
			// if the traffic is passing through more than one explicit proxy. Take the
			// last one as being valid. This is arbitrary, but there is no way to know
			// which IP relates to the client computer. We pick the first client IP as
			// this is the client closest to our upstream proxy.
			$remote_addrs = explode( ', ', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$remote_addr = $remote_addrs[0];
		}
		
		if ( $this->config->anonymise_ip_mask != '' && $this->config->anonymise_ip_mask != '255.255.255.255' ) {
			$remote_addr = SlimStat::anonymise_ip( $remote_addr, $this->config->anonymise_ip_mask );
		}
		
		return $remote_addr;
	}
	
	/**
	 * Determines the visitor’s country based on their IP address.
	 */
	function _determine_country( $_ip ) {
		if ( SlimStat::is_geoip_installed() ) {
			include_once( realpath( dirname( __FILE__ ) ).'/_lib/geoip.php' );
			$gi = geoip_open( realpath( dirname( __FILE__ ) ).'/_lib/GeoIP.dat', GEOIP_STANDARD );
			return geoip_country_code_by_addr( $gi, $_ip );
			geoip_close( $gi );
		} else {
			return '';
		}
	}
	
	/**
	 * Detects referrals from search engines and tries to determine the search terms.
	 */
	function _determine_search_terms( $_url ) {
		if ( !is_array( $_url ) ) {
			$_url = parse_url( $_url );
		}
		
		$search_terms = '';
		
		if ( isset( $_url['host'] ) && isset( $_url['query'] ) ) {
			$sniffs = array( // host regexp, query portion containing search terms, parameterised url to decode
				array( "/images\.google\./i", 'q', 'prev' ),
				array( "/google\./i", 'q' ),
				array( "/\.bing\./i", 'q' ),
				array( "/alltheweb\./i", 'q' ),
				array( "/yahoo\./i", 'p' ),
				array( "/search\.aol\./i", 'query' ),
				array( "/search\.cs\./i", 'query' ),
				array( "/search\.netscape\./i", 'query' ),
				array( "/hotbot\./i", 'query' ),
				array( "/search\.msn\./i", 'q' ),
				array( "/altavista\./i", 'q' ),
				array( "/web\.ask\./i", 'q' ),
				array( "/search\.wanadoo\./i", 'q' ),
				array( "/www\.bbc\./i", 'q' ),
				array( "/tesco\.net/i", 'q' ),
				array( "/yandex\./i", 'text' ),
				array( "/rambler\./i", 'words' ),
				array( "/aport\./i", 'r' ),
				array( "/.*/", 'query' ),
				array( "/.*/", 'q' )
			);
			
			foreach ( $sniffs as $sniff ) {
				if ( preg_match( $sniff[0], $_url['host'] ) ) {
					parse_str( $_url['query'], $q );
					
					if ( isset( $sniff[2] ) && array_key_exists( $sniff[2], $q ) ) {
						$decoded_url = parse_url( $q[ $sniff[2] ] );
						if ( array_key_exists( 'query', $decoded_url ) ) {
							parse_str( $decoded_url['query'], $q );
						}
					}
					
					if ( isset( $q[ $sniff[1] ] ) ) {
						$search_terms = trim( stripslashes( $q[ $sniff[1] ] ) );
						break;
					}
				}
			}
		}
		
		return $search_terms;
	}
	
	/**
	 * Attempts to identify the browser info from its user agent string.
	 */
	function _parse_user_agent( $_ua ) {
		$browser = array(
			'platform' => '',
			'browser'  => '',
			'version'  => ''
		);
		
		// platform
		if ( preg_match( '/Win/', $_ua ) ) {
			$browser['platform'] = 'Windows';
		} elseif ( preg_match( '/iPod/', $_ua ) ) {
			$browser['platform'] = 'iPod';
		} elseif ( preg_match( '/iPad/', $_ua ) ) {
			$browser['platform'] = 'iPad';
		} elseif ( preg_match( '/iPhone/', $_ua ) ) {
			$browser['platform'] = 'iPhone';
		} elseif ( preg_match( '/Android/', $_ua ) ) {
			$browser['platform'] = 'Android';
		} elseif ( preg_match( '/Symbian/', $_ua ) || preg_match( '/SymbOS/', $_ua ) ) {
			$browser['platform'] = 'Symbian';
		} elseif ( preg_match( '/Nintendo Wii/', $_ua ) ) {
			$browser['platform'] = 'Nintendo Wii';
		} elseif ( preg_match( '/PlayStation Portable/', $_ua ) ) {
			$browser['platform'] = 'PlayStation Portable';
		} elseif ( preg_match( '/Mac/', $_ua ) ) {
			$browser['platform'] = 'Macintosh';
		} elseif ( preg_match( '/Linux/', $_ua ) ) {
			$browser['platform'] = 'Linux';
		} elseif ( preg_match( '/FreeBSD/', $_ua ) ) {
			$browser['platform'] = 'FreeBSD';
		} elseif ( preg_match( '/DoCoMo/', $_ua ) ) {
			$browser['platform'] = 'i-mode';
		}
		
		// browser type
		if ( mb_eregi( 'charlotte', $_ua ) ||
		     mb_eregi( 'crawl', $_ua ) ||
		     mb_eregi( 'bot', $_ua ) ||
		     mb_eregi( 'bloglines', $_ua ) ||
		     mb_eregi( 'dtaagent', $_ua ) ||
		     mb_eregi( 'feedfetcher', $_ua ) ||
		     mb_eregi( 'ia_archiver', $_ua ) ||
		     mb_eregi( 'java', $_ua ) ||
		     mb_eregi( 'larbin', $_ua ) ||
		     mb_eregi( 'mediapartners', $_ua ) ||
		     mb_eregi( 'metaspinner', $_ua ) ||
		     mb_eregi( 'mobile goo', $_ua ) || // http://help.goo.ne.jp/help/article/1142/
		     mb_eregi( 'searchmonkey', $_ua ) ||
		     mb_eregi( 'slurp', $_ua ) ||
		     mb_eregi( 'spider', $_ua ) ||
		     mb_eregi( 'teoma', $_ua ) ||
		     mb_eregi( 'ultraseek', $_ua ) ||
		     mb_eregi( 'waypath', $_ua ) ||
		     mb_eregi( 'yacy', $_ua ) ||
		     mb_eregi( 'yandex', $_ua ) ) {
			$browser['browser'] = 'Crawler';
		} else {
			$sniffs = array( // name regexp, name for display, version regexp, version match, platform (optional)
				array( 'Opera Mini', 'Opera Mini', "Opera Mini( |/)([[:digit:]\.]+)", 2 ),
				array( 'Opera', 'Opera', "Version/([[:digit:]\.]+)", 1 ),
				array( 'Opera', 'Opera', "Opera( |/)([[:digit:]\.]+)", 2 ),
				array( 'MSIE', 'Internet Explorer', "MSIE ([[:digit:]\.]+)", 1 ),
				array( 'Epiphany', 'Epiphany', "Epiphany/([[:digit:]\.]+)",  1 ),
				array( 'Fennec', 'Fennec', "Fennec/([[:digit:]\.]+)",  1 ),
				array( 'Firefox', 'Firefox', "Firefox/([[:digit:]\.]+)",  1 ),
				array( 'Iceweasel', 'Iceweasel', "Iceweasel/([[:digit:]\.]+)",  1 ),
				array( 'Minefield', 'Minefield', "Minefield/([[:digit:]\.]+)",  1 ),
				array( 'Minimo', 'Minimo', "Minimo/([[:digit:]\.]+)",  1 ),
				array( 'Flock', 'Flock', "Flock/([[:digit:]\.]+)",  1 ),
				array( 'Firebird', 'Firebird', "Firebird/([[:digit:]\.]+)", 1 ),
				array( 'Phoenix', 'Phoenix', "Phoenix/([[:digit:]\.]+)", 1 ),
				array( 'Camino', 'Camino', "Camino/([[:digit:]\.]+)", 1 ),
				array( 'Flock', 'Flock', "Flock/([[:digit:]\.]+)",  1 ),
				array( 'Chimera', 'Chimera', "Chimera/([[:digit:]\.]+)", 1 ),
				array( 'Thunderbird', 'Thunderbird', "Thunderbird/([[:digit:]\.]+)",  1 ),
				array( 'Netscape', 'Netscape', "Netscape[0-9]?/([[:digit:]\.]+)", 1 ),
				array( 'OmniWeb', 'OmniWeb', "OmniWeb/([[:digit:]\.]+)", 1 ),
				array( 'Iron', 'Iron', "Iron/([[:digit:]\.]+)", 1 ),
				array( 'Chrome', 'Chrome', "Chrome/([[:digit:]\.]+)", 1 ),
				array( 'Chromium', 'Chromium', "Chromium/([[:digit:]\.]+)", 1 ),
				array( 'Safari', 'Safari', "Version/([[:digit:]\.]+)", 1 ),
				array( 'Safari', 'Safari', "Safari/([[:digit:]\.]+)", 1 ),
				array( 'iCab', 'iCab', "iCab/([[:digit:]\.]+)", 1 ),
				array( 'Konqueror', 'Konqueror', "Konqueror/([[:digit:]\.]+)", 1, 'Linux' ),
				array( 'Midori', 'Midori', "Midori/([[:digit:]\.]+)",  1 ),
				array( 'DoCoMo', 'DoCoMo', "DoCoMo/([[:digit:]\.]+)", 1 ),
				array( 'Lynx', 'Lynx', "Lynx/([[:digit:]\.]+)", 1 ),
				array( 'Links', 'Links', "\(([[:digit:]\.]+)", 1 ),
				array( 'W3C_Validator', 'W3C Validator', "W3C_Validator/([[:digit:]\.]+)", 1 ),
				array( 'ApacheBench', 'Apache Bench tool (ab)', "ApacheBench/(.*)$", 1 ),
				array( 'lwp-request', 'libwww Perl library', "lwp-request/(.*)$", 1 ),
				array( 'w3m', 'w3m', "w3m/([[:digit:]\.]+)", 1 ),
				array( 'Wget', 'Wget', "Wget/([[:digit:]\.]+)", 1 )
			);
			
			foreach ( $sniffs as $sniff ) {
				if ( mb_strpos( $_ua, $sniff[0] ) !== false ) {
					$browser['browser'] = $sniff[1];
					mb_ereg( $sniff[2], $_ua, $b );
					if ( sizeof( $b ) > $sniff[3] ) {
						$browser['version'] = $b[ $sniff[3] ];
						if ( sizeof( $sniff ) == 5 ) {
							$browser['platform'] = $sniff[4];
						}
						break;
					}
				}
			}
		}
		
		if ( $browser['browser'] == '' ) {
			if ( mb_ereg( 'Mozilla/4', $_ua ) && !mb_eregi( 'compatible', $_ua ) ) {
				$browser['browser'] = 'Netscape';
				mb_eregi( "Mozilla/([[:digit:]\.]+)", $_ua, $b );
				$browser['version'] = $b[1];
			} elseif ( ( mb_ereg( 'Mozilla/5', $_ua ) && !mb_eregi( 'compatible', $_ua ) ) || mb_ereg( 'Gecko', $_ua ) ) {
				$browser['browser'] = 'Mozilla';
				mb_eregi( "rv(:| )([[:digit:]\.]+)", $_ua, $b );
				$browser['version'] = $b[2];
			}
		}
		
		// browser version
		if ( $browser['browser'] != '' && $browser['version'] != '' ) {
			// Make sure we have at least .0 for a minor version
			$browser['version'] = ( !mb_eregi( "\.", $browser['version'] ) ) ? $browser['version'].'.0' : $browser['version'];
			mb_eregi( "^([0-9]*).(.*)$", $browser['version'], $v );
			$browser['majorver'] = $v[1];
			$browser['minorver'] = $v[2];
		}
		if ( empty( $browser['version'] ) || $browser['version'] == '.0' ) {
			$browser['version'] = '';
			$browser['majorver'] = '';
			$browser['minorver'] = '';
		}
		
		return $browser;
	}
	
}

new SlimStatRecord();
