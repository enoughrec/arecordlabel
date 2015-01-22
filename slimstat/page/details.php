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

// detect whether this is an ajax request

$ajax_capable = ( array_key_exists( 'slimstatajax', $_COOKIE ) && $_COOKIE['slimstatajax'] == 1 );
$ajax_request = ( array_key_exists( 'ajax', $_REQUEST ) && $_REQUEST['ajax'] == 1 );

// set up fields

$time_fields = array( 'yr', 'mo', 'dy', 'hr', 'mi' );
$hit_fields = array( 'resource', 'country', 'language', 'browser', 'version', 'platform', 'resolution' );
$visit_fields = array( 'remote_ip', 'search_terms', 'domain', 'referrer', 'start_resource', 'end_resource', 'hits' );

// set up filters

$filters = array();
$has_filters = false;
foreach ( $time_fields as $key ) {
	if ( array_key_exists( 'filter_'.$key, $_GET ) ) {
		$filters[$key] = $_GET['filter_'.$key];
	}
}
foreach ( array_merge( $hit_fields, $visit_fields ) as $key ) {
	if ( array_key_exists( 'filter_'.$key, $_GET ) ) {
		$filters[$key] = $_GET['filter_'.$key];
		$has_filters = true;
	}
}

if ( array_key_exists( 'QUERY_STRING', $_SERVER ) &&
     ( ( $ajax_request == false && $_SERVER['QUERY_STRING'] == 'today' ) ||
       ( $ajax_request == true && $_SERVER['QUERY_STRING'] == 'ajax=1&today' ) ) ) {
	$filters['yr'] = date( 'Y' );
	$filters['mo'] = date( 'n' );
	$filters['dy'] = date( 'j' );
}

if ( array_key_exists( 'yr', $filters ) ) {
	$filters['yr'] = valid_yr( $filters['yr'] );
}
if ( array_key_exists( 'mo', $filters ) ) {
	$filters['mo'] = valid_mo( $filters['mo'] );
}

if ( !array_key_exists( 'yr', $filters ) ) {
	$filters['yr'] = date( 'Y' );
}
if ( !array_key_exists( 'mo', $filters ) ) {
	$filters['mo'] = date( 'n' );
}

if ( array_key_exists( 'dy', $filters ) ) { // do this after yr and mo are set
	$filters['dy'] = valid_dy( $filters['dy'], $filters['mo'], $filters['yr'] );
}

$is_filtering_visits_only = false;
foreach ( $visit_fields as $key ) {
	if ( array_key_exists( $key, $filters ) ) {
		$is_filtering_visits_only = true;
		break;
	}
}

// go

function render_page() {
	global $i18n, $curr_data, $prev_data, $ajax_request, $ajax_capable, $filters, $prev_filters;
	
	if ( array_key_exists( 'format', $_GET ) && $_GET['format'] == 'xml' ) {
		
		$curr_data = load_data( $filters );
		include( realpath( dirname( __FILE__ ) ).'/details_xml.php' );
		render_page_xml();
		
	} elseif ( array_key_exists( 'format', $_GET ) && $_GET['format'] == 'rss' ) {
		
		include( realpath( dirname( __FILE__ ) ).'/details_rss.php' );
		render_page_rss();
		
	} else {
		
		$prev_filters = prev_period( $filters );
		if ( $ajax_request || !$ajax_capable ) {
			$curr_data = load_data( $filters );
			$prev_data = load_data( $prev_filters );
		} else {
			$curr_data = array();
			$prev_data = array();
		}
		
		if ( ( !array_key_exists( 'QUERY_STRING', $_SERVER ) || $_SERVER['QUERY_STRING'] == '' ) &&
		     ( array_key_exists( 'yr', $curr_data ) && array_sum( $curr_data['yr'] ) == 0 ) &&
		     ( array_key_exists( 'yr', $prev_data ) && array_sum( $prev_data['yr'] ) == 0 ) ) {
			include( realpath( dirname( __FILE__ ) ).'/welcome.php' );
			exit;
		} else {
			include( realpath( dirname( __FILE__ ) ).'/details_html.php' );
			render_page_html();
		}
		
	}
}




