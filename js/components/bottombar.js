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
			</div>
		);
	}
})

module.exports = Bottombar;
