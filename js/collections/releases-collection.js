var Backbone = require('backbone');
var ReleaseModel = require('../models/release-model');
var _  = require('underscore');
_.str = require('underscore.string');


var Releases = Backbone.Collection.extend({
	model: ReleaseModel,
	currentSort: 'release_date',
	comparator: function(model1, model2){
		return model2.get('artist') > model1.get('artist') ? -1 : 1;
	},
	search : function(letters){
		if(letters == "") return this;
 
		var pattern = new RegExp(letters,"gi");
		var releases = this.filter(function(data) {
		  	return pattern.test(data.get("artist"));
		});

	},
	getTags: function(){
		return _.unique(_.flatten(this.pluck('tags'))).sort();
	},
	getArtists: function(){
		return _.unique(this.pluck('artist')).sort(function(a,b){
			return a.toLowerCase() > b.toLowerCase() ? -1 : 1;
		});	
	},
	getByTag: function(tag){
		var releases = _(this.filter(function(data){
			return !!~(data.get('tags').indexOf(tag));
		}));

		return new Releases(releases);
	}
});

module.exports = Releases;