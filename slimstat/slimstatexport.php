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

require_once( realpath( dirname( __FILE__ ) ).'/_lib/config.php' );
require_once( realpath( dirname( __FILE__ ) ).'/_lib/functions.php' );

header( 'Content-type: text/plain; charset=UTF-8' );

$config = SlimStatConfig::get_instance();

$connection = SlimStat::connect();

// get country names

echo 'Getting country names...'."\n";

$countries = array();
$countries_query = 'SELECT country_code2, LOWER(country_name) FROM `'.SlimStat::esc( $config->db_database ).'`.`slimstat_iptocountry` GROUP BY LOWER(country_name)';
$countries_result = mysql_query( $countries_query, $connection );
if ( mysql_error( $connection ) != '' ) {
	echo 'Error: '.mysql_error( $connection )."\n";
}
while ( list( $code, $name ) = mysql_fetch_row( $countries_result ) ) {
	$countries[$name] = $code;
}

// export old hit data

echo 'Updating version data...'."\n";

$select_old_query = 'SELECT DISTINCT `version` FROM `'.SlimStat::esc( $config->db_database ).'`.`slimstat`';
$select_old_result = mysql_query( $select_old_query, $connection );
while ( list( $version ) = mysql_fetch_row( $select_old_result ) ) {
	$parsed_version = SlimStat::parse_version( $version );
	// echo $version."\t".$parsed_version."\n";
	if ( $parsed_version != $version ) {
		$update_old_query = 'UPDATE `'.SlimStat::esc( $config->db_database ).'`.`slimstat` SET `version`=\''.SlimStat::esc( $parsed_version ).'\' WHERE version=\''.SlimStat::esc( $version ).'\'';
		mysql_query( $update_old_query );
	}
}

echo 'Getting hit data...'."\n";

$select_old_query = 'SELECT COUNT(id) AS hits, LOWER(country) AS country, LOWER(language) AS language, resource, platform, browser, version, ';
$select_old_query .= 'FLOOR(MINUTE(FROM_UNIXTIME(dt))/15)*15 AS mi, HOUR(FROM_UNIXTIME(dt)) AS hr, ';
$select_old_query .= 'DAYOFMONTH(FROM_UNIXTIME(dt)) AS dy, MONTH(FROM_UNIXTIME(dt)) AS mo, ';
$select_old_query .= 'YEAR(FROM_UNIXTIME(dt)) AS yr ';
$select_old_query .= 'FROM `'.SlimStat::esc( $config->db_database ).'`.`slimstat` GROUP BY 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2 ORDER BY yr, mo, dy, hr';

// echo $select_old_query."\n";

$select_old_result = mysql_query( $select_old_query, $connection );
if ( mysql_error( $connection ) != '' ) {
	echo 'Error: '.mysql_error( $connection )."\n";
}

echo 'Exporting hit data...'."\n";

