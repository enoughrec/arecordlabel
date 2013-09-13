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
		var releases = this.filter(function(data) {
			var searchString = data.getSearchData();

		  	var hit = pattern.test(searchString);
		  	
		  	if (hit) {
		  		var year = data.get('momented').year();
		  		console.log(years);
		  		if (years.lastIndexOf(year) === -1) {
		  			console.log(years);
		  			years.push(year);
		  		};
		  		console.log(years);
		  	};

		  	data.set('visible', hit);
		  	
		});

		console.log(years);

		$(".year-sep").each(function(){

			var year = parseInt(this.getAttribute('year'),10);

			$(this).toggleClass('hidden', years.indexOf(year) === -1);
			
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