// functions

function load_cache_data( $_filters ) {
	global $slimstat, $config, $connection, $hit_fields, $visit_fields;
	
	$query = 'SELECT `cache` FROM `'.$slimstat->esc( $config->db_database ).'`.`'.$slimstat->esc( $config->tbl_cache ).'`';
	$query .= ' WHERE `tz`=\''.$slimstat->esc( $config->timezone ).'\' AND `yr`=\''.$slimstat->esc( $_filters['yr'] ).'\'';
	$query .= ' AND `mo`=\''.$slimstat->esc( $_filters['mo'] ).'\'';
	if ( array_key_exists( 'dy', $_filters ) ) {
		$query .= ' AND `dy`=\''.$slimstat->esc( $_filters['dy'] ).'\'';
	} else {
		$query .= ' AND `dy`=\'0\'';
	}
	$query .= ' AND `app_version`=\''.$slimstat->esc( $slimstat->app_version() ).'\'';
	foreach ( $hit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			$query .= ' AND `'.$slimstat->esc( $key ).'`=\''.$slimstat->esc( urldecode( $_filters[$key] ) ).'\'';
		} else {
			$query .= ' AND `'.$slimstat->esc( $key ).'` IS NULL';
		}
	}
	foreach ( $visit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			$query .= ' AND `'.$slimstat->esc( $key ).'`=\''.$slimstat->esc( $_filters[$key] ).'\'';
		} elseif ( $key == 'hits' ) {
			$query .= ' AND `'.$slimstat->esc( $key ).'`=\'0\'';
		} else {
			$query .= ' AND `'.$slimstat->esc( $key ).'` IS NULL';
		}
	}
	// echo htmlspecialchars( $query )."<br />\n";
	$result = @mysql_query( $query, $connection );
	// echo htmlspecialchars( mysql_error() );
	if ( @mysql_num_rows( $result ) > 0 ) {
		list( $data_serialized ) = @mysql_fetch_row( $result );
		return unserialize( gzinflate( $data_serialized ) );
	}
	return null;
}

function get_date_clause( $_filters, $_yr_field, $_mo_field, $_dy_field, $_hr_field, $_mi_field ) {
	global $is_filtering_visits_only;
	
	if ( array_key_exists( 'dy', $_filters ) ) {
		$start_ts = mktime( 0, 0, 0, $_filters['mo'], $_filters['dy'], $_filters['yr'] );
		$end_ts = mktime( 23, 59, 59, $_filters['mo'], $_filters['dy'], $_filters['yr'] );
	} else {
		$start_ts = mktime( 0, 0, 0, $_filters['mo'], 1, $_filters['yr'] );
		$end_ts = mktime( 23, 59, 59, $_filters['mo'] + 1, 0, $_filters['yr'] );
	}

	$start_yr = intval( gmdate( 'Y', $start_ts ) );
	$start_mo = intval( gmdate( 'n', $start_ts ) );
	$start_dy = intval( gmdate( 'j', $start_ts ) );
	$start_hr = intval( gmdate( 'G', $start_ts ) );
	$start_mi = intval( floor( gmdate( 'i', $start_ts ) / 15 ) * 15 );

	$end_yr = intval( gmdate( 'Y', $end_ts ) );
	$end_mo = intval( gmdate( 'n', $end_ts ) );
	$end_dy = intval( gmdate( 'j', $end_ts ) );
	$end_hr = intval( gmdate( 'G', $end_ts ) );
	$end_mi = intval( floor( gmdate( 'i', $end_ts ) / 15 ) * 15 );
	
	$subquery = '( ( `'.$_yr_field.'`>'.$start_yr.' OR ( `'.$_yr_field.'`='.$start_yr.' AND (';
	$subquery .= ' `'.$_mo_field.'`>'.$start_mo.' OR ( `'.$_mo_field.'`='.$start_mo.' AND (';
	$subquery .= ' `'.$_dy_field.'`>'.$start_dy.' OR ( `'.$_dy_field.'`='.$start_dy.' AND (';
	$subquery .= ' `'.$_hr_field.'`>'.$start_hr.' OR ( `'.$_hr_field.'`='.$start_hr.' AND';
	$subquery .= ' `'.$_mi_field.'`>='.$start_mi.' ) ) ) ) ) ) ) )';
	$subquery .= ' AND ( `'.$_yr_field.'`<'.$end_yr.' OR ( `'.$_yr_field.'`='.$end_yr.' AND (';
	$subquery .= ' `'.$_mo_field.'`<'.$end_mo.' OR ( `'.$_mo_field.'`='.$end_mo.' AND (';
	$subquery .= ' `'.$_dy_field.'`<'.$end_dy.' OR ( `'.$_dy_field.'`='.$end_dy.' AND (';
	$subquery .= ' `'.$_hr_field.'`<'.$end_hr.' OR ( `'.$_hr_field.'`='.$end_hr.' AND';
	$subquery .= ' `'.$_mi_field.'`<='.$end_mi.' ) ) ) ) ) ) ) ) )';
	
	if ( $is_filtering_visits_only ) {
		$query = '( '.$subquery.' OR '.str_replace( 'start', 'end', $subquery ).' )';
	} else {
		$query = $subquery;
	}
	
	return $query;
}