while ( $row = mysql_fetch_assoc( $select_old_result ) ) {
	$insert_new_query = 'INSERT INTO `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_hits ).'` ( hits, country, language, resource, platform, browser, version, mi, hr, dy, mo, yr ) ';
	$insert_new_query .= 'VALUES ( '.SlimStat::esc( $row['hits'] ).', ';
	if ( !array_key_exists( $row['country'], $countries ) ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $countries[ $row['country'] ] ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $row['language'] == 'empty' || $row['language'] == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( mb_strtolower( $row['language'] ) ).'\'';
	}
	$insert_new_query .= ', \''.SlimStat::esc( $row['resource'] ).'\', ';
	if ( $row['platform'] == 'Unknown' || $row['platform'] == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $row['platform'] ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $row['browser'] == 'Unknown' || $row['browser'] == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $row['browser'] ).'\'';
	}
	$insert_new_query .= ', ';
	$row['version'] = SlimStat::parse_version( $row['version'] );
	if ( $row['version'] == 'Unknown' || $row['version'] == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $row['version'] ).'\'';
	}
	$insert_new_query .= ', \''.SlimStat::esc( $row['mi'] ).'\', \''.SlimStat::esc( $row['hr'] ).'\', \''.SlimStat::esc( $row['dy'] ).'\', \''.SlimStat::esc( $row['mo'] ).'\', \''.SlimStat::esc( $row['yr'] ).'\' )';
	mysql_query( $insert_new_query );
	// echo $insert_new_query."\n";
}

// export old visit data

echo 'Getting visit data...'."\n";

$select_old_query = 'SELECT visit FROM `'.SlimStat::esc( $config->db_database ).'`.`slimstat` GROUP BY visit ORDER BY dt';

$select_old_result = mysql_query( $select_old_query, $connection );
if ( mysql_error( $connection ) != '' ) {
	echo 'Error: '.mysql_error( $connection )."\n";
}

echo 'Exporting visit data...'."\n";

while ( list( $visit ) = mysql_fetch_row( $select_old_result ) ) {
	$select_visit_query = 'SELECT LOWER(country) AS country, remote_ip, LOWER(language) AS language, referer AS referrer, ';
	$select_visit_query .= 'searchterms AS search_terms, domain, resource, platform, browser, version, ';
	$select_visit_query .= 'SECOND(FROM_UNIXTIME(dt)) AS sc,  MINUTE(FROM_UNIXTIME(dt)) AS mi, HOUR(FROM_UNIXTIME(dt)) AS hr, ';
	$select_visit_query .= 'DAYOFMONTH(FROM_UNIXTIME(dt)) AS dy, MONTH(FROM_UNIXTIME(dt)) AS mo, YEAR(FROM_UNIXTIME(dt)) AS yr, ';
	$select_visit_query .= 'dt FROM `'.SlimStat::esc( $config->db_database ).'`.slimstat WHERE visit='.$visit.' ORDER BY dt';
	
	$select_visit_result = mysql_query( $select_visit_query, $connection );
	if ( mysql_error( $connection ) != '' ) {
		echo 'Error: '.mysql_error( $connection )."\n";
	}
	$country = '';
	$language = '';
	$platform = '';
	$browser = '';
	$version = '';
	$referrer = '';
	$domain = '';
	$remote_ip = '';
	$search_terms = '';
	$start_mi = -1;
	$start_hr = -1;
	$start_dy = -1;
	$start_mo = -1;
	$start_yr = -1;
	$end_mi = -1;
	$end_hr = -1;
	$end_dy = -1;
	$end_mo = -1;
	$end_yr = -1;
	$resource = '';
	$start_resource = '';
	$end_resource = '';
	$start_ts = 0;
	$end_ts = 0;
	$hits = 0;
	while ( $row = mysql_fetch_assoc( $select_visit_result ) ) {
		if ( $start_yr == -1 ) {
			$start_mi = floor( $row['mi'] / 15 ) * 15;
			$start_hr = $row['hr'];
			$start_dy = $row['dy'];
			$start_mo = $row['mo'];
			$start_yr = $row['yr'];
			$start_resource = $row['resource'];
			$referrer = $row['referrer'];
			$domain = $row['domain'];
			$remote_ip = $row['remote_ip'];
			$search_terms = $row['search_terms'];
			if ( array_key_exists( $row['country'], $countries ) ) {
				$country = $countries[ $row['country'] ];
			}
			$language = $row['language'];
			$platform = $row['platform'];
			$browser = $row['browser'];
			$version = SlimStat::parse_version( $row['version'] );
			$start_ts = $row['dt'];
		}
		
		$end_mi = floor( $row['mi'] / 15 ) * 15;
		$end_hr = $row['hr'];
		$end_dy = $row['dy'];
		$end_mo = $row['mo'];
		$end_yr = $row['yr'];
		$end_resource = $row['resource'];
		$end_ts = $row['dt'];
		$resource .= $row['yr'].' '.$row['mo'].' '.$row['dy'].' '.$row['hr'].' '.$row['mi'].' '.$row['sc'].' '.$row['resource']." \n";
		
		$hits++;
	}
	
	$insert_new_query = 'INSERT INTO `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_visits ).'` ';
	$insert_new_query .= '( hits, country, remote_ip, language, start_resource, end_resource, platform, ';
	$insert_new_query .= 'browser, version,  domain, referrer, search_terms, start_mi, start_hr, start_dy, start_mo, start_yr, ';
	$insert_new_query .= 'end_mi, end_hr, end_dy, end_mo, end_yr, start_ts, end_ts, duration, resource ) VALUES ( '.SlimStat::esc( $hits ).', ';
	if ( $country == 'Unknown' || $country == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $country ).'\'';
	}
	$insert_new_query .= ', \''.SlimStat::esc( $remote_ip ).'\', ';
	if ( $language == 'empty' || $language == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( mb_strtolower( $language ) ).'\'';
	}
	$insert_new_query .= ', \''.SlimStat::esc( $start_resource ).'\', \''.SlimStat::esc( $end_resource ).'\', ';
	if ( $platform == 'Unknown' || $platform == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $platform ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $browser == 'Unknown' || $browser == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $browser ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $version == 'Unknown' || $version == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $version ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $domain == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $domain ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $referrer == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $referrer ).'\'';
	}
	$insert_new_query .= ', ';
	if ( $search_terms == '' ) {
		$insert_new_query .= 'NULL';
	} else {
		$insert_new_query .= '\''.SlimStat::esc( $search_terms ).'\'';
	}
	$insert_new_query .= ', \''.SlimStat::esc( $start_mi ).'\', \''.SlimStat::esc( $start_hr ).'\', \''.SlimStat::esc( $start_dy ).'\', \''.SlimStat::esc( $start_mo ).'\', \''.SlimStat::esc( $start_yr ).'\' ';
	$insert_new_query .= ', \''.SlimStat::esc( $end_mi ).'\', \''.SlimStat::esc( $end_hr ).'\', \''.SlimStat::esc( $end_dy ).'\', \''.SlimStat::esc( $end_mo ).'\', \''.SlimStat::esc( $end_yr ).'\' ';
	$insert_new_query .= ', \''.SlimStat::esc( $start_ts ).'\', \''.SlimStat::esc( $end_ts ).'\', \''.SlimStat::esc( $end_ts - $start_ts ).'\', \''.SlimStat::esc( $resource ).'\' )';
	mysql_query( $insert_new_query, $connection );
	if ( mysql_error( $connection ) != '' ) {
		echo 'Error: '.mysql_error( $connection )."\n";
	}
}

// empty cache (in case of old data showing zeroes)

echo 'Emptying cache...'."\n";

$cache_query = 'DELETE FROM `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_cache ).'` ';
$cache_query .= 'WHERE `app_version`=\''.SlimStat::esc( SlimStat::app_version() ).'\'';
@mysql_query( $cache_query, $connection );

echo 'Done.'."\n";
