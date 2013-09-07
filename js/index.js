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
window.data = data;
window.rels = releases;