function load_hit_data( $_filters ) {
	global $slimstat, $config, $connection, $is_filtering_visits_only, $time_fields, $hit_fields;
	
	if ( $is_filtering_visits_only ) {
		return array();
	}
	
	$query = 'SELECT `'.implode( '`, `', array_merge( $time_fields, $hit_fields ) ).'`, `title`, `hits` ';
	$query .= 'FROM `'.$slimstat->esc( $config->db_database ).'`.`'.$slimstat->esc( $config->tbl_hits ).'` ';
	$query .= 'WHERE ';
	
	$query .= get_date_clause( $_filters, 'yr', 'mo', 'dy', 'hr', 'mi' );
	
	foreach ( $hit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			if ( $_filters[$key] != '' ) {
				$query .= ' AND `'.$slimstat->esc( $key ).'`=\''.$slimstat->esc( $_filters[$key] ).'\'';
			} else {
				$query .= ' AND `'.$slimstat->esc( $key ).'` IS NULL';
			}
		}
	}
	
	// echo htmlspecialchars( $query )."<br />\n";
	$result = @mysql_query( $query, $connection );
	// echo htmlspecialchars( mysql_error() );
	
	return parse_data( $result, $hit_fields, $_filters, true );
}

function load_visit_data( $_filters ) {
	global $slimstat, $config, $connection, $is_filtering_visits_only, $hit_fields, $visit_fields;
	
	$query = 'SELECT `start_yr`, `start_mo`, `start_dy`, `start_hr`, `'.implode( '`, `', $visit_fields );
	if ( $is_filtering_visits_only ) {
		// $query .= '`, `resource';
		$query .= '`, `'.implode( '`, `', $hit_fields ).'` ';
	} elseif ( array_key_exists( 'resource', $_filters ) ) {
		$query .= '`, `resource` AS \'visit_resource\' ';
	} else {
		$query .= '` ';
	}
	$query .= 'FROM `'.$slimstat->esc( $config->db_database ).'`.`'.$slimstat->esc( $config->tbl_visits ).'` WHERE ';
	
	$query .= get_date_clause( $_filters, 'start_yr', 'start_mo', 'start_dy', 'start_hr', 'start_mi' );
	
	foreach ( $hit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			if ( $_filters[$key] == '' ) {
				$query .= ' AND `'.$slimstat->esc( $key ).'` IS NULL';
			} elseif ( $key == 'resource' ) {
				$query .= ' AND `'.$key.'` LIKE \'% '.$slimstat->esc( $_filters[$key] ).' %\'';
			} else {
				$query .= ' AND `'.$key.'`=\''.$slimstat->esc( $_filters[$key] ).'\'';
			}
		}
	}
	foreach ( $visit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			if ( $_filters[$key] != '' ) {
				$query .= ' AND `'.$slimstat->esc( $key ).'`=\''.$slimstat->esc( $_filters[$key] ).'\'';
			} else {
				$query .= ' AND `'.$slimstat->esc( $key ).'` IS NULL';
			}
		}
	}
	
	// echo htmlspecialchars( $query )."<br />\n";
	$result = @mysql_query( $query, $connection );
	// echo htmlspecialchars( mysql_error() );
	if ( $is_filtering_visits_only ) {
		return parse_data( $result, array_merge( $visit_fields, $hit_fields ), $_filters, true );
	} elseif ( array_key_exists( 'resource', $_filters ) ) {
		return parse_data( $result, array_merge( $visit_fields, array( 'visit_resource' ) ), $_filters );
	} else {
		return parse_data( $result, $visit_fields, $_filters );
	}
}

