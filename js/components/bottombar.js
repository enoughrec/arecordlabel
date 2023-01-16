/**
 * @jsx React.DOM
 */


var React = require('react');
var createReactClass = require('create-react-class');
var Player = require('./player');


var Bottombar = createReactClass({
	render: function(){
		return (
			<div className="bottom-bar">
				<Player />
			</div>
		);
	}
})

module.exports = Bottombar;
