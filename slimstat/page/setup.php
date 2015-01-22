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

$hits_tbl_fields = array(
	'remote_ip'    => 'varchar(15) collate utf8_bin default NULL',
	'country'      => 'char(2) collate utf8_bin default NULL',
	'region'       => 'varchar(255) collate utf8_bin default NULL',
	'city'         => 'varchar(255) collate utf8_bin default NULL',
	'organisation' => 'varchar(255) collate utf8_bin default NULL',
	'isp'          => 'varchar(255) collate utf8_bin default NULL',
	'language'     => 'varchar(255) collate utf8_bin default NULL',
	'domain'       => 'varchar(255) collate utf8_bin default NULL',
	'referrer'     => 'varchar(255) collate utf8_bin default NULL',
	'search_terms' => 'varchar(255) collate utf8_bin default NULL',
	'resource'     => 'varchar(255) collate utf8_bin default NULL',
	'title'        => 'varchar(255) collate utf8_bin default NULL',
	'platform'     => 'varchar(50) collate utf8_bin default NULL',
	'browser'      => 'varchar(50) collate utf8_bin default NULL',
	'version'      => 'varchar(15) collate utf8_bin default NULL',
	'resolution'   => 'varchar(10) collate utf8_bin default NULL',
	'mi'           => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'hr'           => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'dy'           => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'mo'           => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'yr'           => 'smallint(5) unsigned NOT NULL default \'0\'',
	'hits'         => 'int(10) unsigned NOT NULL default \'0\''
);

$visits_tbl_fields = array(
	'remote_ip'      => 'varchar(15) collate utf8_bin default NULL',
	'country'        => 'char(2) collate utf8_bin default NULL',
	'region'         => 'varchar(255) collate utf8_bin default NULL',
	'city'           => 'varchar(255) collate utf8_bin default NULL',
	'organisation'   => 'varchar(255) collate utf8_bin default NULL',
	'isp'            => 'varchar(255) collate utf8_bin default NULL',
	'language'       => 'varchar(255) collate utf8_bin default NULL',
	'domain'         => 'varchar(255) collate utf8_bin default NULL',
	'referrer'       => 'varchar(255) collate utf8_bin default NULL',
	'search_terms'   => 'varchar(255) collate utf8_bin default NULL',
	'start_resource' => 'varchar(255) collate utf8_bin default NULL',
	'end_resource'   => 'varchar(255) collate utf8_bin default NULL',
	'user_agent'     => 'varchar(255) collate utf8_bin default NULL',
	'platform'       => 'varchar(50) collate utf8_bin default NULL',
	'browser'        => 'varchar(50) collate utf8_bin default NULL',
	'version'        => 'varchar(15) collate utf8_bin default NULL',
	'resolution'     => 'varchar(10) collate utf8_bin default NULL',
	'start_mi'       => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'start_hr'       => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'start_dy'       => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'start_mo'       => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'start_yr'       => 'smallint(5) unsigned NOT NULL default \'0\'',
	'end_mi'         => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'end_hr'         => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'end_dy'         => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'end_mo'         => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'end_yr'         => 'smallint(5) unsigned NOT NULL default \'0\'',
	'hits'           => 'int(10) unsigned NOT NULL default \'0\'',
	'start_ts'       => 'int(10) unsigned NOT NULL default \'0\'',
	'end_ts'         => 'int(10) unsigned NOT NULL default \'0\'',
	'duration'       => 'int(11) NOT NULL default \'0\'',
	'resource'       => 'text collate utf8_bin'
);

$cache_tbl_fields = array(
	'remote_ip'      => 'varchar(15) collate utf8_bin default NULL',
	'country'        => 'char(2) collate utf8_bin default NULL',
	'region'         => 'varchar(255) collate utf8_bin default NULL',
	'city'           => 'varchar(255) collate utf8_bin default NULL',
	'organisation'   => 'varchar(255) collate utf8_bin default NULL',
	'isp'            => 'varchar(255) collate utf8_bin default NULL',
	'language'       => 'varchar(255) collate utf8_bin default NULL',
	'resource'       => 'varchar(255) collate utf8_bin default NULL',
	'platform'       => 'varchar(50) collate utf8_bin default NULL',
	'browser'        => 'varchar(50) collate utf8_bin default NULL',
	'version'        => 'varchar(15) collate utf8_bin default NULL',
	'resolution'     => 'varchar(10) collate utf8_bin default NULL',
	'search_terms'   => 'varchar(255) collate utf8_bin default NULL',
	'domain'         => 'varchar(255) collate utf8_bin default NULL',
	'referrer'       => 'varchar(255) collate utf8_bin default NULL',
	'start_resource' => 'varchar(255) collate utf8_bin default NULL',
	'end_resource'   => 'varchar(255) collate utf8_bin default NULL',
	'hits'           => 'int(10) unsigned NOT NULL default \'0\'',
	'tz'             => 'varchar(50) collate utf8_bin NOT NULL',
	'yr'             => 'smallint(5) unsigned NOT NULL default \'0\'',
	'mo'             => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'dy'             => 'tinyint(3) unsigned NOT NULL default \'0\'',
	'app_version'    => 'varchar(10) collate utf8_bin NOT NULL',
	'cache'          => 'longblob NOT NULL'
);

