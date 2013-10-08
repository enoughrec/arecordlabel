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

page_head();

?>

<h2 id="title" class="grid16">Welcome to SlimStat</h2>
<div id="main" class="grid16">
<div id="side" class="grid4"></div>
<div id="content" class="grid12">
<div class="grid12">
<p class="first">Congratulations! You have successfully installed SlimStat.</p>
<p>To get started, you need to include SlimStat in your site’s code for each page where you would like stats to be counted.</p>
<p>You can use either JavaScript or PHP to do this.</p>
<p>When you have done it, you’ll need to wait for people to start visiting your site.</p>
<p>Enjoy viewing your stats!</p>

<h3>Using JavaScript</h3>
<p>Use code similar to this:</p>
<pre>&lt;script type="text/javascript" src="<?php echo dirname( $_SERVER['SCRIPT_NAME'] ); ?>/?js"&gt;&lt;/script&gt;</pre>

<h3>Using PHP</h3>
<p>Use code similar to one of these two examples:</p>
<pre>&lt;?php
@include_once( $_SERVER['DOCUMENT_ROOT'].'<?php echo dirname( $_SERVER['SCRIPT_NAME'] ); ?>/stats_include.php' );
?&gt;</pre>
<pre>&lt;?php
@include_once( '<?php echo dirname( dirname( __FILE__ ) ); ?>/stats_include.php' );
?&gt;</pre>
<p>Don’t use <em>both</em> examples, because then each hit will be counted twice.</p>

</div></div></div>

<?php

page_foot();