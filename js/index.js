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

var router = new Router();

// Trigger 'route' event on router instance."
router.on('release', function(name, args) {
  console.log(name, args); 
});


Backbone.history.start({ pushState: true, root: '/' });

// All navigation that is relative should be passed through the navigate
// method, to be processed by the router. If the link has a `data-bypass`
// attribute, bypass the delegation completely.
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
		// Stop the default event to ensure the link will not cause a page
		// refresh.
		evt.preventDefault();

		// `Backbone.history.navigate` is sufficient for all Routers and will
		// trigger the correct events. The Router's internal `navigate` method
		// calls this anyways.  The fragment is sliced from the root.
		Backbone.history.navigate(href.attr, true);
	}
});



// should be an API, but flat object for now 
var data = require('../data/clean.json');


// releases collection
var releases = new Releases(data);

// view where it will all live
var list = new ReleaseListView({
	collection: releases
});

list.render();
$("#main").empty().append(list.el);


// expose
window._ = _;
window.$ = $;
window.Backbone = Backbone;
window.data = data;
window.rels = releases;