/**
 * @jsx React.DOM
 */
var React = require('react');
var createReactClass = require('create-react-class');
var bus = require('../bus');

var url = require('url');

var Tag = require('./tag');

var Router = require('react-router');
var Link = Router.Link;

function canShow(items, className){
    if (!items.length) {
        className += ' hidden';
    }
    return className;
}

String.prototype.splice = function(idx, rem, str) {
    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
};

var ReleaseDetail = createReactClass({
    componentWillMount: function(){
        var cat = this.props.params.cat;
        this.props.release = this.props.data.findWhere({cat:cat});
        var data = this.props.release.toJSON();
        
        // fix for &#1042; style unicode entities
        var s = document.createElement('span');
        s.innerHTML = '' + data.album + ' - ' + data.artist + ' | ' + data.cat.toUpperCase();
        document.title = s.textContent || s.innerHTML;
    },
    componentDidMount: function(){
        window.scrollTo(0,0);
    },
    getDownloadLinks: function(release){

        var sources = ["fma", "archiveorg", "scene_org", "sonicsquirrel", "jamendo", "bandcamp", "soundcloud", "mixcloud", "lastfm", "itunes", "discogs", "spotify", "audius", "audiomack", "resonate", "youtube", "freeebooks", "bookrix", "amazon", "goodreads", "manybooks", "smashwords", "blurb", "scribd"];
        //var release = this.props.release.toJSON();

        var downloadLinks = sources.map(function(source){
            if (release[source]) {
            	/*if ((source == 'mixcloud') && (release.cat.substring(0,7) == 'enrshow')) {
            		var mixcloud_pt = release[source].splice(release[source].length-1,0,'-pt');
            		return <span><a href={release[source]} target="_blank"><img src={'/iconss/'+source+'.png'}/></a><a href={mixcloud_pt} target="_blank"><img src={'/iconss/'+source+'.png'}/></a></span>;
            	} else {*/
                	return <a href={release[source]} target="_blank"><img src={'/iconss/'+source+'_64.png'}/></a>;
                //}
            };
        });
        return downloadLinks;

    },

    buildLink: function(release){
        return (
            <Link className="mini-link" to={"/release/" + release.cat}>
                <img src={release.cover} title={release.artist + ' - ' + release.album} />
            </Link>);
    },
    getTags: function(tags){

        return tags.map(function(tag){
            return (<Tag tag={tag}>{tag}</Tag>)
        });
        
    },
    getCountries: function(countries){
        return countries.map(function(country){
            var country = country.replace('.','');
            return (<img className="artist-country" title="" src={"/flags/_flag_" + country + ".png"} />);
        });
    },
    getRelatedLinks: function(data){   	
        var relatedRelease = this.props.data.getMayAlsoLike({
            tags: data.tags,
            artist: data.artist,
            ignore: data.cat
        });

        var links = relatedRelease.map(function(model){
            var data = model.toJSON();
            return this.buildLink(data);
        }.bind(this));

        return links;
    },
    getBySameArtist: function(data){

    	if ((data.cat.substring(0,7) == 'enrshow') && data.artist == 'ps') return {};
    	if ((data.cat.substring(0,6) == 'enrmix') && data.artist == 'ps') return {};

        var bySameArtist = this.props.data.getByArtist(data.artist, data.cat);

        var links = bySameArtist.map(function(model){
            var data = model.toJSON();
            return this.buildLink(data);
        }.bind(this));

        return links;
    },
    getCoverThing: function(data){
    	if (data.cat.substring(0,6) == 'enrtxt') {
    		return(<div className="cover">
                	<img src={data.cover} alt={data.album + ' - ' + data.artist} />
                </div>);
    	} else {
    		return(<div className="cover">
                	<div className="playbutton fa fa-play" onClick={this.startPlaying}></div>
                    <img src={data.cover} alt={data.album + ' - ' + data.artist} />
                </div>);
    	}
    },
    startPlaying: function(){
        // push this release to the playlist queue
        bus.emit('queue', this.props.release);
    },
    render: function(){

        var data = this.props.release.toJSON();
        var playable = data.tracks && data.tracks.length;
        var coverPath = url.parse(data.cover);
        var formattedDate = data.momented.format('MMMM Do, YYYY');

        var relatedLinks = this.getRelatedLinks(data);
        var bySameArtist = this.getBySameArtist(data);

        var tags = this.getTags(data.tags);
        var countries = this.getCountries(data.country);
        var downloadLinks = this.getDownloadLinks(data);
		var coverThing = this.getCoverThing(data);
		
        var sameArtistText = data.artist === 'Various Artists' ? 'More Compilations:' : 'Other releases by '+data.artist+':';

        return (
            <div className="release-full">
                <div className="leftframe">
                    <header>
                        {coverThing}
                        <div className="titles">
                            <h1><span className="title album">{data.album}</span> <span className="title artist">{data.artist}</span></h1>
                        </div>
                    </header>
                    <div className="cc"><img src={"/iconss/" + data.cc_img}/></div>
                </div>
                <div className="details">
                    <div className="release-date block">Released on <span>{formattedDate}</span></div>
                    <div className="block info_en text-clamped " dangerouslySetInnerHTML={{__html: data.info_en}} />
                    <div className="block info_pt text-clamped " dangerouslySetInnerHTML={{__html: data.info_pt}} />
                    <div className="block">
                        Tags:<br />
                        {tags}
                    </div>
                    <div className={canShow(countries,"block")}>Nationality:<br />
                        {countries}
                    </div>
                    <div className={canShow(downloadLinks, "download_links block")}>
                        <h1>Free Download:</h1>
                        {downloadLinks}
                    </div>
                    <div className={canShow(bySameArtist, "related")}>
                        {sameArtistText}<br />
                        {bySameArtist}
                    </div>
                    <div className={canShow(relatedLinks, "related")}>
                        You may also like:<br />
                        {relatedLinks}
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
