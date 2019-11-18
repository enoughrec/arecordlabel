/**
 * @jsx React.DOM
 */


var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var Tag = require('./tag');

var About = React.createClass({
	componentWillMount: function(){
		document.title = 'About';
	},
	render: function(){
		return (

<div className="release-full">
<div className="centered-article">

	<h1>About</h1>
	
	<p>Enough Records is a netlabel (<a href="http://en.wikipedia.org/wiki/Netlabel">wikipedia link</a>). It was founded in 2000 by three Portuguese demoscene enthusiasts Fred, H4rv3st and ps. Official release catalogue was relaunched in 2001. It has been managed by ps and a few random helping hands since 2003.</p>

	<h1>Mailing List</h1>

	<p>
	<div id="mc_embed_signup">
	<form action="//scene.us3.list-manage.com/subscribe/post?u=f8c1f1dae22f9657c634ea871&amp;id=163fce62d4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" className="validate" target="_blank" noValidate>
	    <div id="mc_embed_signup_scroll">
	    <div id="outside"><input type="hidden" name="b_f8c1f1dae22f9657c634ea871_163fce62d4" tabIndex="-1" /></div>
		<input type="email" name="EMAIL" className="email" id="mce-EMAIL" placeholder="email address" required />
	    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" className="button" />
	    </div>
	</form>
	</div>
	</p>
	
	<h1>Release Terms</h1>

	<p>We operate non-profit.</p>
	
	<p>Our entire catalogue is free for download, it has associated <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons BY-NC-SA 4.0</a> licenses (unless otherwise specified). Feel free to share, use and remix accordingly.</p>
	
	<p>Our releases are available in several free distribution platforms such as <a href="http://scene.org/dir.php?dir=%2Fmusic%2Fgroups%2Fenough_records/">Scene.Org</a>, <a href="http://archive.org/details/enough_records">Internet Archive</a>, <a href="http://sonicsquirrel.net/detail/label/enoughrecords/118">Sonic Squirrel</a>, <a href="http://freemusicarchive.org/label/Enough_Records/">Free Music Archive</a>, <a href="https://enoughrec.bandcamp.com/">Bandcamp</a>, <a href="https://soundcloud.com/enoughrec/">Soundcloud</a>, Jamendo, LastFM, etc.</p>

	<p>Our releases are also available through Routenote on a few commercial platforms such as iTunes, Amazon, Google Play, eMusic, Deezr, Rdio, Spotify, Youtube, Wimp, Music Unlimited, etc. Some of these platforms force us to put a price tag on our releases, we try to always set the possible minimum required.</p>
	
	<p>Artists retain priority to commercialize their tracks, licensing for explicit commercial use must go through them directly. We are non-profit, any income Enough Records gets from commercial platforms is used to cover label costs or given away as donations (check our yearly transparency reports).</p>
	
	<p>Regarding youtube copyright claims: Some of our tracks, which have been uploaded to routenote, might give you a copyright claim on youtube videos that use them, if you do not wish for routenote to collect ad revenue from those videos you are welcome to contest the claim and reference the release page and associated creative commons license that allows your free use of the track on youtube.</p>

	<h1>Demo Policy</h1>
	
	<p>We are open to demos. Please send your submissions to <a href="mailto:ps@enoughrecords.org">ps</a> via <a href="https://wetransfer.com/">wetransfer</a> or something similar.</p>
		
	<h1>Sub Labels</h1>

	<p><Tag tag="c!">c!</Tag> comprises 4 releases salvaged when the Catita! netlabel stopped its activities. They focused on 8bit music.</p>
	<p><a href="http://enoughrecords.scene.org/anonymous_archives/">Anonymous Archives</a>, tagged as <Tag tag="Anon">Anon</Tag> is our socio-political activist sub-label.</p>
	<p><Tag tag="[Esc.]">[Esc.]</Tag> tags releases from the <a href="http://www.esc-laboratory.com/">[Esc.] Laboratory</a> collective from Germany.</p>
	<p><Tag tag="thisk">thisk</Tag> lists the releases related or co-released with our friends from <a href="http://thisco.net/">Thisco Records</a>.</p>
	
	<h1>Acknowledgements</h1>
	
	<p>Enough Records was founded in 2001 by Fred, H4rv3st and ps. Managed and curated by ps alone since 2003. But it's not a solo effort, thanks to all the nameless folks who help us run and promote Enough Records. This includes organizing mixtapes, concerts, compilations, sending out packages in the mail, spamming online, etc. Enough Records would not be what it is without your love and support.</p>

	<p>Website developed by <a href="http://twitter.com/danpeddle" target="_blank">Dan Peddle</a> and ps as an <a href="https://github.com/enoughrec/arecordlabel/">open source project</a>, feel free to fork, re-use and contribute.</p>

</div>
</div>

		);
	}
});


module.exports = About;
