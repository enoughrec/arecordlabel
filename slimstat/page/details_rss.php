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

function render_page_rss() {
	global $config, $i18n, $filters;
	
	header( 'Content-Type: application/rss+xml' );
	echo '<'.'?xml version="1.0" encoding="UTF-8"?'.">\n";
	echo '<rss version="2.0">'."\n";
	echo '<channel>'."\n";
	echo '<title>SlimStat';
	if ( $config->sitename != '' ) {
		echo ' for '.htmlspecialchars( $config->sitename );
	}
	echo '</title>'."\n";
	echo '<link>http://'.$_SERVER['SERVER_NAME'].dirname( $_SERVER['PHP_SELF'] ).'/</link>'."\n";
	echo '<description>SlimStat';
	if ( $config->sitename != '' ) {
		echo ' for '.htmlspecialchars( $config->sitename );
	}
	echo '</description>'."\n";
	echo '<pubDate>'.date( 'r', strtotime( date( 'Y-m-d 00:00:00' ) ) - 1 ).'</pubDate>'."\n";
	echo '<lastBuildDate>'.date( 'r', strtotime( date( 'Y-m-d 00:00:00' ) ) - 1 ).'</lastBuildDate>'."\n";
	echo '<docs>http://blogs.law.harvard.edu/tech/rss</docs>'."\n";
	echo '<ttl>60</ttl>'."\n\n";
	
	$filters = array_merge( $filters, array( 'yr' => date( 'Y' ), 'mo' => date( 'n' ), 'dy' => date( 'j' ) ) );
	
	for ( $i=0; $i<7; $i++ ) {
		$filters = prev_period( $filters );
		$data = load_data( $filters );
		
		echo '<item>'."\n";
		echo '<title>'.date_label( $filters ).'</title>'."\n";
		echo '<link>http://'.$_SERVER['SERVER_NAME'].dirname( $_SERVER['PHP_SELF'] ).'/'.filter_url( $filters ).'</link>'."\n";
		echo '<description>';
		if ( array_key_exists( 'yr', $data ) ) {
			echo 'Hits: '.array_sum( $data['yr'] ).'&lt;br /&gt;';
		}
		if ( array_key_exists( 'hits', $data ) ) {
			echo 'Visits: '.array_sum( $data['hits'] ).'&lt;br /&gt;';
		}
		if ( array_key_exists( 'remote_ip', $data ) ) {
			echo 'Unique IPs: '.sizeof( $data['remote_ip'] ).'&lt;br /&gt;';
		}
		
		echo '</description>'."\n";
		echo '<guid isPermaLink="true">http://'.$_SERVER['SERVER_NAME'].dirname( $_SERVER['PHP_SELF'] ).'/'.filter_url( $filters ).'</guid>'."\n";
		echo '<pubDate>'.date( 'r', mktime( 0, 0, 0, $filters['mo'], $filters['dy'] + 1, $filters['yr'] ) - 1 ).'</pubDate>'."\n";
		echo '</item>'."\n\n";
	}
	
	echo '</channel>';
	echo '</rss>'."\n";
}
