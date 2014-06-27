var Backbone = require('backbone');
var ReleaseModel = require('../models/release-model');
var _  = require('underscore');
_.str = require('underscore.string');


var Releases = Backbone.Collection.extend({

	model: ReleaseModel,
	currentSort: 'release_date',
	comparator: function(model1, model2){
		return model1.get('momented') > model2.get('momented') ? -1 : 1;
	},
	search : function(letters){
		if(letters == "") return this;
 
		var pattern = new RegExp(letters,"gi");
		var years = [];
		var releases = this.filter(function(item) {

			var searchString = item.getSearchData();
		  	var hit = pattern.test(searchString);
		  	
		  	if (hit) {
		  		var year = item.get('momented').year();
		  		if (years.lastIndexOf(year) === -1) {
		  			years.push(year);
		  		};
		  	};

		  	// should not be doing it like this
		  	item.set('visible', hit);
		});

		
	},
	getSimilarByTag: function(tags, ignore){
		var hits = [];

		if (tags && tags instanceof Array) {

			this.filter(function(item){

				if (item.get('cat') === ignore){
					return; // it is this release, no need to include
				};

				var itemTags = item.get('tags');
				var result = _.intersection(tags, itemTags);

				if (result.length) {
					// calc levenshtein distance between the intersection and the search array
					var dist = _.str.levenshtein(result.join(' '), tags.join(' '));
					if (Math.log(dist) < 1.5) { // this value needs tuning
						hits.push(item.toJSON());	
					} else {
						// console.log('nuhuh',Math.log(dist));
					}
				};
			});
		}

		return hits;
	},
	searchByTag: function(tags){
		var years = [];
		this.filter(function(item){
			var itemTags = item.get('tags');
			var result = _.intersection(tags, itemTags);

			var hit = result.length === tags.length;

			if (hit) {
		  		var year = item.get('momented').year();
		  		if (years.lastIndexOf(year) === -1) {
		  			years.push(year);
		  		};
		  	};

		  	// should return array of hits, not interact directly on the model
			item.set('visible',hit);
		});

		
	},
	getTags: function(){
		return _.unique(_.flatten(this.pluck('tags'))).sort();
	},
	getCountries: function(){
		return _.unique(_.flatten(this.pluck('country'))).sort();
	},
	getArtists: function(){
		return _.unique(this.pluck('artist')).sort(function(a,b){
			return a.toLowerCase() > b.toLowerCase() ? -1 : 1;
		});	
	},
	getByTag: function(tag){
		return  _(this.filter(function(data){
			return !!~(data.get('tags').indexOf(tag));
		}));
	}
});

module.exports = Releases;