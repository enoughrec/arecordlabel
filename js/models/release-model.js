var Backbone = require('backbone');
var _ = require('underscore'); // augmented by _.str in index.js
var path = require('path');
var moment = window.moment = require('moment');

var ReleaseModel = Backbone.Model.extend({
	initialize: function() {
		this.cache = {};
	},
	// getters: {
	// 	'cover': function(){
	// 		var ret = this.get('cover');
	// 		return ret;
	// 	}
	// },
	setters: {
		'cover': function(val) {

			var result = '/covers/' + path.basename(val);
			return result;
		},
		'artist': function(val) {
			val = _.isString(val) ? val : _.result(this.defaults, 'artist');
			return val;
		},
		'album': function(val) {
			val = _.isString(val) ? val : _.result(this.defaults, 'album');
			return val;
		},
		'release_date': function(date) {
			if (!date || date === '0000-00-00') {
				date = '2002-01-01';
			};

			this.set('momented',moment(date));
			return date;
		}
	},
	// get: function(attr) {
	// 	if (typeof this.getters[attr] === 'function') {
	// 		return this.getters[attr].call(this, attr);
	// 	} else {
	// 		return Backbone.Model.prototype.get.call(this, attr);
	// 	}
	// },
	set: function(attr, val, options) {

		if (typeof attr === 'object') {
			var singleAttr;
			for (singleAttr in attr) {
				this.set.call(this, singleAttr, attr[singleAttr], options);
			}
			return this;

		} else {
			if (typeof this.setters[attr] === 'function') {
				var normalised = this.setters[attr].call(this, val);
				return Backbone.Model.prototype.set.call(this, attr, normalised, options);
			} else {
				return Backbone.Model.prototype.set.apply(this, arguments);
			}
		}

	},
	getSearchData: function(){
		if (this.cache['searchData']) return this.cache['searchData'];
		var artist = this.get('artist') || '',
			album = this.get('album') || '',
			// info = this.get('info_en') || '',
			cat = this.get('cat') || '';
		var searchString = [artist,album,cat].join(' ');
		return searchString;
	},
	defaults: {
		album: "",
		artist: "",
		bandcamp: null,
		cat: "ENR",
		clearbits: false,
		cover: "http://tpolm.org/~ps/enough/covers/enrmp001.jpg",
		discogs: false,
		download: false,
		info_en: false,
		info_pt: false,
		jamendo: false,
		release_date: "2001-01-01",
		scene_org: false,
		sonicsquirrel: false,
		soundcloud: false,
		tags: function() {
			return [];
		},
		visible: true
	}
});

module.exports = ReleaseModel;