function save_cache_data( $_filters, $_data ) {
	global $slimstat, $config, $connection, $hit_fields, $visit_fields;
	
	$query = 'INSERT INTO `'.$slimstat->esc( $config->db_database ).'`.`'.$slimstat->esc( $config->tbl_cache ).'` ';
	$query .= '( `app_version`, `tz`, `yr`, `mo`, `dy`, `'.implode( '`, `', $hit_fields );
	$query .= '`, `'.implode( '`, `', $visit_fields ).'`, `cache` ) VALUES ( \'';
	$query .= SlimStat::esc( $slimstat->app_version() ).'\', \''.$slimstat->esc( $config->timezone ).'\', ';
	foreach ( array( 'yr', 'mo', 'dy' ) as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			$query .= '\''.$slimstat->esc( $_filters[$key] ).'\', ';
		} else {
			$query .= '\'0\', ';
		}
	}
	foreach ( $hit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			$query .= '\''.$slimstat->esc( $_filters[$key] ).'\', ';
		} else {
			$query .= 'NULL, ';
		}
	}
	foreach ( $visit_fields as $key ) {
		if ( array_key_exists( $key, $_filters ) ) {
			$query .= '\''.$slimstat->esc( $_filters[$key] ).'\', ';
		} elseif ( $key == 'hits' ) {
			$query .= '\'0\', ';
		} else {
			$query .= 'NULL, ';
		}
	}
	$query .= '\''.$slimstat->esc( gzdeflate( serialize( $_data ) ) ).'\' )';
	// echo htmlspecialchars( mb_substr( $query, 0, 400 ) )."<br />\n";
	@mysql_query( $query, $connection );
	// echo htmlspecialchars( mysql_error() );
}

function load_data( $_filters ) {
	global $config, $i18n, $hit_fields, $visit_fields;
	
	$data = null;
	
	$have_cache_data = false;
	
	$is_caching = true;
	
	// get cached data
	if ( $is_caching && is_period_past( $_filters ) ) {
		$data = load_cache_data( $_filters );
		$have_cache_data = is_array( $data );
	}
	
	// if data is not cached, get data manually
	if ( !$have_cache_data ) {
		if ( array_key_exists( 'dy', $_filters ) ) { // day
			$data = array_merge( load_hit_data( $_filters ), load_visit_data( $_filters ) );
		} else { // month	
			$data = array();
			$max_dy = valid_dy( 31, $_filters['mo'], $_filters['yr'] );
			for ( $dy = 1; $dy <= $max_dy; $dy++ ) {
				$dy_data = load_data( array_merge( $_filters, array( 'dy' => $dy ) ) );
				
				foreach ( $dy_data as $field => $field_data ) {
					if ( !array_key_exists( $field, $data ) ) {
						$data[$field] = array();
					}
					if ( $field == 'title' ) {
						$data[$field] = array_merge( $data[$field], $field_data );
					} elseif ( $field == 'version' ) {
						foreach ( $field_data as $browser => $version_data ) {
							if ( !array_key_exists( $browser, $data[$field] ) ) {
								$data[$field][$browser] = array();
							}
							foreach ( $version_data as $key => $value ) {
								if ( array_key_exists( $key, $data[$field][$browser] ) ) {
									$data[$field][$browser][$key] += $value;
								} else {
									$data[$field][$browser][$key] = $value;
								}
							}
						}
					} else {
						foreach ( $field_data as $key => $value ) {
							if ( array_key_exists( $key, $data[$field] ) ) {
								$data[$field][$key] += $value;
							} else {
								$data[$field][$key] = $value;
							}
						}
					}
				}
			}
			
			sort_data( $data, array_merge( $hit_fields, $visit_fields ), true );
		}
	}
	
	if ( array_key_exists( 'title', $data ) ) {
		foreach ( $data['title'] as $key => $value ) {
			$i18n->data['labels']['resource.'.$key] = $value;
		}
	}
	
	// save data to cache
	if ( $is_caching && is_period_past( $_filters ) && !$have_cache_data ) {
		save_cache_data( $_filters, $data );
	}
	
	return $data;
}

