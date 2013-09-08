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
		var releases = this.filter(function(data) {
			var searchString = data.getSearchData();

		  	var hit = pattern.test(searchString);
		  	data.set('visible', hit);
		  	
		});

	},
	resetVisibility: function(){
		this.invoke('set',{
			visible: true
		});
	},
	getTags: function(){
		return _.unique(_.flatten(this.pluck('tags'))).sort();
	},
	getCountries: function(){
		var tags = this.getTags();
		return _.filter(tags, function(tag){
			return tag[0] === '.';
		});
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