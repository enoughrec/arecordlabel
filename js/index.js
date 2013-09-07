var $ = window.$ =  require('jquery-browserify');
var Backbone = require('backbone');
Backbone.$ = $; // for browserify


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
$("#main").append(list.el);

// expose

window.data = data;
window.rels = releases;