var Backbone = require('backbone');
var _ = require('underscore'); // augmented by _.str in index.js

var bus = require('../bus');

var State = Backbone.Model.extend({
	initialize: function(){
		bus.on('search', this.setSearch.bind(this));
	},
	setSearch: function(value){
		value = value || '';
		value = value.toLowerCase().trim();

		if (value.length) {
			this.set('userSearch', value);
		} else {
			this.set('userSearch', '');
		}
	},
	defaults: {
		userSearch: ''
	},
	hasUserSearch: function(){
		var userSearch = this.get('userSearch');
		if (userSearch && userSearch.length) {
			return userSearch;
		} else {
			return false;
		}
	}

});

module.exports = State;