$steps = array(
	'Connect to database',
	'Create tables',
	'Finish'
);

function check_table_exists( $_table ) {
	global $config, $connection;
	
	$query = 'DESCRIBE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $_table ).'`';
	$result = @mysql_query( $query, $connection );
	return ( @mysql_num_rows( $result ) > 0 );
}

function check_table_fields_exist( $_table, $_fields ) {
	global $config, $connection;
	
	$missing_fields = array();

	$query = 'DESCRIBE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $_table ).'`';
	$result = @mysql_query( $query, $connection );
	if ( $result ) {
		$existing_fields = array();
		while ( $datum = @mysql_fetch_assoc( $result ) ) {
			$existing_fields[ $datum['Field'] ] = $datum;
		}
	
		foreach ( array_keys( $_fields ) as $field_name ) {
			if ( !array_key_exists( $field_name, $existing_fields ) ) {
				$missing_fields[] = $field_name;
			}
		}
	}
	
	return $missing_fields;
}

$step = -1;

$config_file_lines = file( realpath( dirname( dirname( __FILE__ ) ).'/_lib/config.php' ) );

page_head();

?>
<h2 id="title" class="grid16">Setting up SlimStat</h2>

<div id="main" class="grid16">

<div id="side" class="grid4"><div id="sideinner" class="grid3 first">
<p class="first"><?php echo sizeof( $steps ); ?> simple steps to complete your SlimStat installation.</p>

<ol>
<?php
foreach ( $steps as $step_name ) {
	echo '<li>'.htmlspecialchars( $step_name ).'</li>'."\n";
}
?>
</ol>
</div>
</div>

<div id="content" class="grid12 first">

<div class="grid12 first">
<?php

function step_header() {
	global $step, $steps;
	
	echo '<h3>Step '.( $step + 1 ).' of '.sizeof( $steps ).': '.$steps[$step].'</h3>'."\n";
}

//////////////////////////////////////////////////////////// Connect to database

if ( $step == -1 ) {
	$connection = SlimStat::connect();
	if ( !$connection ) {
		$step = array_search( 'Connect to database', $steps );
		step_header();
		
		echo '<p>SlimStat needs to be able to connect to your MySQL database.</p>'."\n";
		echo '<p>You can use SlimStat with an existing database, or create a new database if you prefer.</p>'."\n";
		echo '<p>In <tt>_lib/config.php</tt>, edit these variables:</p>'."\n";
		echo '<pre>';
		foreach ( $config_file_lines as $config_file_line ) {
			if ( strstr( $config_file_line, 'var $db_' ) ) {
				echo htmlspecialchars( trim( $config_file_line ) )."\n";
			}
		}
		echo '</pre>'."\n";
		echo '<p>When they match your connection settings, click the button below.</p>'."\n";
	}
}

////////////////////////////////////////////////////////////////// Create tables

