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

require_once( realpath( dirname( __FILE__ ) ).'/config.php' );

class SlimStatI18n {
	
	var $data;
	
	function SlimStatI18n() {
		global $config;
		//global $i18n;
		
		//$i18n->data = array();
		$this->data = parse_ini_file( realpath( dirname( dirname( __FILE__ ) ) ).'/_i18n/'.preg_replace( "[^A-Za-z\-]", '', $config->language ).'.ini', true );
		//var_dump($i18n->data);
	}
	
	/*function &get_instance() {
		static $i18n_instance = array();
		if ( empty( $i18n_instance ) ) {
			// Assigning the return value of new by reference is deprecated in PHP 5.3
			//if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
				$i18n_instance[] = new SlimStatI18n();
			//} else {
			//	$i18n_instance[] =& new SlimStatI18n();
			//}
		}
		return $i18n_instance[0];
	}*/
	
	function label( $_field, $_key ) {
		global $i18n;
		 
		if ( ( $_field == 'prev_resource' || $_field == 'next_resource' ) && $_key == '' &&
		     array_key_exists( $_field.'.'.$_key, $i18n->data['labels'] ) ) {
			return $i18n->data['labels'][$_field.'.'.$_key];
		}
		
		if ( strstr( $_field, '_resource' ) ) {
			$_field = 'resource';
		}
		
		if ( array_key_exists( $_field.'.'.$_key, $i18n->data['labels'] ) ) {
			return $i18n->data['labels'][$_field.'.'.$_key];
		} elseif ( $_key == '' ) {
			return $i18n->data['core']['indeterminable'];
		} elseif ( $_field == 'language' && mb_strlen( $_key ) == 5 ) {
			$language = mb_strtolower( mb_substr( $_key, 0, 2 ) );
			$country = mb_strtoupper( mb_substr( $_key, 3, 2 ) );
			
			if ( array_key_exists( 'language.'.$language, $i18n->data['labels'] ) ) {
				if ( array_key_exists( 'country.'.$country, $i18n->data['labels'] ) ) {
					return sprintf(
						$i18n->data['core']['language_country'],
					    $i18n->data['labels']['language.'.$language],
					    $i18n->data['labels']['country.'.$country] );
				} else {
					return $i18n->data['labels']['language.'.$language];
				}
			} else {
				return $_key;
			}
		} else {
			return $_key;
		}
	}
	
	function _( $_category, $_field, $_str='' ) {
		global $i18n;
		//var_dump($_category);
		//var_dump($i18n->data);
		if ( array_key_exists( $_category, $i18n->data ) &&
		     array_key_exists( $_field, $i18n->data[$_category] ) ) {
			if ( $_str != '' ) {
				return sprintf( $i18n->data[$_category][$_field], $_str );
			} else {
				return $i18n->data[$_category][$_field];
			}
		} else {
			return $_field;
		}
	}
	
	function hsc( $_category, $_field, $_str='' ) {
		global $i18n;
		return htmlspecialchars( $i18n->_( $_category, $_field, $_str ) );
	}
}
