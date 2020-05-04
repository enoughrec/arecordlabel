/**
 * @jsx React.DOM
 */

var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var About = React.createClass({
	componentWillMount: function(){
		document.title = 'Radio';
	},
	render: function(){
		return (

<div className="release-full">
<div className="centered-article">
	<h1>Enough Radio</h1>
	
	<p>Enough Radio was a pirate radio operated during 2000 and 2001, it's no longer in operation.</p>
	
	<h1>Enough Records Radio Show</h1>
	
	<p>From September 2017 to September 2018 we did a fortnightly 1 hour Enough Records Radio Show program, hosted by our label head ps, featuring tracks from our back-catalogue and some occasional exclusive mixes by guest Enough artists, broadcasted at <a href="http://futuremusic.fm/">futuremusic FM</a> and with Portuguese voice off at <a href="http://radiomanobras.pt/">Rádio Manobras</a>.</p>

	<p>On September 2018 we changed to a monthly 2 hour Enough Records Radio Show. English only.</p>
	
	<p>Currently being broadcast at:<br />
	- <a href="http://radiomanobras.pt/">Rádio Manobras</a>, Sunday 15:00 - 17:00 (PT)<br />
	- <a href="http://radioquantica.com/">Rádio Quântica</a>, every third Thursday 14:00 - 16:00 (PT)<br />
	- <a href="http://piratona.alg-a.org/">Rádio Piratona</a>, Sunday 09:00, Tuesday 00:00, Thursday 04:00, Saturday 00:00<br />
	- <a href="http://internetpublicradio.live/">Internet Public Radio</a>, every third Thursday, 17:00-19:00 (PT)</p>	
	
	<h1>Radio &amp; Livestream Archives</h1>

	<p id="contacts">
		<a href="https://www.mixcloud.com/enoughrecords/"><img src="/iconss/mixcloud_64.png" /></a>
		<a href="https://soundcloud.com/enoughrec/"><img src="/iconss/soundcloud_64.png" /></a>
		<a href="https://www.youtube.com/user/enoughrec"><img src="/iconss/youtube_64.png" /></a>
		<a href="https://twitch.tv/enoughrec"><img src="/iconss/twitch_64.png" /></a>
	</p>

</div>
</div>
		);
	}
});


module.exports = About;