if ( $step == -1 ) {
	$hidden_field_before = 'do_create';
	$hidden_field_after = 'done_create';
	$hits_table_exists = check_table_exists( $config->tbl_hits );
	$visits_table_exists = check_table_exists( $config->tbl_visits );
	$cache_table_exists = check_table_exists( $config->tbl_cache );
	$hits_table_missing = ( $hits_table_exists ) ? check_table_fields_exist( $config->tbl_hits, $hits_tbl_fields ) : array();
	$visits_table_missing = ( $visits_table_exists ) ? check_table_fields_exist( $config->tbl_visits, $visits_tbl_fields ) : array();
	$cache_table_missing = ( $cache_table_exists ) ? check_table_fields_exist( $config->tbl_cache, $cache_tbl_fields ) : array();
	
	if ( !$hits_table_exists || !$visits_table_exists || !$cache_table_exists ||
		 !empty( $hits_table_missing ) || !empty( $visits_table_missing ) || !empty( $cache_table_missing ) ) {
		$step = array_search( 'Create tables', $steps );
		step_header();
		
		if ( !isset( $_POST[$hidden_field_before] ) ) {
			
			if ( !$hits_table_exists || !$visits_table_exists || !$cache_table_exists ) {
				echo '<p>SlimStat needs to create three database tables to store its data. They will be called ';
				echo '<tt>'.$config->tbl_hits.'</tt>, <tt>'.$config->tbl_visits.'</tt> and <tt>'.$config->tbl_cache.'</tt>. ';
				echo 'To change this, edit these lines in <tt>_lib/config.php</tt>:</p>'."\n";
				echo '<pre>';
				foreach ( $config_file_lines as $config_file_line ) {
					if ( strstr( $config_file_line, 'var $tbl_' ) ) {
						echo htmlspecialchars( trim( $config_file_line ) )."\n";
					}
				}
				echo '</pre>'."\n";
				echo '<p>Click the button below to create the tables.</p>'."\n";
			} else { // tables exist, some fields don't
				echo '<p>SlimStat needs to add some fields to its database tables. This will not affect your existing data.</p>'."\n";
				echo '<p>Click the button below to create the fields.</p>'."\n";
			}
			$hidden_field = $hidden_field_before;
			
		} else {
			
			if ( !$hits_table_exists || !$visits_table_exists || !$cache_table_exists ) {
				$we_are_creating = 'tables';
			} else {
				$we_are_creating = 'fields';
			}
			
			// try to create the tables

			$hits_create_query = 'CREATE TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_hits ).'` (';
			foreach ( $hits_tbl_fields as $field_name => $field_type ) {
				$hits_create_query .= "\n\t".'`'.$field_name.'` '.$field_type.',';
			}
			$hits_create_query .= "\n\t".'KEY `ts` (`yr`,`mo`,`dy`,`hr`,`mi`)'.
			"\n".') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin';

			$visits_create_query = 'CREATE TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_visits ).'` (';
			foreach ( $visits_tbl_fields as $field_name => $field_type ) {
				$visits_create_query .= "\n\t".'`'.$field_name.'` '.$field_type.',';
			}
			$visits_create_query .= "\n\t".'KEY `start_ts` (`start_yr`,`start_mo`,`start_dy`,`start_hr`,`start_mi`),'.
			"\n\t".'KEY `end_ts` (`end_yr`,`end_mo`,`end_dy`,`end_hr`,`end_mi`)'.
			"\n".') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin';

			$cache_create_query = 'CREATE TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_cache ).'` (';
			foreach ( $cache_tbl_fields as $field_name => $field_type ) {
				$cache_create_query .= "\n\t".'`'.$field_name.'` '.$field_type.',';
			}
			$cache_create_query .= "\n\t".'KEY `ts` (`tz`,`yr`,`mo`,`dy`)'.
			"\n".') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin';
			
			$hits_alter_queries = array();
			foreach ( $hits_table_missing as $field_name ) {
				$hits_alter_query = 'ALTER TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_hits ).'`'.
				' ADD `'.$field_name.'` '.$hits_tbl_fields[$field_name];
				$hits_alter_queries[] = $hits_alter_query;
			}
			
			$visits_alter_queries = array();
			foreach ( $visits_table_missing as $field_name ) {
				$visits_alter_query = 'ALTER TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_visits ).'`'.
				' ADD `'.$field_name.'` '.$visits_tbl_fields[$field_name];
				$visits_alter_queries[] = $visits_alter_query;
			}
			
			$cache_alter_queries = array();
			foreach ( $cache_table_missing as $field_name ) {
				$cache_alter_query = 'ALTER TABLE `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_cache ).'`'.
				' ADD `'.$field_name.'` '.$cache_tbl_fields[$field_name];
				$cache_alter_queries[] = $cache_alter_query;
			}
			
			if ( !$hits_table_exists ) {
				@mysql_query( $hits_create_query, $connection );
			}
			if ( !$visits_table_exists ) {
				@mysql_query( $visits_create_query, $connection );
			}
			if ( !$cache_table_exists ) {
				@mysql_query( $cache_create_query, $connection );
			}
			foreach ( $hits_alter_queries as $hits_alter_query ) {
				@mysql_query( $hits_alter_query, $connection );
			}
			foreach ( $visits_alter_queries as $visits_alter_query ) {
				@mysql_query( $visits_alter_query, $connection );
			}
			foreach ( $cache_alter_queries as $cache_alter_query ) {
				@mysql_query( $cache_alter_query, $connection );
			}
						
			$hits_table_exists = check_table_exists( $config->tbl_hits );
			$visits_table_exists = check_table_exists( $config->tbl_visits );
			$cache_table_exists = check_table_exists( $config->tbl_cache );
			$hits_table_missing = ( $hits_table_exists ) ? check_table_fields_exist( $config->tbl_hits, $hits_tbl_fields ) : array();
			$visits_table_missing = ( $visits_table_exists ) ? check_table_fields_exist( $config->tbl_visits, $visits_tbl_fields ) : array();
			$cache_table_missing = ( $cache_table_exists ) ? check_table_fields_exist( $config->tbl_cache, $cache_tbl_fields ) : array();
			
			if ( $hits_table_exists && $visits_table_exists && $cache_table_exists &&
				 empty( $hits_table_missing ) && empty( $visits_table_missing ) && empty( $cache_table_missing ) ) {
				echo '<p>All required '.$we_are_creating.' have been created. Click the button below to continue to the next step.</p>'."\n";
			} else {
				echo '<p>SlimStat was unable to create the '.$we_are_creating.'. This is most likely because the MySQL user does not have permission to create '.$we_are_creating.'.</p>'."\n";
				echo '<p>You will need to create the '.$we_are_creating.' yourself, by executing the following queries.</p>'."\n";
				
				if ( !$hits_table_exists ) {
					echo '<p>To create the hits table:</p>'."\n";
					echo '<pre>'.htmlspecialchars( $hits_create_query ).';</pre>'."\n";
				}
				if ( !$visits_table_exists ) {
					echo '<p>To create the visits table:</p>'."\n";
					echo '<pre>'.htmlspecialchars( $visits_create_query ).';</pre>'."\n";
				}
				if ( !$cache_table_exists ) {
					echo '<p>To create the cache table:</p>'."\n";
					echo '<pre>'.htmlspecialchars( $cache_create_query ).';</pre>'."\n";
				}
				if ( !empty( $hits_table_missing ) ) {
					echo '<p>To alter the hits table:</p>'."\n";
					echo '<pre>';
					foreach ( $hits_alter_queries as $hits_alter_query ) {
						echo htmlspecialchars( $hits_alter_query ).';'."\n";
					}
					echo '</pre>'."\n";
				}
				if ( !empty( $visits_table_missing ) ) {
					echo '<p>To alter the visits table:</p>'."\n";
					echo '<pre>';
					foreach ( $visits_alter_queries as $visits_alter_query ) {
						echo htmlspecialchars( $visits_alter_query ).';'."\n";
					}
					echo '</pre>'."\n";
				}
				if ( !empty( $cache_table_missing ) ) {
					echo '<p>To alter the cache table:</p>'."\n";
					echo '<pre>';
					foreach ( $cache_alter_queries as $cache_alter_query ) {
						echo htmlspecialchars( $cache_alter_query ).';'."\n";
					}
					echo '</pre>'."\n";
				}
			}
			
			$hidden_field = $hidden_field_before;
			
		}
	}
}

