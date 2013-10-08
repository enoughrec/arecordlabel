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

$base_href = '';
if ( ( array_key_exists( 'HTTPS', $_SERVER ) && $_SERVER['HTTPS'] == 'on' ) ) {
	$base_href .= 'https://';
} else {
	$base_href .= 'http://';
}
$base_href .= $_SERVER['SERVER_NAME'];

if ( dirname( $_SERVER['SCRIPT_NAME'] ) != '/' ) {
	$base_href .= dirname( $_SERVER['SCRIPT_NAME'] );
}

header( 'Content-type: text/javascript' );

?>
document.write('<img src="<?php echo $base_href; ?>/stats_js.php?ref=' + escape(document.referrer) + '&amp;url=' + escape(document.URL));
document.write('&amp;res=' + escape(screen.width+'x'+screen.height) + '&amp;ttl=' + encodeURIComponent(document.title) + '" style="position:absolute;top:-10px;left:0"');
document.writeln(' width="1" height="1" alt="" />');