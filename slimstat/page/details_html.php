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

function render_page_html() {
	global $config, $i18n, $filters, $prev_filters, $has_filters, $curr_data;
	
	page_head();
	
	$curr_date_label = htmlspecialchars( date_label( $filters ) );
	$prev_date_label = htmlspecialchars( date_label( $prev_filters ) );
	
	echo '<h2 id="title" class="grid16">'.$curr_date_label.' <span class="prev">compared with '.$prev_date_label.'</span></h2>'."\n";

	// main

	echo '<div id="main" class="grid16">';
	
	// side

	echo '<div id="side" class="grid4"><div id="sideinner" class="grid3 first">';

	calendar_widget();

	if ( $has_filters ) {
		echo '<h2>Filters</h2>';
	
		foreach ( array_merge( array_keys( $config->hit_fields ), array_keys( $config->visit_fields ) ) as $key ) {
			if ( array_key_exists( $key, $filters ) ) {
				$new_filters = $filters;
				unset( $new_filters[$key] );
				echo '<div class="grid3"><h3>'.htmlspecialchars( ( array_key_exists( $key, $config->hit_fields ) ) ? $config->hit_fields[$key] : $config->visit_fields[$key] ).'</h3>'."\n";
				echo '<p class="text"><a href="./'.filter_url( $new_filters ).'">';
				echo htmlspecialchars( $i18n->label( $key, $filters[$key] ) );
				echo '</a></p></div>'."\n";
			}
		}
	}
	
	echo '<h2 id="api"><a href="./?format=xml'.filter_url( $filters, '&amp;' ).'">XML format</a></h2>'."\n";
	
	$rss_filters = $filters;
	foreach ( array_keys( $config->time_fields ) as $time_field ) {
		if ( array_key_exists( $time_field, $rss_filters ) ) {
			unset( $rss_filters[$time_field] );
		}
	}
	echo '<h2 id="feed"><a href="./?format=rss'.filter_url( $rss_filters, '&amp;' ).'">RSS format</a></h2>'."\n";

	echo '</div></div>'."\n"; // side

	// content
	
	echo '<div id="content" class="grid12">';

	table_summary();

	if ( array_key_exists( 'resource', $filters ) && !array_key_exists( 'start_resource', $filters ) && !array_key_exists( 'end_resource', $filters ) ) {
		table_resource_summary();
	}

	echo '<h2 class="grid12">Content</h2>';

	if ( array_key_exists( 'dy', $filters ) ) {
		chart_hours();
	} else {
		chart_days();
	}

	if ( !array_key_exists( 'resource', $filters ) ) {
		table_total( 'resource' );
	}
	if ( !array_key_exists( 'start_resource', $filters ) ) {
		table_total( 'start_resource' );
	}
	if ( !array_key_exists( 'end_resource', $filters ) ) {
		table_total( 'end_resource' );
	}



	echo '<h2 class="grid12">Visitors</h2>';
	
	if ( !array_key_exists( 'remote_ip', $filters ) ) {
		table_total( 'remote_ip' );
	}
	if ( !array_key_exists( 'hits', $filters ) ) {
		chart_hits();
		// table_percent( 'hits' );
	}
	if ( !array_key_exists( 'browser', $filters ) ) {
		table_percent( 'browser' );
	}
	if ( !array_key_exists( 'platform', $filters ) ) {
		table_percent( 'platform' );
	}
	if ( !array_key_exists( 'resolution', $filters ) ) {
		table_percent( 'resolution' );
		// resolutions();
	}
	if ( !array_key_exists( 'country', $filters ) && SlimStat::is_geoip_installed() ) {
		table_percent( 'country' );
		map();
	}
	if ( !array_key_exists( 'language', $filters ) ) {
		table_percent( 'language' );
	}

	echo '<h2 class="grid12">Referrers</h2>';

	if ( !array_key_exists( 'search_terms', $filters ) ) {
		table_total( 'search_terms' );
	}
	if ( !array_key_exists( 'domain', $filters ) ) {
		table_total( 'domain' );
	}
	if ( !array_key_exists( 'referrer', $filters ) ) {
		table_total( 'referrer' );
	}
	if ( !array_key_exists( 'search_terms', $filters ) ) {
		sources();
	}
	
	echo '</div>'."\n"; // main

	echo '</div>'."\n"; // content
	
	page_foot();
}

