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

class SlimStatI18nBase {
	
	var $config;
	
	function init() {
		$this->labels['resource'] = array();
	}
	
	function &get_instance() {
		static $i18n_instance = array();
		if ( empty( $i18n_instance ) ) {
			$i18n_instance[] =& new SlimStatI18n();
		}
		return $i18n_instance[0];
	}
	
	function label( $_field, $_key ) {
		if ( $_field == 'start_resource' || $_field == 'end_resource' ) {
			$_field = 'resource';
		}
		
		if ( array_key_exists( $_field, $this->labels ) && array_key_exists( $_key, $this->labels[$_field] ) ) {
			return $this->labels[$_field][$_key];
		} elseif ( $_key == '' ) {
			return $this->indeterminable;
		} elseif ( $_field == 'language' && mb_strlen( $_key ) == 5 ) {
			$language = mb_strtolower( mb_substr( $_key, 0, 2 ) );
			$country = mb_strtoupper( mb_substr( $_key, 3, 2 ) );
			
			if ( array_key_exists( $language, $this->labels['language'] ) ) {
				if ( array_key_exists( $country, $this->labels['country'] ) ) {
					return sprintf( $this->language_country, $this->labels['language'][$language], $this->labels['country'][$country] );
				} else {
					return $this->labels['language'][$language];
				}
			} else {
				return $_key;
			}
		} else {
			return $_key;
		}
	}
}
