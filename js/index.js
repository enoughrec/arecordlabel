// setup 

var $ = require('jquery-browserify');
var Backbone = require('backbone');
var play = window.play = require('play-audio');
var path = require('path');
Backbone.$ = $; // for browserify
var hbs = require('handlebars-runtime');
hbs.registerHelper('pluralize', function(num, single, plural) {
	if (parseInt(num, 10) === 1) {
		return single;
	} else {
		return plural;
	}
});

hbs.registerHelper('removeDot', function(word) {
	if (word && _.isString(word)) {
		if (word[0] === '.') {
			word = word.substr(1, word.length - 1)
		};
	};

	return word;
});

// underscore and string methods
var _ = require('underscore');
_.str = require('underscore.string');

// app stuff
var Releases = require('./collections/releases-collection');
var ReleaseView = require('./views/release-view');
var ReleaseListView = require('./views/release-list-view');
var Router = require('./router');

// templates
var relpage_tpl = require('./templates/details.hbs');
var about_tpl = require('./templates/about.hbs');

// could be an API, but flat object for now 
var data = require('../data/all.json');

// releases collection
var releases = new Releases(data);


var tagSearch = [];

var tagClickHandler = function(tag){
	return function(){
		$(this).toggleClass('active');
		if (tagSearch.indexOf(tag) === -1) {
			tagSearch.push(tag);
		} else {
			tagSearch.splice(tagSearch.indexOf(tag),1);
		}

		releases.searchByTag(tagSearch);

	};
}

var tags = releases.getTags();
var $tv = $(".tagvis");
var $tn = $(".tag-navigation");
tags.forEach(function(item){
	var tag = document.createElement('div');
	tag.className = 'tag-choice';
	tag.innerHTML = item;
	tag.onclick = tagClickHandler(item);
	$tn[0].appendChild(tag);
},tags);



$tv.on('click',function(e){
	$tn.toggleClass('hidden');
});

// view where it will all live
var list = new ReleaseListView({
	collection: releases
});


var router = new Router();

router.on('release', function(cat) {

	this.currentPage = 'release';
	var release = releases.filter(function(rel) {
		return rel.get('cat') === cat;
	});

	if (!release) {
		Backbone.history.navigate('/');
	} else {
		list.remove();
		var relData = release[0].toJSON();

		relData.formattedDate = release[0].get('momented').format('MMMM Do, YYYY');
		relData.numTracks = relData.tracks ? relData.tracks.length : false;

		var html = relpage_tpl(relData);
		document.title = '' + relData.album + ' - ' + relData.artist + '  | ' + relData.cat.toUpperCase();
		$("#main").html(html)
			.find('.play').on('click', function() {
			player.queue(relData.tracks);
		});
		window.scrollTo(0);			
	}
});

router.on('about', function() {
	this.currentPage = 'about';
	document.title = 'About Us';
	list.remove();
	var content = about_tpl();
	$("#main").empty().html(content);
});

router.on('home', function() {
	console.log('home');
	this.currentPage = 'home';
	document.title = 'Enough Records';
	list.render();
	document.title
	$("#main").empty().append(list.el);
});

router.on('error', function() {
	alert('Hey, something is screwy. please refresh, and if it happens again, let us know!');
});


var app = {
	rootURL: '/'
};



// search box stuff
var lastSearch = false;
var searchBox = $("#top-bar input");

function clearSearchBox() {
	searchBox[0].value = '';
	closeBox.addClass('hidden');
	releases.resetVisibility();
}

var closeBox = $(".fontawesome-remove");
closeBox.on('click', clearSearchBox);

var searchHandler = function(e) {

	if (e.keyCode === 27) { // reset on escape
		this.value = '';
	};
	if (router.currentPage !== 'home') {
		Backbone.history.navigate('/', {
			trigger: true
		});
	};

	// @TODO need to normalize input values here (can actually put valid regex in, if should so want)

	if (this.value === lastSearch) {
		return;
	} else {
		lastSearch = this.value;
	}

	if (this.value.length === 0) {
		closeBox.addClass('hidden');
		releases.resetVisibility();
		return;
	} else {
		closeBox.removeClass('hidden');
		releases.search(this.value);
	}

}

searchBox.on('keyup', _.debounce(searchHandler, 333));

$('#home').on('click', clearSearchBox);

// player

var controls = document.getElementById('bottom-bar');
var Player = function(elem) {
	this.elem = elem;
	this.playing = false;
	this.widget = play([], elem).autoplay();
	this.playButton = $("#play-state");
	this.widget.volume(0.5);
	this.widget.on('play', this.togglePlayState.bind(this, true));
	this.widget.on('ended', this.advance.bind(this));

	$(".controls span").on('click', this.doControl.bind(this));
}

Player.prototype.doControl = function(evt) {
	if (!this.playlist) {
		return
	};
	var ctrl = evt.target.getAttribute('ctrl');

	switch (ctrl) {
		case 'stop':
			this.widget.pause().currentTime(0);
			this.togglePlayState(false);
			break;
		case 'play':
			if (this.playing) this.widget.pause() && this.togglePlayState(false);
			else this.widget.play();
			break;
		case 'forward':
			this.advance(true);
			break;
		case 'back':
			this.advance(false);
			break;
	}

};

Player.prototype.togglePlayState = function(playing) {
	this.playButton.removeClass();

	if (playing) {
		this.playButton.addClass('fa fontawesome-pause');
		var name = path.basename(this.widget.src());
		$(".now-playing").removeClass('nothing').html(name);
		this.playing = true;
	} else {
		this.playButton.addClass('fa fontawesome-play');
		this.playing = false;
		$(".now-playing").html('nothing playing').addClass('nothing');
	}
};

Player.prototype.advance = function(fwd) {
	var mod = fwd ? 1 : -1;
	this.position = this.position + mod;
	if (this.position > this.playlist.length - 1) this.position = 0;
	else if (this.position < 0) this.position = this.playlist.length - 1;
	this.play();

};

Player.prototype.play = function() {
	var pos = this.position;
	this.widget.src(this.playlist[pos]);
};

Player.prototype.queue = function(files) {
	if (files && files.length) {
		this.playlist = files;
		this.position = 0;
		this.play();
	} else {
		alert('This release has no playable files, sorry.');
	}
}

var player = new Player(controls);

// start routing
Backbone.history.start({
	pushState: true,
	root: app.rootURL
});

$(document).on("click", "a[href]:not([data-bypass])", function(evt) {
	// Get the absolute anchor href.
	var href = {
		prop: $(this).prop("href"),
		attr: $(this).attr("href")
	};
	// Get the absolute root.
	var root = location.protocol + "//" + location.host + app.rootURL;

	// Ensure the root is part of the anchor href, meaning it's relative.
	if (href.prop.slice(0, root.length) === root) {
		evt.preventDefault();
		// `Backbone.history.navigate` is sufficient for all Routers and will
		// trigger the correct events. The Router's internal `navigate` method
		// calls this anyways.  The fragment is sliced from the root.
		Backbone.history.navigate(href.attr, true);
	}
});

// expose some stuff to window for debug 
window._ = _;
window.rels = releases;
