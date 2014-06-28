/**
 * @jsx React.DOM
 */


var React = require('react');

var Bottombar = React.createClass({
	render: function(){
		return (
			<div className="bottom-bar">
			<span className="controls">
	            <span ctrl="back" className="fa fontawesome-backward"></span>
	            <span ctrl="stop" className="fa fontawesome-stop"></span>
	            <span id="play-state" ctrl="play" className="fa fontawesome-play"></span>
	            <span ctrl="forward" className="fa fontawesome-forward"></span>
	        </span>
			</div>
		);
	}
})

module.exports = Bottombar;