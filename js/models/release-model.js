var Backbone = require('backbone');
var _ = require('underscore'); // augmented by _.str in index.js
var path = require('path');
var moment = window.moment = require('moment');

var ReleaseModel = Backbone.Model.extend({
	initialize: function() {

	},
	getters: {

	},
	setters: {
		'cover': function(val) {
			var result = '/covers/' + path.basename(val);
			return result;
		},
		'artist': function(val) {
			return _.isString(val) ? val : _.result(this.defaults['artist']);
		},
		'album': function(val) {
			return _.isString(val) ? val : _.result(this.defaults['album']);
		},
		'release_date': function(date) {
			if (!date || date === '0000-00-00') {
				date = '2002-01-01';
			};

			this.set('momented',moment(date));
			return date;
		}
	},
	get: function(attr) {
		if (typeof this.getters[attr] === 'function') {
			return this.getters[attr].call(this, attr);
		} else {
			return Backbone.Model.prototype.get.call(this, attr);
		}
	},
	set: function(attr, val) {

		if (typeof attr === 'object') {

			var singleAttr;

			for (singleAttr in attr) {
				this.set.call(this, singleAttr, attr[singleAttr]);
			}
		} else {
			if (typeof this.setters[attr] === 'function') {
				var normalised = this.setters[attr].call(this, val);
				Backbone.Model.prototype.set.call(this, attr, normalised);
			} else {
				return Backbone.Model.prototype.set.apply(this, arguments);
			}
		}



	},
	defaults: {
		album: "no name",
		artist: "no artist",
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
		visible: false
	}
});

module.exports = ReleaseModel;