///////////////////////////////////////////////////////////////////////// Finish

if ( $step == -1 ) {
	// remove old data from cache
	$query = 'DELETE FROM `'.SlimStat::esc( $config->db_database ).'`.`'.SlimStat::esc( $config->tbl_cache ).'` ';
	$query .= 'WHERE `app_version`<>\''.SlimStat::esc( SlimStat::app_version() ).'\'';
	@mysql_query( $query, $connection );
	
	$step = array_search( 'Finish', $steps );
	step_header();
	
	echo '<p>That’s it! Remove <tt>page/setup.php</tt> from the server and click the button below to start using SlimStat.</p>'."\n";
}

///////////////////////////////////////////////////////////// 'Next step' button

if ( $step < ( sizeof( $steps ) - 1 ) ) {
	echo '<form action="';
	echo ( isset( $_SERVER['REQUEST_URI'] ) ) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
	echo '" method="post">'."\n";
	echo '<p>';
	if ( isset( $hidden_field ) ) {
		echo '<input type="hidden" name="'.$hidden_field.'" value="1" />';
	}
	echo '<input type="submit" value="Next step" /></p>'."\n";
	echo '</form>'."\n";
} else {
	echo '<form action="./" method="post">'."\n";
	echo '<p><input type="submit" value="Finish" /></p>'."\n";
	echo '</form>'."\n";
}

?>
</div></div>

</div>
<?php

page_foot();