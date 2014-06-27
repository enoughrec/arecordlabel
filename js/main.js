// setup 

var $ = require('jquery-browserify');
var Backbone = require('backbone');
Backbone.$ = $; // for browserify

var App = require('./app');


var play = require('play-audio');


// load HBS runtime and helpers
var helpersLoaded = require('./helpers');

// underscore and string methods
var _ = require('underscore');
_.str = require('underscore.string');

var Router = require('./router');