/**
 * @jsx React.DOM
 */
var React = require('react');
var bus = require('../bus');

var url = require('url');

var ReleaseDetail = React.createClass({
	componentWillMount: function(){
		var cat = this.props.cat;
		this.props.data = this.props.data.findWhere({cat:cat});
		var data = this.props.data.toJSON();

		// fix for &#1042; style unicode entities
		var s = document.createElement('span');
		s.innerHTML = '' + data.album + ' - ' + data.artist + '  | ' + data.cat.toUpperCase();
		document.title = s.innerHTML;
	},
	getDownloadLinks: function(){

		var sources = ["fma", "archiveorg", "scene_org", "clearbits", "sonicsquirrel", "soundshiva", "dogmazic", "archiveorg", "jamendo", "bandcamp", "soundcloud", "mixcloud", "lastfm", "itunes", "amazon", "googleplay", "spotify", "discogs", "rym", "musicbrainz"];
		var release = this.props.data.toJSON();

		var downloadLinks = sources.map(function(source){
			if (release[source]) {
				return <a href={release[source]}><img src={'/iconss/'+source+'.png'}/></a>;
			};
		});
		return downloadLinks;

	},
	startPlaying: function(){
		// push this release to the playlist queue
		bus.emit('queue', this.props.data);
	},
	render: function(){

		var data = this.props.data.toJSON();
		var playable = data.tracks && data.tracks.length;
		var coverPath = url.parse(data.cover);
		var formattedDate = data.momented.format('MMMM Do, YYYY');
		data.cover = '/'+coverPath.path;
		return (
<div className="release-full">
	<div className="leftframe">
		<div className="cover">
			<div className="playbutton fontawesome-play" onClick={this.startPlaying}></div>
			<img src={data.cover} alt={data.album + ' - ' + data.artist} />
		</div>
		<div className="titles">
			<h1><span className="album">{data.album}</span> <span className="artist">{data.artist}</span></h1>
		</div>
		<div className="cc"><img src={"/iconss/" + data.cc_img}/></div>
	</div>
	<div className="details">
		<div className="release-date block">Released on <span>{formattedDate}</span></div>
		<div className="block info_en text-clamped " dangerouslySetInnerHTML={{__html: data.info_en}} />
		<div className="block info_pt text-clamped " dangerouslySetInnerHTML={{__html: data.info_pt}} />
		<div className="block">
			Tags:<br />
		</div>
		<div className="block">Nationality:<br />
			<img className="artist-country" title="" src="/flags/_flag_pt.png" />
		</div>
		<div className="download_links block">
			Download links:<br />
			{this.getDownloadLinks()}
				
		</div>
		<div className="related">
			<h1>You may also like:</h1>
		</div>
	</div>
	<div className="clear"></div>
</div>

			)
	}
})

/*

{
	"cat": "enrmp001",
	"artist": "ps",
	"album": "from my own little corner",
	"release_date": "2002-01-14",
	"info_en": "3 track dark ambient ep from our staff member <a href=\"http:\/\/tpolm.org\/~ps\/\">ps<\/a>. Photo artwork by Joana Queir\u00f3z.",
	"info_pt": "EP de dark ambient de 3 temas feito pelo nosso membro de staff <a href=\"http:\/\/tpolm.org\/~ps\/\">ps<\/a>. Fotografia de Joana Queir\u00f3z.",
	"discogs": "http:\/\/www.discogs.com\/ps-From-My-Own-Little-Corner\/release\/606784",
	"jamendo": null,
	"sonicsquirrel": null,
	"scene_org": "http:\/\/scene.org\/file.php?file=%2Fmusic%2Fgroups%2Fenough_records%2Fenrmp001_ps_-_from_my_own_little_corner.zip&fileinfo",
	"bandcamp": "http:\/\/enoughrec.bandcamp.com\/album\/from-my-own-little-corner",
	"soundcloud": null,
	"itunes": null,
	"spotify": null,
	"mixcloud": null,
	"fma": null,
	"rym": "http:\/\/rateyourmusic.com\/release\/ep\/ps_f1\/from_my_own_little_corner\/",
	"musicbrainz": null,
	"dogmazic": null,
	"lastfm": "http:\/\/www.last.fm\/music\/ps\/from+my+own+little+corner",
	"tags": ["laidback", "dark", "ambient", ".pt"],
	"archiveorg": "http:\/\/www.archive.org\/details\/enrmp001_ps_-_from_my_own_little_corner",
	"cover": "http:\/\/enoughrecords.scene.org\/covers\/enrmp001.jpg",
	"cc": "http:\/\/creativecommons.org\/licenses\/by-nc-sa\/3.0\/",
	"cc_img": "ccbyncsa.png",
	"tracks": ["http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/01_ps_-_lost_in_familiar_alleys.mp3", "http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/02_ps_-_while_she_is_not_here.mp3", "http:\/\/http.de.scene.org\/pub\/music\/groups\/enough_records\/enrmp001_ps_-_from_my_own_little_corner\/03_xhale_vs_ps_-_two_seconds_of_time.mp3"]
}

*/


module.exports = ReleaseDetail;