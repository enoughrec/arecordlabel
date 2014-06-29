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

		playerWidget.on('play', this.togglePlayState);
		playerWidget.on('ended', this.advance);

		return {
			widget: playerWidget,
			playing: false,
			position: 0,
			playlist: [],
			release: false
		};
	},
	componentDidMount: function(){
		bus.on('queue', this.queue);
	},
	componentWillUnmount: function(){
		bus.off('queue', this.queue);
	},
	doControl: function(evt){
		if (this.state.release === false) {
			return;
		}
		var ctrl = evt.target.getAttribute('data-ctrl');
		switch (ctrl) {
			case 'stop':
				this.state.widget.pause().currentTime(0);
				this.togglePlayState(false);
				break;
			case 'play':
				if (this.state.playing) this.state.widget.pause() && this.togglePlayState(false);
				else this.state.widget.play();
				break;
			case 'forward':
				this.advance(true);
				break;
			case 'back':
				this.advance(false);
				break;
		}
	},
	queue: function(release){
		// release is a backbone model
		// the only place we're publishing these events for now
		// is on the detail page, but could also make a new model
		// which has a shuffled track selection based on a tag,
		// for example
		var tracks = release.get('tracks');

		// this is async, so have to give it a callback
		// would be nice with a .then(...) thing
		this.setState({
			release: release,
			playlist: tracks,
			position: 0,
			playing: false	
		}, this.play);
		
	},
	play: function(){
		var trackNumber = this.state.position;
		this.state.widget.src(this.state.playlist[trackNumber]);
		
	},
	togglePlayState: function(playing){

		if (playing) {
			this.setState({
				currentTrack: path.basename(this.state.widget.src()),
				playing: true
			});
			
		} else {
			this.setState({
				currentTrack: false,
				playing: false
			});
		}
	},
	advance: function(fwd){
		var mod = fwd ? 1 : -1;
		var next = this.state.position + mod;
		if (next > this.state.playlist.length - 1) {
			next = 0
		} else if (next < 0){
			next = this.state.playlist.length - 1
		} 

		this.setState({
			position: next
		},this.play);
		
	},
	render: function(){
		var trackName = this.state.playing ? this.state.currentTrack : 'nothing playing';

		return (
			<div className="player">
				<span className="controls" onClick={this.doControl}>
		            <span data-ctrl="back" className="fa fontawesome-backward"></span>
		            <span data-ctrl="stop" className="fa fontawesome-stop"></span>
		            <span data-ctrl="play" id="play-state" className={this.state.playing ? "fa fontawesome-pause" : "fa fontawesome-play"}></span>
		            <span data-ctrl="forward" className="fa fontawesome-forward"></span>
		        </span>
		        <span className={this.state.playing ? 'now-playing' : 'now-playing nothing'}>{trackName}</span>
	        </div>
		);
	}
});


module.exports = Player;