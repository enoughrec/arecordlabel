var Backbone = require('backbone');

var State = Backbone.Model.extend({
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
