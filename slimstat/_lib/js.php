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

$base_href = dirname( $_SERVER['SCRIPT_NAME'] );

header( 'Content-type: text/javascript; charset=UTF-8' );

?>
function slimstatAddLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}

slimstatAddLoadEvent(function() {
	var ssSrc = '<?php echo $base_href; ?>/stats_js.php?ref=' + encodeURIComponent(document.referrer)
		+ '&url=' + encodeURIComponent(document.URL)
		+ '&res=' + encodeURIComponent(screen.width+'x'+screen.height)
		+ '&ttl=' + encodeURIComponent(document.title)
		+ '&ts=<?php echo time(); ?>';
	
	var ssImg = document.createElement('img');
	ssImg.setAttribute('id', 'slimstat<?php echo htmlspecialchars( SlimStat::app_version() ); ?>img');
	ssImg.setAttribute('src', ssSrc);
	ssImg.setAttribute('style', 'position:absolute;top:-10px;left:0');
	ssImg.setAttribute('width', '1');
	ssImg.setAttribute('height', '1');
	ssImg.setAttribute('alt', '');
	document.body.appendChild(ssImg);
});