function parse_data( $_result, $_fields, $_filters, $_init_time_fields=false ) {
	global $slimstat, $config, $is_filtering_visits_only, $time_fields, $hit_fields, $visit_fields;
	
	$data = array();
	
	foreach ( array_merge( $_fields, array( 'prev_resource', 'next_resource' ) ) as $field ) {
		$data[$field] = array();
	}
	$data['source'] = array( 'search_terms' => 0, 'referrer' => 0, 'direct' => 0 );
	
	// echo '<pre>';
	
	while ( $row = @mysql_fetch_assoc( $_result ) ) {
		if ( $is_filtering_visits_only ) {
			$local_start_time = SlimStat::local_time_fields( array( 'yr' => $row['start_yr'], 'mo' => $row['start_mo'], 'dy' => $row['start_dy'], 'hr' => $row['start_hr'], 0, 0 ) );
			
			$values = explode( "\n", $row['resource'] );
			$hits = 0;
			$valid_values = '';
			foreach ( $values as $value ) {
				if ( $value == '' ) {
					continue;
				}
				
				@list( $yr, $mo, $dy, $hr, $mi, $sc, $resource, $title ) = explode( ' ', $value, 8 );
				$local_time = SlimStat::local_time_fields( array( 'yr' => $yr, 'mo' => $mo, 'dy' => $dy, 'hr' => $hr, 'mi' => $mi, 'sc' => $sc ) );
				
				if ( ( array_key_exists( 'yr', $_filters ) && $local_time['yr'] != $_filters['yr'] ) ||
				     ( array_key_exists( 'mo', $_filters ) && $local_time['mo'] != $_filters['mo'] ) ||
				     ( array_key_exists( 'dy', $_filters ) && $local_time['dy'] != $_filters['dy'] ) ||
				     ( array_key_exists( 'hr', $_filters ) && $local_time['hr'] != $_filters['hr'] ) ||
				     ( array_key_exists( 'resource', $_filters ) && $resource != $_filters['resource'] ) ) {
					continue;
				}
				
				$valid_values .= $local_time['yr'].' '.$local_time['mo'].' '.$local_time['dy'].' ';
				$valid_values .= $local_time['hr'].' '.$local_time['mi'].' '.$local_time['sc'].' '.$resource.' '.$title."\n";
				
				$hits++;
			}
			if ( $hits == 0 ) {
				continue;
			} else {
				$row['resource'] = $valid_values;
				$row['hits'] = $hits;
			}
		}
		
		// print_r( $row );
		
		// convert to local time
		if ( array_key_exists( 'yr', $row ) && array_key_exists( 'mo', $row ) &&
		     array_key_exists( 'dy', $row ) && array_key_exists( 'hr', $row ) ) {
			$local_time = $slimstat->local_time_fields( $row );
			foreach ( $local_time as $field => $value ) {
				if ( !array_key_exists( $field, $data ) ) {
					$data[$field] = array();
				}
			
				if ( array_key_exists( $value, $data[$field] ) ) {
					$data[$field][$value] += $row['hits'];
				} else {
					$data[$field][$value] = $row['hits'];
				}
			}
		}
		
		if ( array_key_exists( 'title', $row ) && $row['title'] != '' && array_key_exists( 'resource', $row ) ) {
			if ( !array_key_exists( 'title', $data ) ) {
				$data['title'] = array();
			}
			$data['title'][ $row['resource'] ] = $row['title'];
		}
		
		if ( array_key_exists( 'search_terms', $row ) && array_key_exists( 'referrer', $row ) ) {
			if ( $row['search_terms'] != '' ) {
				$data['source']['search_terms']++;
			} elseif ( $row['referrer'] != '' ) {
				$data['source']['referrer']++;
			} else {
				$data['source']['direct']++;
			}
		}
		
		foreach ( $_fields as $field ) {
			if ( !array_key_exists( $field, $row ) ) {
				continue;
			}
			
			$value = $row[$field];
			
			if ( ( $field == 'search_terms' || $field == 'referrer' || $field == 'domain' ) && $value == '' ) {
				continue;
			}
			
			if ( $field == 'version' ) {
				$browser = $row['browser'];
				if ( !array_key_exists( $browser, $data[$field] ) ) {
					$data[$field][$browser] = array();
				}
				
				if ( $is_filtering_visits_only ) {
					if ( array_key_exists( $value, $data[$field][$browser] ) ) {
						$data[$field][$browser][$value]++;
					} else {
						$data[$field][$browser][$value] = 1;
					}
				} else {
					if ( array_key_exists( $value, $data[$field][$browser] ) ) {
						$data[$field][$browser][$value] += $row['hits'];
					} else {
						$data[$field][$browser][$value] = $row['hits'];
					}
				}
			} elseif ( $field == 'resource' && $is_filtering_visits_only ) {
				if ( array_key_exists( 'start_resource', $_filters ) ) {
					parse_next_prev_resources( $data, $value, $_filters['start_resource'] );
				} elseif ( array_key_exists( 'end_resource', $_filters ) ) {
					parse_next_prev_resources( $data, $value, $_filters['end_resource'] );
				}
				
				$values = explode( "\n", $value );
				$resources = array();
				foreach ( $values as $value ) {
					if ( $value == '' ) {
						continue;
					}
					
					// time was converted to local time above, no need to convert again
					list( $yr, $mo, $dy, $hr, $mi, $sc, $resource, $title ) = explode( ' ', $value, 8 );
					
					if ( $title != '' ) {
						if ( !array_key_exists( 'title', $data ) ) {
							$data['title'] = array();
						}
						$data['title'][$resource] = $title;
					}
					
					$hit_time_fields = array( 'yr' => $yr, 'mo' => $mo, 'dy' => $dy, 'hr' => $hr );
					
					foreach ( $hit_time_fields as $time_field => $time_value ) {
						if ( !array_key_exists( $time_field, $data ) ) {
							$data[$time_field] = array();
						}
						if ( array_key_exists( $time_value, $data[$time_field] ) ) {
							$data[$time_field][$time_value]++;
						} else {
							$data[$time_field][$time_value] = 1;
						}
					}
					
					if ( $resource != '' ) {
						if ( array_key_exists( $resource, $data[$field] ) ) {
							$data[$field][$resource]++;
						} else {
							$data[$field][$resource] = 1;
						}
						$resources[] = $resource;
					}
				}
			} elseif ( $field == 'visit_resource' ) {
				parse_next_prev_resources( $data, $value, $_filters['resource'] );
			} elseif ( in_array( $field, $visit_fields ) || $is_filtering_visits_only ) {
				if ( $is_filtering_visits_only &&
					 ( ( array_key_exists( 'yr', $_filters ) && $local_start_time['yr'] != $_filters['yr'] ) ||
				       ( array_key_exists( 'mo', $_filters ) && $local_start_time['mo'] != $_filters['mo'] ) ||
				       ( array_key_exists( 'dy', $_filters ) && $local_start_time['dy'] != $_filters['dy'] ) ||
				       ( array_key_exists( 'hr', $_filters ) && $local_start_time['hr'] != $_filters['hr'] ) ) ) {
					continue;
				}
				
				if ( array_key_exists( $value, $data[$field] ) ) {
					$data[$field][$value]++;
				} else {
					$data[$field][$value] = 1;
				}
			} else {
				if ( array_key_exists( $value, $data[$field] ) ) {
					$data[$field][$value] += $row['hits'];
				} else {
					$data[$field][$value] = $row['hits'];
				}
			}
		}
	}
	
	sort_data( $data, $_fields, $_init_time_fields );
	
	// echo '</pre>'."\n";
	
	return $data;
}

