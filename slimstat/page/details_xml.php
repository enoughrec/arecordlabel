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

function render_page_xml() {
	global $curr_data;
	
	header( 'Content-Type: text/xml; charset=UTF-8' );
	echo '<'.'?xml version="1.0" encoding="UTF-8"?'.">\n";
	echo '<data>';
	render_data_xml( $curr_data );
	echo '</data>';
}

function render_data_xml( $_data ) {
	global $i18n;
	
	foreach ( $_data as $field => $values ) {
		echo '<field name="'.htmlspecialchars( $field ).'">';
		if ( $field == 'version' ) {
			foreach ( $values as $browser => $browser_values ) {
				echo '<key name="'.htmlspecialchars( $i18n->label( $field, $browser ) ).'">';
				foreach ( $browser_values as $key => $value ) {
					echo '<value name="'.htmlspecialchars( $i18n->label( 'version', $key ) ).'">'.intval( $value ).'</value>'."\n";
				}
				echo '</key>'."\n";
			}
		} else {
			foreach ( $values as $key => $value ) {
				echo '<value name="';
				echo htmlspecialchars( $i18n->label( $field, $key ) );
				echo '">'.intval( $value ).'</value>'."\n";
			}
		}
		echo '</field>'."\n";
	}
}
