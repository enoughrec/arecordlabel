// setup 

var $ = require('jquery-browserify');
var Backbone = require('backbone');
Backbone.$ = $; // for browserify

// underscore and string methods
var _ = require('underscore');
_.str = require('underscore.string');

// app stuff
var Releases = require('./collections/releases-collection');
var ReleaseView = require('./views/release-view');
var ReleaseListView = require('./views/release-list-view');
var Router = require('./router');

var relpage_tpl = require('./templates/details.hbs');

// should be an API, but flat object for now 
var data = require('../data/clean.json');	

// releases collection
var releases = new Releases(data);

// view where it will all live
var list = new ReleaseListView({
	collection: releases
});


var router = new Router();

router.on('release', function(cat) {
	var release = releases.filter(function(rel){
		return rel.get('cat') === cat;
	});

	if (!release) {
		Backbone.history.navigate('/');
	} else {
		list.remove();
		var html = relpage_tpl(release[0].toJSON());
		$("#main").html(html);	
	}
});

router.on('home', function() {
  	list.render();
	$("#main").empty().append(list.el);
});


var app = {
	rootURL: '/'
};


// search box stuff
var lastSearch = false;
var searchHandler = function(){

	if(this.value === lastSearch){
		return;
	} else {
		lastSearch = this.value;
	}

	if (this.value.length === 0) {
		console.log('reset vis', Date.now())
		releases.resetVisibility();
		return;
	} else {
		releases.search(this.value);
	}

}

$("#top-bar input").on('keyup', _.debounce(searchHandler,333));


// start routing
Backbone.history.start({ pushState: true, root: app.rootURL });

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

// expose
window.list = list;
window._ = _;
window.$ = $;
window.Backbone = Backbone;
window.data = data;
window.rels = releases;