function table_summary() {
	global $filters, $prev_filters, $curr_data, $prev_data, $curr_date_label, $prev_date_label;
	
	$curr_hits = array_sum( $curr_data['yr'] );
	$prev_hits = array_sum( $prev_data['yr'] );
	
	$curr_visits = array_sum( $curr_data['hits'] );
	$prev_visits = array_sum( $prev_data['hits'] );
	
	$curr_ips = sizeof( $curr_data['remote_ip'] );
	$prev_ips = sizeof( $prev_data['remote_ip'] );
	
	if ( array_key_exists( 'dy', $filters ) ) {
		if ( $filters['yr'] == gmdate( 'Y' ) && $filters['mo'] == gmdate( 'n' ) && $filters['dy'] == gmdate( 'j' ) ) {
			$curr_per = gmdate( 'G' ) + 1;
		} else {
			$curr_per = 24;
		}
		$prev_per = 24;
	} else {
		if ( $filters['yr'] == gmdate( 'Y' ) && $filters['mo'] == gmdate( 'n' ) ) {
			$curr_per = gmdate( 'j' );
		} else {
			$curr_per = days_in_month( $filters['mo'], $filters['yr'] );
		}
		$prev_per = days_in_month( $prev_filters['mo'], $prev_filters['yr'] );
	}
	
	$curr_hits_per = to1dp( $curr_hits / $curr_per );
	$prev_hits_per = to1dp( $prev_hits / $prev_per );
	
	$curr_visits_per = to1dp( $curr_visits / $curr_per );
	$prev_visits_per = to1dp( $prev_visits / $prev_per );
	
	if ( array_key_exists( 1, $curr_data['hits'] ) ) {
		$curr_bounce = ( $curr_visits > 0 ) ? to1dp( $curr_data['hits'][1] / $curr_visits * 100 ) : 100;
	} else {
		$curr_bounce = 0;
	}
	if ( array_key_exists( 1, $prev_data['hits'] ) ) {
		$prev_bounce = ( $prev_visits > 0 ) ? to1dp( $prev_data['hits'][1] / $prev_visits * 100 ) : 100;
	} else {
		$prev_bounce = 0;
	}
	
	echo '<div class="grid12" id="summary">';
	echo '<h3>Summary</h3>';
	echo '<div class="tbody"><table><tbody>'."\n";
	
	// current
	
	echo '<tr>';
	echo '<td class="center numeric first" title="'.$curr_date_label.'">'.format_number( $curr_hits, 0 ).'</td>';
	echo '<td><span class="text">hits</span></td>';
	echo '<td class="center numeric" title="'.$curr_date_label.'">'.format_number( $curr_visits, 0 ).'</td>';
	echo '<td><span class="text">visits</span></td>';
	echo '<td class="center numeric" title="'.$curr_date_label.'">'.format_number( $curr_ips, 0 ).'</td>';
	echo '<td><span class="text">unique IPs</span></td>';
	echo '<td class="center numeric" title="'.$curr_date_label.'">'.format_number( $curr_hits_per ).'</td>';
	echo '<td><span class="text">hits ∕ '.( ( array_key_exists( 'dy', $filters ) ) ? 'hour' : 'day' ).'</span></td>';
	echo '<td class="center numeric" title="'.$curr_date_label.'">'.format_number( $curr_visits_per ).'</td>';
	echo '<td class="last"><span class="text">visits ∕ '.( ( array_key_exists( 'dy', $filters ) ) ? 'hour' : 'day' ).'</span></td>';
	// echo '<td class="center numeric" title="'.$curr_date_label.'">'.format_number( $curr_bounce ).'%</td>';
	// echo '<td class="last"><span class="text">bounce rate</span></td>';
	echo '</tr>'."\n";
	
	// previous
	
	echo '<tr>';
	
	// hits
	
	echo '<td class="center numeric first prev" title="'.$prev_date_label.'">'.format_number( $prev_hits, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_hits == $curr_hits || $curr_hits == 0 || $prev_hits == 0 ) {
		echo '—';
	} elseif ( $curr_hits > $prev_hits ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_hits / $prev_hits ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_hits > $curr_hits ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_hits / $prev_hits ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// visits
	
	echo '<td class="center numeric prev" title="'.$prev_date_label.'">'.format_number( $prev_visits, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_visits == $curr_visits || $curr_visits == 0 || $prev_visits == 0 ) {
		echo '—';
	} elseif ( $curr_visits > $prev_visits ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_visits / $prev_visits ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_visits > $curr_visits ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_visits / $prev_visits ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// IPs
	
	echo '<td class="center numeric prev" title="'.$prev_date_label.'">'.format_number( $prev_ips, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_ips == $curr_ips || $curr_ips == 0 || $prev_ips == 0 ) {
		echo '—';
	} elseif ( $curr_ips > $prev_ips ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_ips / $prev_ips ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_ips > $curr_ips ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_ips / $prev_ips ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// hits per hour/day
	
	echo '<td class="center numeric prev" title="'.$prev_date_label.'">'.format_number( $prev_hits_per ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_hits_per == $curr_hits_per || $curr_hits_per == '0.0' || $prev_hits_per == '0.0' ) {
		echo '—';
	} elseif ( $curr_hits_per > $prev_hits_per ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_hits_per / $prev_hits_per ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_hits_per > $curr_hits_per ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_hits_per / $prev_hits_per ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// visits per hour/day
	
	echo '<td class="center numeric prev" title="'.$prev_date_label.'">'.format_number( $prev_visits_per ).'</td>';
	echo '<td class="numeric last">';
	if ( $prev_visits_per == $curr_visits_per || $curr_visits_per == 0 || $prev_visits_per == 0 ) {
		echo '—';
	} elseif ( $curr_visits_per > $prev_visits_per ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_visits_per / $prev_visits_per ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_visits_per > $curr_visits_per ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_visits_per / $prev_visits_per ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// bounce rate
	
	// echo '<td class="center numeric prev" title="'.$prev_date_label.'">'.format_number( $prev_bounce ).'%</td>';
	// echo '<td class="numeric last">';
	// if ( $prev_bounce == $curr_bounce ) {
	// 	echo '—';
	// } elseif ( $curr_bounce > $prev_bounce ) {
	// 	echo '<span class="dn">↑ '.format_percent( ( ( $curr_bounce / $prev_bounce ) - 1 ) * 100 ).'%</span>';
	// } elseif ( $prev_bounce > $curr_bounce ) {
	// 	echo '<span class="up">↓ '.format_percent( ( 1 - ( $curr_bounce / $prev_bounce ) ) * 100 ).'%</span>';
	// }
	// echo '</td>';
	echo '</tr>'."\n";
	
	echo '</tbody></table></div></div>'."\n";
}

function table_resource_summary() {
	global $i18n, $filters, $prev_filters, $curr_data, $prev_data;
	
	$curr_hits = 0;
	if ( array_key_exists( $filters['resource'], $curr_data['resource'] ) ) {
		$curr_hits = $curr_data['resource'][ $filters['resource'] ];
	}
	$prev_hits = 0;
	if ( array_key_exists( $filters['resource'], $prev_data['resource'] ) ) {
		$prev_hits = $prev_data['resource'][ $filters['resource'] ];
	}
	
	$curr_start_hits = 0;
	if ( array_key_exists( $filters['resource'], $curr_data['start_resource'] ) ) {
		$curr_start_hits = $curr_data['start_resource'][ $filters['resource'] ];
	}
	$prev_start_hits = 0;
	if ( array_key_exists( $filters['resource'], $prev_data['start_resource'] ) ) {
		$prev_start_hits = $prev_data['start_resource'][ $filters['resource'] ];
	}
	
	$curr_bounce_data = load_data( array_merge( $filters, array( 'start_resource' => $filters['resource'] ) ) );
	$prev_bounce_data = load_data( array_merge( $prev_filters, array( 'start_resource' => $filters['resource'] ) ) );
	$curr_bounce_visits = array_sum( $curr_bounce_data['hits'] );
	$prev_bounce_visits = array_sum( $prev_bounce_data['hits'] );
	if ( array_key_exists( 1, $curr_bounce_data['hits'] ) ) {
		$curr_bounce_rate = ( $curr_bounce_visits > 0 ) ? to1dp( $curr_bounce_data['hits'][1] / $curr_bounce_visits * 100 ) : 100;
	} else {
		$curr_bounce_rate = 0;
	}
	if ( array_key_exists( 1, $prev_bounce_data['hits'] ) ) {
		$prev_bounce_rate = ( $prev_bounce_visits > 0 ) ? to1dp( $prev_bounce_data['hits'][1] / $prev_bounce_visits * 100 ) : 100;
	} else {
		$prev_bounce_rate = 0;
	}
	
	$curr_end_hits = 0;
	if ( array_key_exists( $filters['resource'], $curr_data['end_resource'] ) ) {
		$curr_end_hits = $curr_data['end_resource'][ $filters['resource'] ];
	}
	$prev_end_hits = 0;
	if ( array_key_exists( $filters['resource'], $prev_data['end_resource'] ) ) {
		$prev_end_hits = $prev_data['end_resource'][ $filters['resource'] ];
	}
	
	$curr_exit_rate = ( $curr_hits > 0 ) ? to1dp( $curr_end_hits / $curr_hits * 100 ) : to1dp( 0 );
	$prev_exit_rate = ( $prev_hits > 0 ) ? to1dp( $prev_end_hits / $prev_hits * 100 ) : to1dp( 0 );
	
	echo '<div class="grid12 summary">';
	echo '<h3>'.htmlspecialchars( $i18n->label( 'resource', $filters['resource'] ) ).'</h3>';
	echo '<div class="tbody"><table><tbody>';
	
	// current
	
	echo '<tr>';
	echo '<td class="center numeric first">'.format_number( $curr_hits, 0 ).'</td>';
	echo '<td><span class="text">hits</span></td>';
	echo '<td class="center numeric">'.format_number( $curr_start_hits, 0 ).'</td>';
	echo '<td><span class="text">as entry page</span></td>';
	echo '<td class="center numeric">'.format_number( $curr_end_hits, 0 ).'</td>';
	echo '<td><span class="text">as exit page</span></td>';
	echo '<td class="center numeric">'.format_percent( $curr_bounce_rate ).'%</td>';
	echo '<td><span class="text">bounce rate</span></td>';
	echo '<td class="center numeric">'.format_percent( $curr_exit_rate ).'%</td>';
	echo '<td class="last"><span class="text">exit rate</span></td>';
	echo '</tr>'."\n";
	
	// previous
	
	echo '<tr>';
	
	// hits
	
	echo '<td class="center numeric first prev">'.format_number( $prev_hits, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_hits == $curr_hits || $curr_hits == 0 || $prev_hits == 0 ) {
		echo '—';
	} elseif ( $curr_hits > $prev_hits ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_hits / $prev_hits ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_hits > $curr_hits ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_hits / $prev_hits ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// hits as start page
	
	echo '<td class="center numeric prev">'.format_number( $prev_start_hits, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_start_hits == $curr_start_hits || $curr_start_hits == 0 || $prev_start_hits == 0 ) {
		echo '—';
	} elseif ( $curr_start_hits > $prev_start_hits ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_start_hits / $prev_start_hits ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_start_hits > $curr_start_hits ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_start_hits / $prev_start_hits ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// hits as exit page
	
	echo '<td class="center numeric prev">'.format_number( $prev_end_hits, 0 ).'</td>';
	echo '<td class="numeric">';
	if ( $prev_end_hits == $curr_end_hits || $curr_end_hits == 0 || $prev_end_hits == 0 ) {
		echo '—';
	} elseif ( $curr_end_hits > $prev_end_hits ) {
		echo '<span class="up">↑ '.format_percent( ( ( $curr_end_hits / $prev_end_hits ) - 1 ) * 100 ).'%</span>';
	} elseif ( $prev_end_hits > $curr_end_hits ) {
		echo '<span class="dn">↓ '.format_percent( ( 1 - ( $curr_end_hits / $prev_end_hits ) ) * 100 ).'%</span>';
	}
	echo '</td>';
	
	// bounce rate
	
	echo '<td class="center numeric prev">'.format_percent( $prev_bounce_rate ).'%</td>';
	echo '<td class="numeric">';
	if ( $prev_bounce_rate == $curr_bounce_rate || $curr_bounce_rate == 0 || $prev_bounce_rate == 0 ) {
		echo '—';
	} elseif ( $curr_bounce_rate > $prev_bounce_rate ) {
		echo '<span class="dn">↑ '.format_percent( $curr_bounce_rate - $prev_bounce_rate ).'%</span>';
	} elseif ( $prev_bounce_rate > $curr_bounce_rate ) {
		echo '<span class="up">↓ '.format_percent( $prev_bounce_rate - $curr_bounce_rate ).'%</span>';
	}
	echo '</td>';
	
	// exit rate
	
	echo '<td class="center numeric prev">'.format_percent( $prev_exit_rate ).'%</td>';
	echo '<td class="numeric last">';
	if ( $prev_exit_rate == $curr_exit_rate || $curr_exit_rate == 0 || $prev_exit_rate == 0 ) {
		echo '—';
	} elseif ( $curr_exit_rate > $prev_exit_rate ) {
		echo '<span class="dn">↑ '.format_percent( $curr_exit_rate - $prev_exit_rate ).'%</span>';
	} elseif ( $prev_exit_rate > $curr_exit_rate ) {
		echo '<span class="up">↓ '.format_percent( $prev_exit_rate - $curr_exit_rate ).'%</span>';
	}
	echo '</td>';
	echo '</tr></tbody></table></div></div>'."\n";
}

function table_total( $_field ) {
	global $config, $i18n, $is_iphone, $filters, $curr_data, $prev_data, $curr_date_label, $prev_date_label;
	
	if ( !array_key_exists( $_field, $curr_data ) || !array_key_exists( $_field, $prev_data ) ) {
		return;
	}
	
	$new_filters = $filters;
	$max_rows = ( $is_iphone ) ? 10 : 50;
	
	$curr = $curr_data[$_field];
	$prev = $prev_data[$_field];
	
	$curr_size = sizeof( $curr );
	if ( $curr_size < $max_rows && $curr_size < sizeof( $prev ) ) {
		$pos = 0;
		foreach ( $prev as $key => $hits ) {
			if ( !array_key_exists( $key, $curr ) ) {
				$curr[$key] = 0;
			}
			$pos++;
			if ( $pos >= $max_rows ) break;
		}
	}
	
	echo '<div class="grid';
	if ( $_field == 'resource' ) {
		echo '12';
	// } elseif ( stristr( $_field, 'resource' ) ) {
		// echo '6';
	} elseif ( $_field == 'referrer' ) {
		echo '12';
	} elseif ( stristr( $_field, 'resource' ) && ( array_key_exists( 'resource', $filters ) || array_key_exists( 'start_resource', $filters ) || array_key_exists( 'end_resource', $filters ) ) ) {
		echo '12';
	} else {
		echo '6';
	}
	echo ' table"><table>';
	echo '<thead><tr>';
	echo '<th class="first"><span class="text">'.( array_key_exists( $_field, $config->hit_fields ) ? $config->hit_fields[$_field] : $config->visit_fields[$_field] );
	echo ' ('.sizeof( $curr ).')';
	echo '</span></th>';
	echo '<th class="center">'.( array_key_exists( $_field, $config->hit_fields ) ? 'Hits' : 'Visits' ).'</th>';
	echo '<th class="center">&nbsp;</th>';
	echo '<th class="center last">&plusmn;</th>';
	echo '</tr></thead></table>'."\n";
	echo '<div class="tbody"><table><tbody>'."\n";
	
	$pos = 0;
	foreach ( $curr as $key => $hits ) {
		$new_filters[$_field] = $key;
		
		echo '<tr>';
		// echo '<td>'.( $pos + 1 ).'</td>';
		echo '<td class="first" title="'.htmlspecialchars( $key ).'"><span class="text">';
		if ( $_field == 'referrer' ) {
			echo '<a class="external" title="'.htmlspecialchars( $key ).'" href="'.htmlspecialchars( $key ).'" rel="nofollow">&rarr;</a> ';
		} elseif ( $_field == 'remote_ip' ) {
			echo '<a class="external" title="'.str_replace( '%i', $key, $config->whoisurl ).'" href="'.str_replace( '%i', $key, $config->whoisurl ).'" rel="nofollow">&rarr;</a> ';
		}
		echo '<a href="./'.filter_url( $new_filters ).'">';
		echo htmlspecialchars( $i18n->label( $_field, $key ) );
		echo '</a></span></td>';
		echo '<td class="center" title="'.$curr_date_label.'">'.format_number( $hits, 0 ).'</td>';
		echo '<td class="center prev" title="'.$prev_date_label.'">';
		if ( array_key_exists( $key, $prev ) ) {
			echo format_number( $prev[$key], 0 );
		} else {
			echo '0';
		}
		echo '</td>';
		echo '<td class="center last">';
		if ( array_key_exists( $key, $prev ) ) {
			$prev_pos = array_search( $key, array_keys( $prev ) );
			if ( $prev_pos == $pos ) {
				echo '—';
			} elseif ( $prev_pos > $pos ) {
				echo '<span class="up">↑ '.( $prev_pos - $pos ).'</span>';
			} elseif ( $pos > $prev_pos ) {
				echo '<span class="dn">↓ '.( $pos - $prev_pos ).'</span>';
			}
		} else {
			echo '<span class="up">new</span>';
		}
		echo '</td>';
		echo '</tr>'."\n";
		
		$pos++;
		if ( $pos >= $max_rows ) break;
	}
	
	// while ( $pos < 10 ) {
	// 	echo '<tr>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '</tr>'."\n";
	// 	$pos++;
	// }

	echo '</tbody></table></div></div>'."\n";
}

function table_percent( $_field ) {
	global $config, $i18n, $is_iphone, $filters, $curr_data, $prev_data, $curr_date_label, $prev_date_label;
	
	if ( !array_key_exists( $_field, $curr_data ) || !array_key_exists( $_field, $prev_data ) ) {
		return;
	}
	
	$new_filters = $filters;
	$max_rows = ( $is_iphone ) ? 10 : 50;
	
	$curr = $curr_data[$_field];
	$prev = $prev_data[$_field];
	
	$curr_size = sizeof( $curr );
	if ( $curr_size < $max_rows && $curr_size < sizeof( $prev ) ) {
		$pos = 0;
		foreach ( $prev as $key => $hits ) {
			if ( !array_key_exists( $key, $curr ) ) {
				$curr[$key] = 0;
			}
			$pos++;
			if ( $pos >= $max_rows ) break;
		}
	}
	
	$curr_total = 0;
	foreach ( array_values( $curr ) as $hits ) {
		$curr_total += $hits;
	}
	$prev_total = 0;
	foreach ( array_values( $prev ) as $hits ) {
		$prev_total += $hits;
	}
	
	echo '<div class="grid6 table"><table>';
	echo '<thead><tr>';
	echo '<th class="first"><span class="text">'.( array_key_exists( $_field, $config->hit_fields ) ? $config->hit_fields[$_field] : $config->visit_fields[$_field] );
	echo '</span></th>';
	echo '<th class="center">%</th>';
	echo '<th class="center">&nbsp;</th>';
	echo '<th class="center last">&plusmn;</th>';
	echo '</tr></thead></table>'."\n";
	echo '<div class="tbody"><table><tbody>'."\n";
	
	if ( empty( $curr_data[$_field] ) && empty( $prev_data[$_field] ) ) {
		echo '<tr>';
		echo '<td class="first"><em>(none)</em></td>';
		echo '</tr>'."\n";
	}
	
	$pos = 0;
	foreach ( $curr as $key => $hits ) {
		$new_filters[$_field] = $key;
		
		if ( $curr_total > 0 ) {
			$curr_pct = $hits / $curr_total * 100;
		} else {
			$curr_pct = 0;
		}
		
		if ( array_key_exists( $key, $prev ) && $prev_total > 0 ) {
			$prev_pct = $prev[$key] / $prev_total * 100;
		} else {
			$prev_pct = 0;
		}
		
		echo '<tr>';
		// echo '<td>'.( $pos + 1 ).'</td>';
		echo '<td class="first"><span class="text">';
		if ( $_field == 'browser' && !$is_iphone ) {
			echo '<a class="toggle" title="" id="browser_'.preg_replace( '/[^a-z]/', '', mb_strtolower( $key ) );
			echo '" href="#">+</a> ';
		}
		echo '<a href="./'.filter_url( $new_filters ).'">';
		echo htmlspecialchars( $i18n->label( $_field, $key ) );
		echo '</a></span></td>';
		echo '<td class="center" title="'.$curr_date_label.'">'.format_number( $curr_pct ).'</td>';
		echo '<td class="center prev" title="'.$prev_date_label.'">'.format_number( $prev_pct ).'</td>';
		echo '<td class="center last">';
		if ( array_key_exists( $key, $prev ) ) {
			if ( $prev_pct == $curr_pct ) {
				echo '—';
			} elseif ( $curr_pct > $prev_pct ) {
				echo '<span class="up">↑ '.format_number( $curr_pct - $prev_pct ).'</span>';
			} elseif ( $prev_pct > $curr_pct ) {
				echo '<span class="dn">↓ '.format_number( $prev_pct - $curr_pct ).'</span>';
			}
		} else {
			echo '<span class="up">new</span>';
		}
		echo '</td>';
		echo '</tr>'."\n";
		
		if ( $_field == 'browser' && !$is_iphone && ( array_key_exists( $key, $curr_data['version'] ) || array_key_exists( $key, $prev_data['version'] ) ) ) {
			if ( !array_key_exists( $key, $curr_data['version'] ) ) {
				$curr_data['version'][$key] = array();
				foreach ( $prev_data['version'][$key] as $key2 => $hits2 ) {
					$curr_data['version'][$key][$key2] = 0;
				}
			}
			
			foreach ( $curr_data['version'][$key] as $key2 => $hits2 ) {
				if ( $curr_total > 0 ) {
					$curr_pct = $hits2 / $curr_total * 100;
				} else {
					$curr_pct = 0;
				}
				
				if ( array_key_exists( $key, $prev_data['version'] ) && array_key_exists( $key2, $prev_data['version'][$key] ) && $prev_total > 0 ) {
					$prev_pct = $prev_data['version'][$key][$key2] / $prev_total * 100;
				} else {
					$prev_pct = 0;
				}
				
				echo '<tr class="detail detail_browser_'.preg_replace( '/[^a-z]/', '', mb_strtolower( $key ) ).'">';
				// echo '<td>'.( $pos + 1 ).'</td>';
				echo '<td class="first"><span class="text">'.htmlspecialchars( $i18n->label( 'version', $key2 ) ).'</span></td>';
				echo '<td class="center">'.format_number( $curr_pct ).'</td>';
				echo '<td class="center prev">'.format_number( $prev_pct ).'</td>';
				echo '<td class="center last">';
				if ( array_key_exists( $key, $prev_data['version'] ) && array_key_exists( $key2, $prev_data['version'][$key] ) ) {
					if ( $prev_pct == $curr_pct ) {
						echo '—';
					} elseif ( $curr_pct > $prev_pct ) {
						echo '<span class="up">↑ '.format_number( $curr_pct - $prev_pct ).'</span>';
					} elseif ( $prev_pct > $curr_pct ) {
						echo '<span class="dn">↓ '.format_number( $prev_pct - $curr_pct ).'</span>';
					}
				} else {
					echo '<span class="up">new</span>';
				}
				echo '</td>';
				echo '</tr>'."\n";
			}
		}
		
		$pos++;
		if ( $pos >= $max_rows ) break;
	}
	
	// while ( $pos < 10 ) {
	// 	echo '<tr>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '<td>&nbsp;</td>';
	// 	echo '</tr>'."\n";
	// 	$pos++;
	// }

	echo '</tbody></table></div></div>'."\n";
}

function map() {
	global $curr_data;
	
	// map
	$first_value = -1;
	$country_keys = array();
	$country_values = array();
	foreach ( $curr_data['country'] as $key => $value ) {
		if ( $key == '' ) {
			continue;
		}
	
		$country_keys[] = $key;
	
		if ( $first_value == -1 ) {
			$first_value = $value;
		}
		$rounded_value = round( $value / $first_value * 100 );
		$country_values[] = $rounded_value;
	}
	echo '<div class="grid6">';
	echo '<h3>Map</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=330x170&amp;cht=t&amp;chtm=world';
	echo '&amp;chco=ffffff,eeeeee,333333';
	echo '&amp;chld='.implode( '', array_slice( $country_keys, 0, 20 ) );
	echo '&amp;chd=t:'.implode( ',', array_slice( $country_values, 0, 20 ) );
	echo '&amp;chf=bg,s,ffffff';
	echo '" alt="" width="330" height="170" style="margin:14px 5px" />';
	echo '</div></div>';
}

function sources() {
	global $curr_data, $prev_data;
	
	// sources
	$curr_total_visits = max( 1, array_sum( $curr_data['source'] ) );
	$curr_search_visits = $curr_data['source']['search_terms'] / $curr_total_visits * 100;
	$curr_direct_visits = $curr_data['source']['direct'] / $curr_total_visits * 100;
	$curr_referrer_visits = $curr_data['source']['referrer'] / $curr_total_visits * 100;

	$prev_total_visits = max( 1, array_sum( $prev_data['source'] ) );
	$prev_search_visits = $prev_data['source']['search_terms'] / $prev_total_visits * 100;
	$prev_direct_visits = $prev_data['source']['direct'] / $prev_total_visits * 100;
	$prev_referrer_visits = $prev_data['source']['referrer'] / $prev_total_visits * 100;

	echo '<div class="grid6">';
	echo '<h3>Visit source</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=340x160';
	echo '&amp;chd=t:'.to1dp( $prev_search_visits ).','.to1dp( $prev_direct_visits ).','.to1dp( $prev_referrer_visits );
	echo '|'.to1dp( $curr_search_visits ).','.to1dp( $curr_direct_visits ).','.to1dp( $curr_referrer_visits );
	echo '&amp;chl=';//.urlencode( '('.format_number( $prev_search_visits ).'%)' );
	echo '|';//.urlencode( '('.format_number( $prev_direct_visits ).'%)' );
	echo '|';//.urlencode( '('.format_number( $prev_referrer_visits ).'%)' );
	echo '|Search '.urlencode( '('.format_number( $curr_search_visits ).'%)' );
	echo '|Direct '.urlencode( '('.format_number( $curr_direct_visits ).'%)' );
	echo '|Referred '.urlencode( '('.format_number( $curr_referrer_visits ).'%)' );
	echo '&amp;chp=4.7124';
	// echo '&amp;chp=2.3662';
	echo '&amp;chco=CCCCCC,333333';
	echo '&amp;cht=pc';
	echo '" alt="" title="" width="340" height="160" style="margin:19px 0" />';
	echo '</div></div>';
}

function resolutions() {
	global $curr_data, $prev_data;

	// resolutions
	$max_hits = reset( array_values( $curr_data['resolution'] ) );
	$widths = array( 0 );
	$heights = array( 0 );
	$amounts = array( 0 );
	
	foreach ( $curr_data['resolution'] as $key => $value ) {
		$amount = round( ( $value / $max_hits ) * 100 );
		if ( $amount < 5 ) break;
		
		if ( preg_match( "/[0-9]+x[0-9]+/", $key ) && list( $w, $h ) = @explode( 'x', $key, 2 ) ) {
			$widths[] = intval( $w );
			$heights[] = intval( $h );
			$amounts[] = $amount;
		} else {
			$widths[] = 0;
			$heights[] = 0;
			$amounts[] = $amount;
		}
	}
	
	$width_labels = array_unique( $widths );
	sort( $width_labels );
	$height_labels = array_unique( $heights );
	sort( $height_labels );

	echo '<div class="grid6">';
	echo '<h3>Screen sizes</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=340x160';
	echo '&amp;chd=t:';
	echo implode( ',', $widths ).'|'.implode( ',', $heights ).'|'.implode( ',', $amounts );
	// echo '&amp;chp=4.7124';
	// echo '&amp;chp=2.3662';
	// echo '&amp;chco=CCCCCC,333333';
	echo '&amp;chds=0,'.max( $widths ).',0,'.max( $heights ),',0,100';
	echo '&amp;chxt=x,y';
	// echo '&amp;chxp=0,'.implode( ',', $width_labels ).'|1,'.implode( ',', $height_labels );
	echo '&amp;chxr=0,0,'.max( $widths ).'|1,0,'.max( $heights );
	echo '&amp;cht=s';
	echo '&amp;chxs=0,333333,10,0,lt|1,333333,10,1,lt';
	echo '" alt="" title="" width="340" height="160" style="margin:19px 0" />';
	echo '</div></div>';
}

function chart_hits() {
	global $filters, $curr_data, $prev_data, $curr_date_label, $prev_date_label;
	
	$curr = $curr_data['hits'];
	$prev = $prev_data['hits'];
	
	ksort( $curr );
	ksort( $prev );
	
	$curr_total = 0;
	foreach ( $curr as $key => $hits ) {
		$curr_total += $hits;
	}
	
	$prev_total = 0;
	foreach ( $prev as $key => $hits ) {
		$prev_total += $hits;
	}
	
	$curr_points = array();
	$curr_labels = array();
	$running_total = 0;
	foreach ( $curr as $key => $hits ) {
		$value = $hits / $curr_total * 100;
		$curr_points[] = to1dp( $value );
		$curr_labels[] = urlencode( $key.' ('.format_number( $value ).'%)' );
		$running_total += $value;
		if ( $running_total > 90 && $running_total < 100 ) {
			$value = 100 - $running_total;
			$curr_points[] = to1dp( $value );
			$curr_labels[] = urlencode( ( $key + 1 ).'+ ('.format_number( $value ).'%)' );
			break;
		}
	}
	
	$prev_points = array();
	$prev_labels = array();
	$running_total = 0;
	foreach ( $prev as $key => $hits ) {
		$value = $hits / $prev_total * 100;
		$prev_points[] = to1dp( $value );
		$prev_labels[] = '';//urlencode( $key.' ('.format_number( $value ).'%)' );
		$running_total += $value;
		if ( $running_total > 90 && $running_total < 100 ) {
			$value = 100 - $running_total;
			$prev_points[] = to1dp( $value );
			$prev_labels[] = '';//urlencode( ( $key + 1 ).'+ ('.format_number( $value ).'%)' );
			break;
		}
	}
	
	if ( empty( $prev_points ) ) {
		$prev_points[] = 0;
	}
	if ( empty( $curr_points ) ) {
		$curr_points[] = 0;
	}
	
	echo '<div class="grid6" id="hits">';
	echo '<h3>Pages viewed</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=340x198';
	echo '&amp;chd=t:'.implode( ',', $prev_points ).'|'.implode( ',', $curr_points );
	echo '&amp;chl='.implode( '|', $prev_labels ).'|'.implode( '|', $curr_labels );
	echo '&amp;chp=4.7124';
	echo '&amp;chco=CCCCCC,333333';
	echo '&amp;cht=pc';
	echo '&amp;chma=0,0,10,10';
	// echo '&amp;chtt='.urlencode( date_label( $filters ) );
	echo '" alt="'.$curr_date_label.'" title="'.$curr_date_label.'" width="340" height="198" />';
	echo '</div></div>';
}

function chart_hours() {
	global $filters, $curr_data, $prev_data;
	
	$curr = $curr_data['hr'];
	$prev = $prev_data['hr'];
	
	$curr_max_hr = 23;
	if ( $filters['dy'] == date( 'j' ) && $filters['mo'] == date( 'n' ) && $filters['yr'] == date( 'Y' ) ) {
		$curr_max_hr = date( 'G' );
	}
	$max_hr = max( $curr_max_hr, 23 );
	
	$curr_points = array();
	$max = 0;
	$min = -1;
	$max_index = 0;
	$min_index = 0;
	$prev_points = array();
	
	for ( $hr=0; $hr<=$max_hr; $hr++ ) {
		if ( array_key_exists( $hr, $curr ) ) {
			$curr_points[] = $curr[$hr];
			if ( $curr[$hr] > $max ) {
				$max = $curr[$hr];
				$max_index = $hr;
			}
			if ( $curr[$hr] < $min || $min == -1 ) {
				$min = $curr[$hr];
				$min_index = $hr;
			}
		} elseif ( $hr <= $curr_max_hr ) {
			$curr_points[] = 0;
			if ( $min != 0 /*&& $hr <= $curr_max_hr*/ ) {
				$min = 0;
				$min_index = $hr;
			}
		} else {
			$curr_points[] = -1;
		}
	}
	$curr_max = $max;
	$curr_min = $min;
	
	for ( $hr=0; $hr<=23; $hr++ ) {
		if ( array_key_exists( $hr, $prev ) ) {
			$prev_points[] = $prev[$hr];
			if ( $prev[$hr] > $max ) {
				$max = $prev[$hr];
			}
			if ( $prev[$hr] < $min || $min == -1 ) {
				$min = $prev[$hr];
			}
		} else {
			$prev_points[] = 0;
			$min = 0;
		}
	}
	
	$min = max( 0, floor( $min - ( $max * 0.05 ) ) );
	$max = max( 5, ceil( $max * 1.05 ) );
	
	echo '<div class="grid12">';
	echo '<h3>Hits ∕ hour</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=700x198';
	echo '&amp;chd=t:'.implode( ',', $prev_points ).'|'.implode( ',', $curr_points );
	echo '&amp;chds='.$min.','.$max.','.$min.','.$max;
	echo '&amp;chco=CCCCCC,333333';
	echo '&amp;chls=1|2.5';
	echo '&amp;chma=0,0,10,0';
	echo '&amp;chxt=x,y';
	echo '&amp;chxs=0,333333,10,0,t|1,333333,10,1,t';
	// echo '&amp;chm=o,00CC00,1,'.$max_index.',7|o,CC0000,1,'.$min_index.',7';
	/* B,EEEEEE,0,0,0| */ echo '&amp;chm=o,00CC00,1,'.$max_index.',7';//|t'.$curr_max.',009900,1,'.$max_index.',10';
	if ( $min_index != $max_index ) {
		echo '|o,CC0000,1,'.$min_index.',7';//|t'.$curr_min.',990000,1,'.$min_index.',10';
	}
	echo '&amp;chxl=0:|00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23';
	echo '&amp;chxr=1,'.$min.','.$max;
	echo '&amp;cht=lc';
	echo '" alt="" width="700" height="198" />';
	echo '</div></div>';
}

function chart_days() {
	global $filters, $curr_data, $prev_data;
	
	$curr = ( array_key_exists( 'dy', $curr_data ) ) ? $curr_data['dy'] : array();
	$prev = ( array_key_exists( 'dy', $prev_data ) ) ? $prev_data['dy'] : array();
	
	$curr_max_dy = days_in_month( $filters['mo'], $filters['yr'] );
	if ( $filters['mo'] == date( 'n' ) && $filters['yr'] == date( 'Y' ) ) {
		$curr_max_dy = date( 'j' );
	}
	$prev_max_dy = days_in_month( $filters['mo'] - 1, $filters['yr'] );
	$max_dy = max( $curr_max_dy, $prev_max_dy );
	
	$curr_points = array();
	$max = 0;
	$min = -1;
	$max_index = 1;
	$min_index = 1;
	$prev_points = array();
	
	for ( $dy=1; $dy<=$max_dy; $dy++ ) {
		if ( array_key_exists( $dy, $curr ) ) {
			$curr_points[] = $curr[$dy];
			if ( $curr[$dy] > $max ) {
				$max = $curr[$dy];
				$max_index = $dy;
			}
			if ( $curr[$dy] < $min || $min == -1 ) {
				$min = $curr[$dy];
				$min_index = $dy;
			}
		} elseif ( $dy <= $curr_max_dy ) {
			$curr_points[] = 0;
			if ( $min != 0 /*&& $dy <= $curr_max_dy*/ ) {
				$min = 0;
				$min_index = $dy;
			}
		} else {
			$curr_points[] = -1;
		}
	}
	$curr_max = $max;
	$curr_min = $min;
	
	for ( $dy=1; $dy<=$max_dy; $dy++ ) {
		if ( array_key_exists( $dy, $prev ) ) {
			$prev_points[] = $prev[$dy];
			if ( $prev[$dy] > $max ) {
				$max = $prev[$dy];
			}
			if ( $prev[$dy] < $min || $min == -1 ) {
				$min = $prev[$dy];
			}
		} elseif ( $dy <= $prev_max_dy ) {
			$prev_points[] = 0;
		} else {
			$prev_points[] = -1;
		}
	}
	
	$scale_min = max( 0, floor( $min - ( $max * 0.05 ) ) );
	$scale_max = max( 5, ceil( $max * 1.05 ) );
	
	echo '<div class="grid12">';
	echo '<h3>Hits ∕ day</h3>';
	echo '<div class="tbody">';
	echo '<img src="http://chart.apis.google.com/chart?';
	echo 'chs=700x198';
	echo '&amp;chd=t:'.implode( ',', $prev_points ).'|'.implode( ',', $curr_points );
	echo '&amp;chds='.$scale_min.','.$scale_max.','.$scale_min.','.$scale_max;
	echo '&amp;chco=CCCCCC,333333';
	echo '&amp;chls=1|2.5';
	echo '&amp;chma=0,0,10,0';
	echo '&amp;chxt=x,y';
	echo '&amp;chxs=0,333333,10,0,t|1,333333,10,1,t';
	/* B,EEEEEE,0,0,0| */ echo '&amp;chm=o,00CC00,1,'.( $max_index - 1 ).',7';//|t'.$curr_max.',009900,1,'.( $max_index - 1 ).',10';
	if ( $min_index != $max_index ) {
		echo '|o,CC0000,1,'.( $min_index - 1 ).',7';//|t'.$curr_min.',990000,1,'.( $min_index - 1 ).',10';
	}
	echo '&amp;chxl=0:';
	for ( $dy=1; $dy<=$max_dy; $dy++ ) {
		echo '|'.$dy;
	}
	echo '&amp;chxr=1,'.$scale_min.','.$scale_max;
	echo '&amp;cht=lc';
	echo '" alt="" width="700" height="198" />';
	echo '</div></div>';
}

function calendar_widget() {
	global $filters;
	
	$start_offset = gmdate( 'w', gmmktime( 12, 0, 0, $filters['mo'], 1, $filters['yr'] ) );
	$days_in_month = days_in_month( $filters['mo'], $filters['yr'] );
	$table = array();
	for ( $d = 1; $d <= $days_in_month; $d++ ) {
		$this_w = intval( floor( ( $d + $start_offset - 1 ) / 7 ) );
		$target_w = $this_w /*% 5*/;
		if ( !array_key_exists( $target_w, $table ) ) {
			$table[ $target_w ] = array();
			for ( $x=0; $x<7; $x++ )
				$table[ $target_w ][ $x ] = 0;
		}
		$table[ $target_w ][ $d + $start_offset - 1 - ( $this_w * 7 ) ] = $d;
	}
	
	// for ( $w=0; $w<sizeof( $table ); $w++ ) {
	// 	ksort( $table[$w] );
	// }
	// 
	// ksort( $table );
	
	$prev = prev_period( $filters, true );
	$prev_link = '<a href="./'.filter_url( $prev ).'" title="'.date_label( $prev, false ).'">&larr;</a>';
	
	if ( $filters['yr'] < date( 'Y' ) || $filters['mo'] < date( 'n' ) ) {
		$next = next_period( $filters, true );
		$next_link = '<a href="./'.filter_url( $next ).'" title="'.date_label( $next, false ).'">&rarr;</a>';
	} else {
		$next_link = '';
	}
	
	echo '<table class="calendar" summary="Monthly calendar">'."\n";
	echo '<thead>';
	echo '<tr>';
	echo '<th>'.$prev_link.'</th>';
	echo '<th colspan="5">';
	if ( array_key_exists( 'dy', $filters ) ) {
		echo '<a class="thismonth" href="./'.filter_url( next_period( $prev ) ).'" title="';
		echo date_label( $filters, false ).'">'.sp2nb( date_label( $filters, false ) ).'</a>';
	} else {
		echo sp2nb( date_label( $filters, false ) );
	}
	echo '</th>';
	echo '<th>'.$next_link.'</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tr>'."\n";
	echo '<th abbr="Sunday">S</th>';
	echo '<th abbr="Monday">M</th>';
	echo '<th abbr="Tuesday">T</th>';
	echo '<th abbr="Wednesday">W</th>';
	echo '<th abbr="Thursday">T</th>';
	echo '<th abbr="Friday">F</th>';
	echo '<th abbr="Saturday">S</th>';
	echo "</tr>\n";
	
	$actual_dy = intval( date( 'd' ) );
	$actual_mo = intval( date( 'm' ) );
	$actual_yr = intval( date( 'Y' ) );
	
	$dy_filters = $filters;
	for ( $w=0; $w<sizeof( $table ); $w++ ) {
		echo '<tr>';
		for ( $d=0; $d<7; $d++ ) {
			if ( $table[$w][$d] == 0 ) {
				echo '<td>';
			} elseif ( array_key_exists( 'dy', $filters ) && $filters['dy'] == $table[$w][$d] ) {
				echo '<td class="selected">';
			} else {
				echo '<td class="dy">';
			}
			if ( $table[$w][$d] > 0 ) {
				if ( $filters['yr'] == $actual_yr && $filters['mo'] == $actual_mo && $table[$w][$d] > $actual_dy ) {
					echo $table[$w][$d];
				} else {
					$dy_filters['dy'] = $table[$w][$d];
					echo '<a href="./'.filter_url( $dy_filters ).'" title="';
					echo date_label( $filters, $table[$w][$d] ).'">'.$table[$w][$d].'</a>';
				}
			}
			echo '</td>';
		}
		echo '</tr>'."\n";
	}
	echo '</table>'."\n";
}

function days_in_month( $_mo, $_yr ) {
	return date( 'j', mktime( 12, 0, 0, $_mo + 1, 0, $_yr ) );
}
