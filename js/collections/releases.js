var Backbone = require('backbone');
var ReleaseModel = require('../models/release');
var _ = require('underscore');
_.str = require('underscore.string');

var moment = require('moment');

// we only ever deal with one data source, so the collection
// has a reference to this, and a method to reset to the 
// full store. 
// hacky, but works and is simple ..and actually may not need this, 
// but lets see where it goes
var data = require('../../data/all.json').reverse().map(function(item) {
	item.key = "rel_" + _.uniqueId();
	item.momented = moment(item.release_date);
	return item;
});

var Releases = Backbone.Collection.extend({

	model: ReleaseModel,
	currentSort: 'release_date',
	comparator: function(model1, model2) {
		return model1.get('momented') > model2.get('momented') ? -1 : 1;
	},
	search: function(letters) {
		if (letters == "") return this;

		var pattern = new RegExp(letters, "gi");
		var releases = this.filter(function(item) {

			var searchString = item.getSearchData();
			return pattern.test(searchString);

		});
		return new Releases(releases);

	},
	getSimilarByTag: function(tags, ignore, limit) {
		limit = limit || 20;
		var hits = new Releases();

		if (tags && tags instanceof Array) {

			var releases = this.filter(function(item) {

				if (item.get('cat') === ignore || item.get('artist') === ignore) {
					return; // it is this release, no need to include
				}

				var itemTags = item.get('tags');
				var result = _.intersection(tags, itemTags);

				if (result.length) {
					item.matchLength = result.length;
				} else {
					item.matchLength = 0;
				}

				return result.length;
			});
			
		}

		releases = _.first(releases, limit);
		hits.reset(releases);

		hits.sort(function(a,b){
			return a.matchLength > b.matchLength ? -1 : 1;
		});

		return hits;


	},
	searchByTag: function(tags) {
		tags = tags instanceof Array ? tags : [tags];

		var hits = this.filter(function(item) {
			var itemTags = item.get('tags');
			var result = _.union(tags, itemTags);
			
			var hit = result.length === tags.length;

			if (hit) {
				return true;
			}

		});

		return new Releases(hits);

	},
	getTags: function() {
		return _.unique(_.flatten(this.pluck('tags'))).sort();
	},
	getCountries: function() {
		return _.unique(_.flatten(this.pluck('country'))).sort();
	},
	getArtists: function() {
		return _.unique(this.pluck('artist')).sort(function(a, b) {
			return a.toLowerCase() > b.toLowerCase() ? -1 : 1;
		});
	},
	getByArtist: function(artist, ignore){
		artist = artist || '';
		ignore = ignore || '';

		artist = artist.toLowerCase().trim();
		var hits = _(this.filter(function(data) {
			var isSameArtist = data.get('artist').toLowerCase() === artist;
			var isIgnored = data.get('cat') === ignore;
			return isSameArtist && !isIgnored;
		})).value();

		return new Releases(hits);
	},
	getByTag: function(tag) {
		var hits = _(this.filter(function(data) {
			return !!~ (data.get('tags').indexOf(tag));
		})).value();

		return new Releases(hits);

	},
	
	fullReset: function(){
		return this.reset(data);
	},
	getMayAlsoLike: function(options){
		options = options || {};

		var hits = new Releases();

		// var bySameArtist = this.getByArtist(options.artist, options.ignore);
		var similarByTag = this.getSimilarByTag(options.tags, options.artist);
		
		// hits.add(bySameArtist.toJSON(), {sort:false});
		hits.add(similarByTag.toJSON(), {sort:false});
		
		
		
		return hits;

	}
});

module.exports = Releases;