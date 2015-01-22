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

page_head();

echo '<h2 id="title" class="grid16">'.$i18n->hsc( 'paths', 'title' ).'</h2>'."\n";

// main

echo '<div id="main" class="grid16">';

// side

if ( !$is_handheld ) {
	echo '<div id="side" class="grid4"><div id="sideinner" class="grid3 first">'."\n";
	echo '<p class="first">'.$i18n->hsc( 'paths', 'side' ).'</p>'."\n";
	echo '</div></div>'."\n";
}

echo '<div id="content" class="grid12"><div class="grid12">';

// get requests

$page_size = 50;
$offset = 0;

if ( array_key_exists( 'offset', $_GET ) ) {
	$offset = abs( intval( $_GET['offset'] ) );
	$offset = round( $offset / $page_size ) * $page_size;
}

$query = 'SELECT * FROM `'.$slimstat->esc( $config->db_database ).'`.`'.$slimstat->esc( $config->tbl_visits ).'`';
$query .= ' ORDER BY `start_yr` DESC, `start_mo` DESC, `start_dy` DESC, `start_hr` DESC, `start_mi` DESC LIMIT '.$offset.','.$page_size;

$visits = array();
if ( $result = @mysql_query( $query, $connection ) ) {
	while ( $assoc = @mysql_fetch_assoc( $result ) ) {
		$visits[] = $assoc;
	}
}

// draw table

$col1_w = ( $is_handheld ) ? 240 : 230;
$col2_w = ( $is_handheld ) ?  88 : 75;
$col3_w = ( $is_handheld ) ? 137 : 120;
$col4_w = ( $is_handheld ) ? 115 : 145;
$col5_w = ( $is_handheld ) ?  88 : 100;

echo '<table><thead>'."\n";
echo '<tr><th class="first" colspan="2" style="width:'.$col1_w.'px">'.$i18n->hsc( 'titles', 'remote_ip' ).'/'.$i18n->hsc( 'titles', 'resource' ).'</th>'."\n";
echo '<th class="center" style="width:'.$col2_w.'px">'.$i18n->hsc( 'paths', 'when' ).'</th>'."\n";
if ( !$is_handheld ) {
	echo '<th class="center" style="width:'.$col3_w.'px">'.$i18n->hsc( 'titles', 'browser' ).'</th>'."\n";
	echo '<th class="center" style="width:'.$col4_w.'px">'.$i18n->hsc( 'titles', 'platform' ).'</th>'."\n";
	echo '<th class="center last" style="width:'.$col5_w.'px">'.$i18n->hsc( 'titles', 'country' ).'</th>';
}
echo '</tr></thead></table>'."\n";

echo '<div class="tbody"><table><tbody>'."\n";