function sort_data( &$_data, $_fields, $_init_time_fields=false ) {
	global $time_fields;
	
	foreach ( $time_fields as $field ) {
		if ( array_key_exists( $field, $_data ) ) {
			ksort( $_data[$field] );
		} elseif ( $_init_time_fields ) {
			$_data[$field] = array();
		}
	}
	foreach ( array_merge( $_fields, array( 'prev_resource', 'next_resource' ) ) as $field ) {
		if ( array_key_exists( $field, $_data ) ) {
			if ( $field == 'version' ) {
				foreach ( array_keys( $_data[$field] ) as $browser ) {
					arsort( $_data[$field][$browser] );
				}
			} else {
				arsort( $_data[$field] );
			}
		}
	}
}

function parse_next_prev_resources( &$_data, &$_visit_resource, &$_resource ) {
	$visit_resources = explode( "\n", $_visit_resource );
	$resources = array();
	foreach ( $visit_resources as $visit_resource ) {
		if ( $visit_resource == '' ) {
			continue;
		}

		list( $yr, $mo, $dy, $hr, $mi, $sc, $resource, $title ) = explode( ' ', $visit_resource, 8 );
		
		if ( $title != '' ) {
			if ( !array_key_exists( 'title', $_data ) ) {
				$_data['title'] = array();
			}
			$_data['title'][$resource] = $title;
		}
		
		if ( $resource != '' ) {
			$resources[] = $resource;
		}
	}
	
	for ( $resource_pos=0; $resource_pos<sizeof( $resources ); $resource_pos++ ) {
		if ( $resources[$resource_pos] == $_resource ) {
			$prev_resource = '';
			$next_resource = '';
			if ( $resource_pos > 0 ) {
				$prev_resource = $resources[$resource_pos - 1];
			}
			if ( $resource_pos < sizeof( $resources ) - 1 ) {
				$next_resource = $resources[$resource_pos + 1];
			}

			if ( array_key_exists( $prev_resource, $_data['prev_resource'] ) ) {
				$_data['prev_resource'][$prev_resource]++;
			} else {
				$_data['prev_resource'][$prev_resource] = 1;
			}
			if ( array_key_exists( $next_resource, $_data['next_resource'] ) ) {
				$_data['next_resource'][$next_resource]++;
			} else {
				$_data['next_resource'][$next_resource] = 1;
			}
		}
	}
}

