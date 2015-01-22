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

function render_page() {
	global $i18n;
	
	page_head();
	
	?>
<h2 id="title" class="grid16"><?php echo $i18n->hsc( 'login', 'title' ); ?></h2>

<div id="main" class="grid16">

<div id="side" class="grid4"><div id="sideinner" class="grid3">
<p class="first"></p>
</div>
</div>

<div id="content" class="grid12">

<form action="./" method="post" class="grid6">
<h3><?php echo $i18n->hsc( 'login', 'please_login' ); ?></h3>

<fieldset class="tbody">
<p><label for="username"><?php echo $i18n->hsc( 'login', 'username' ); ?></label><input type="text" name="username" value="" tabindex="1" class="input" /></p>

<p><label for="password"><?php echo $i18n->hsc( 'login', 'password' ); ?></label><input type="password" name="password" value="" tabindex="2" class="input" /></p>

<p><input type="submit" value="<?php echo $i18n->hsc( 'login', 'submit' ); ?>" tabindex="3" /></p>
</fieldset>
</form>

</div>

</div>
<?php
	
	page_foot();
}