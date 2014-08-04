/**
 * @jsx React.DOM
 */


var React = require('react');
var Player = require('./player');


var Bottombar = React.createClass({
	render: function(){
		return (
			<div className="bottom-bar">
				<Player />
		        <span className="flattr">
		        	<iframe src="http://api.flattr.com/button/view/?button=compact&amp;uid=0&amp;url=http%3A%2F%2Fenoughrecords.scene.org&amp;language=&amp;hidden=0&amp;title=&amp;category=&amp;tags=&amp;description=" width="110" height="20" frameBorder="0" scrolling="no" border="0" marginHeight="0" marginWidth="0" allowTransparency="true"></iframe>
	        	</span>
			</div>
		);
	}
})

module.exports = Bottombar;