$prev_dy = '';
foreach ( $visits as $visit ) {
	$start_ts = -1;
	$end_ts = -1;
	
	$hits = explode( "\n", $visit['resource'] );
	foreach ( $hits as $hit ) {
		if ( $hit == '' ) {
			continue;
		}
		
		foreach ( $hits as $hit ) {
			if ( $hit == '' ) {
				continue;
			}
			
			@list( $yr, $mo, $dy, $hr, $mi, $sc, $resource, $title ) = explode( ' ', $hit, 8 );
			$local_time = $slimstat->local_time_fields( array( 'yr' => $yr, 'mo' => $mo, 'dy' => $dy, 'hr' => $hr, 'mi' => $mi, 'sc' => $sc ) );
			$dt = mktime( $local_time['hr'], $local_time['mi'], $local_time['sc'], $local_time['mo'], $local_time['dy'], $local_time['yr'] );
			
			if ( $start_ts == -1 ) {
				$start_ts = $dt;
			}
			if ( $dt > $end_ts ) {
				$end_ts = $dt;
			}
		}
	}
	
	$dy_label = strftime( '%e %b', $start_ts );
	
	$start_ts = date( 'H:i', $start_ts );
	$end_ts = date( 'H:i', $end_ts );
	
	echo '<tr>'."\n".'<td class="first accent" colspan="2" style="width:'.$col1_w.'px; max-width:'.$col1_w.'px">';
	echo '<a class="external" title="'.str_replace( '%i', $visit['remote_ip'], $config->whoisurl ).'" href="'.str_replace( '%i', $visit['remote_ip'], $config->whoisurl ).'" rel="nofollow">&rarr;</a> ';
	echo htmlspecialchars( $visit['remote_ip'] ).'</td>'."\n";
	echo '<td class="center accent" style="width:'.$col2_w.'px; max-width:'.$col2_w.'px">'.$dy_label.'</td>'."\n";
	if ( $is_handheld ) {
		echo '</tr><tr>';
	}
	echo '<td class="center accent" style="width:'.$col3_w.'px; max-width:'.$col3_w.'px">'.htmlspecialchars( $visit['browser'] );
	if ( $visit['version'] != $i18n->data['core']['indeterminable'] ) {
		echo ' '.htmlspecialchars( $visit['version'] );
	}
	echo '</td>'."\n".'<td class="center accent" style="width:'.$col4_w.'px; max-width:'.$col4_w.'px">'.htmlspecialchars( $visit['platform'] ).'</td>'."\n";
	echo '<td class="center last accent" style="width:'.$col5_w.'px; max-width:'.$col5_w.'px">'.htmlspecialchars( $i18n->label( 'country', $visit['country'] ) ).'</td></tr>'."\n";
	
	$prev_ts = '';
	foreach ( $hits as $hit ) {
		if ( $hit == '' ) {
			continue;
		}
		
		@list( $yr, $mo, $dy, $hr, $mi, $sc, $resource, $title ) = explode( ' ', $hit, 8 );
		$local_time = $slimstat->local_time_fields( array( 'yr' => $yr, 'mo' => $mo, 'dy' => $dy, 'hr' => $hr, 'mi' => $mi, 'sc' => $sc ) );
		$dt = mktime( $local_time['hr'], $local_time['mi'], $local_time['sc'], $local_time['mo'], $local_time['dy'], $local_time['yr'] );
		
		echo '<tr>'."\n".'<td class="first" colspan="2"><span class="text truncate">';
		echo '<a href="'.$resource.'" class="external"';
		echo ' title="'.htmlspecialchars( $resource ).'">&rarr;</a>';
		echo '<a href="./'.filter_url( array( 'resource' => $resource ) );
		echo '" title="'.htmlspecialchars( $resource ).'">';
		if ( $title != '' ) {
			echo htmlspecialchars( $title );
		} else {
			echo htmlspecialchars( $resource );
		}
		echo '</a></span></td>'."\n";
		$ts_label = strftime( '%H:%M', $dt );
		if ( $ts_label != $prev_ts ) {
			echo '<td class="center">'.$ts_label.'</td>'."\n";
		} else {
			echo '<td class="center">&nbsp;</td>'."\n";
		}
		
		if ( $prev_ts == '' && $visit['referrer'] != '' && !$is_handheld ) {
			echo '<td colspan="3" class="right last">';
			echo '<a href="'.$visit['referrer'].'" class="external" rel="nofollow"';
			echo ' title="'.htmlspecialchars( $visit['referrer'] ).'">&rarr;</a>';
			echo '<span class="text truncate">';
			if ( $visit['search_terms'] != '' ) {
				echo $i18n->hsc( 'core', 'open_quote' );
				echo '<a href="./'.filter_url( array( 'search_terms' => $visit['search_terms'] ) );
				echo '" title="'.htmlspecialchars( $visit['search_terms'] ).'"';
				echo '>'.htmlspecialchars( $visit['search_terms'] ).'</a>';
				echo $i18n->hsc( 'core', 'close_quote' );
			} else {
				echo '<a href="./'.filter_url( array( 'domain' => $visit['domain'] ) );
				echo '" title="'.htmlspecialchars( $visit['domain'] ).'"';
				echo '>'.htmlspecialchars( $visit['domain'] ).'</a>';
			}
			echo '</span></td>'."\n";
		} elseif ( !$is_handheld ) {
			echo '<td colspan="3" class="last">&nbsp;</td>'."\n";
		}
		echo '</tr>'."\n";
		
		$prev_ts = $ts_label;
	}
	
	$prev_dy = $dy_label;
}

echo '</tbody></table></div>'."\n";

echo '</div></div></div>'."\n";

?>
<script type="text/javascript">
function resizePathsTbody() {
	var viewportHeight = window.innerHeight ? window.innerHeight : $(window).height();
	var footerHeight = $('#foot').height();
	var tbodyOffset = $('.tbody').offset().top;
	$('.tbody').css('height', Math.max(198, viewportHeight - tbodyOffset - footerHeight - 42) + 'px');
}

$(function() {
	resizePathsTbody();
	$('.tbody').scroll(function() {
		var jthis = $(this);
		var scrollTop = jthis.scrollTop();
		var clientHeight = jthis.height();
		var scrollHeight = jthis.find('table').height();
		if (scrollTop + clientHeight == scrollHeight && offset < pageSize * 10) {
			offset += pageSize;
			$.get('./?page=paths&offset=' + offset, function(data) {
				var jdata = $(data).find('.tbody table > *');
				$('.tbody table').append(jdata);
			});
		}
	});
});
$(window).resize(function() {
	resizePathsTbody();
});

var offset = <?= $offset; ?>;
var pageSize = <?= $page_size; ?>;
</script>
<?php

page_foot();
