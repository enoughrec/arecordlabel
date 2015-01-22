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

if ( isset( $_GET['ref'] ) && isset( $_GET['url'] ) && isset( $_GET['res'] ) && isset( $_GET['ttl'] ) ) {
	$_SERVER['HTTP_REFERER'] = rawurldecode( $_GET['ref'] );
	
	$url = @parse_url( rawurldecode( $_GET['url'] ) );
	$slimstat_resolution = $_GET['res'];
	$slimstat_title = $_GET['ttl'];
	if ( isset( $url['path'] ) ) {
		if ( isset( $url['query'] ) ) {
			$_SERVER['REQUEST_URI'] = $url['path'].'?'.$url['query'];
		} else {
			$_SERVER['REQUEST_URI'] = $url['path'];
		}
		@include_once( realpath( dirname( __FILE__ ) ).'/stats_include.php' );
	}
}

header( 'Content-Type: image/gif' );
readfile( '_img/pixel.gif' );
