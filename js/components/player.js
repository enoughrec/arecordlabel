/**
 * @jsx React.DOM
 */


var React = require('react');
var path = require('path');
var play = require('play-audio');

var bus = require('../bus');



var Player = React.createClass({
	getInitialState: function(){
		var playerWidget = play([],document.body).autoplay();
		playerWidget.volume(0.8);

		playerWidget.on('play', this.togglePlayState.bind(this));
		playerWidget.on('ended', this.advance.bind(this));

		return {
			widget: playerWidget,
			playing: false,
			position: 0,
			playlist: [],
			release: false
		};
	},
	componentWillMount: function(){
		bus.on('queue', this.queue.bind(this));
	},
	queue: function(release){
		var tracks = release.get('tracks');

		this.state.release = release;
		this.state.playlist = tracks;
		this.state.position = 0;
		this.play();
	},
	play: function(){
		var trackNumber = this.state.position;
		this.state.widget.src(this.state.playlist[trackNumber]);
		
	},
	togglePlayState: function(playing){

		if (playing) {
			this.state.currentTrack = path.basename(this.state.widget.src());
			this.state.playing = true;
		} else {
			this.state.playing = false;
		}
		this.forceUpdate();
	},
	advance: function(fwd){
		var mod = fwd ? 1 : -1;
		this.state.position = this.state.position + mod;
		if (this.state.position > this.state.playlist.length - 1) {
			this.state.position = 0;
		}
		else if (this.state.position < 0){
			this.state.position = this.state.playlist.length - 1;	
		}
		this.play();
	},
	render: function(){
		var trackName = this.state.playing ? this.state.currentTrack : 'nothing playing';

		return (
			<div className="player">
				<span className="controls">
		            <span ctrl="back" className="fa fontawesome-backward"></span>
		            <span ctrl="stop" className="fa fontawesome-stop"></span>
		            <span id="play-state" ctrl="play" className={this.playing ? "fa fontawesome-pause" : "fa fontawesome-play"}></span>
		            <span ctrl="forward" className="fa fontawesome-forward"></span>
		        </span>
		        <span className={this.state.playing ? 'now-playing' : 'now-playing nothing'}>{trackName}</span>
	        </div>
		);
	}
});


module.exports = Player;