function is_period_past( $_filters ) {
	return (
		$_filters['yr'] < gmdate( 'Y' ) ||
		$_filters['mo'] < gmdate( 'n' ) ||
		( array_key_exists( 'dy', $_filters ) && $_filters['dy'] < gmdate( 'j' ) ) );
}

function valid_hr( $_hr ) {
	return max( 0, min( 23, intval( $_hr ) ) );
}

function valid_dy( $_dy, $_mo, $_yr ) {
	$dy = max( 1, min( date( 'j', gmmktime( 12, 0, 0, $_mo + 1, 0, $_yr ) ), intval( $_dy ) ) );
	if ( $_yr == date( 'Y' ) && $_mo == date( 'n' ) ) {
		$dy = min( date( 'j' ), $dy );
	}
	return $dy;
}

function valid_mo( $_mo ) {
	return max( 1, min( 12, intval( $_mo ) ) );
}

function valid_yr( $_yr ) {
	return max( 1970, min( 3000, intval( $_yr ) ) );
}

function date_label( $_array, $_dy_override=null ) {
	$yr = valid_yr( $_array['yr'] );
	$mo = ( array_key_exists( 'mo', $_array ) ) ? valid_mo( $_array['mo'] ) : null;
	$dy = ( array_key_exists( 'dy', $_array ) ) ? valid_dy( $_array['dy'], $mo, $yr ) : null;
	if ( $_dy_override === false ) {
		$dy = null;
	} elseif ( $_dy_override > 0 ) {
		$dy = valid_dy( $_dy_override, $mo, $yr );
	}
	
	if ( $dy != null && $mo != null ) {
		// echo gmstrftime( '%z', gmmktime( 12, 0, 0, $mo, $dy, $yr ) );
		return gmstrftime( '%a %e %b %Y', gmmktime( 12, 0, 0, $mo, $dy, $yr ) );
	} elseif ( $mo != null ) {
		// echo gmstrftime( '%z', gmmktime( 12, 0, 0, $mo, 1, $yr ) );
		return gmstrftime( '%b %Y', gmmktime( 12, 0, 0, $mo, 1, $yr ) );
	} else {
		return $yr;
	}
}

function prev_period( $_query_fields, $_ignore_dy=false ) {
	$prev_fields = $_query_fields;
	
	if ( $_ignore_dy && array_key_exists( 'dy', $prev_fields ) ) {
		unset( $prev_fields['dy'] );
	}
	
	if ( !$_ignore_dy && array_key_exists( 'dy', $_query_fields ) && array_key_exists( 'mo', $_query_fields ) && array_key_exists( 'yr', $_query_fields ) ) {
		$prev_ts = gmmktime( 12, 0, 0, $_query_fields['mo'], $_query_fields['dy'] - 1, $_query_fields['yr'] );
		$prev_fields['dy'] = date( 'j', $prev_ts );
		$prev_fields['mo'] = date( 'n', $prev_ts );
		$prev_fields['yr'] = date( 'Y', $prev_ts );
	} elseif ( array_key_exists( 'mo', $_query_fields ) && array_key_exists( 'yr', $_query_fields ) ) {
		$prev_ts = gmmktime( 12, 0, 0, $_query_fields['mo'] - 1, 1, $_query_fields['yr'] );
		$prev_fields['mo'] = date( 'n', $prev_ts );
		$prev_fields['yr'] = date( 'Y', $prev_ts );
	} elseif ( array_key_exists( 'yr', $_query_fields ) ) {
		$prev_fields['yr'] = $_query_fields['yr'] - 1;
	}
	
	return $prev_fields;
}

function next_period( $_query_fields, $_ignore_dy=false ) {
	$next_fields = $_query_fields;
	
	if ( $_ignore_dy && array_key_exists( 'dy', $next_fields ) ) {
		unset( $next_fields['dy'] );
	}

	if ( !$_ignore_dy && array_key_exists( 'dy', $_query_fields ) && array_key_exists( 'mo', $_query_fields ) && array_key_exists( 'yr', $_query_fields ) ) {
		$next_ts = gmmktime( 12, 0, 0, $_query_fields['mo'], $_query_fields['dy'] + 1, $_query_fields['yr'] );
		$next_fields['dy'] = date( 'j', $next_ts );
		$next_fields['mo'] = date( 'n', $next_ts );
		$next_fields['yr'] = date( 'Y', $next_ts );
	} elseif ( array_key_exists( 'mo', $_query_fields ) && array_key_exists( 'yr', $_query_fields ) ) {
		$next_ts = gmmktime( 12, 0, 0, $_query_fields['mo'] + 1, 1, $_query_fields['yr'] );
		$next_fields['mo'] = date( 'n', $next_ts );
		$next_fields['yr'] = date( 'Y', $next_ts );
	} elseif ( array_key_exists( 'yr', $_query_fields ) ) {
		$next_fields['yr'] = $_query_fields['yr'] + 1;
	}

	return $next_fields;
}

function format_percent( $_percent ) {
	if ( $_percent < 100 ) {
		return format_number( $_percent );
	} else {
		return round( $_percent );
	}
}

function to1dp( $_number ) {
	return number_format( $_number, 1, '.', '' );
}

function hsc( $_str ) {
	return htmlspecialchars( $_str );
}
