var Backbone = require('backbone');
var ReleaseModel = require('../models/release-model');
var _  = require('underscore');
_.str = require('underscore.string');


var Releases = Backbone.Collection.extend({
	model: ReleaseModel,
	currentSort: 'release_date',
	comparator: function(model1, model2){
		return model2.get('artist') > model1.get('artist') ? -1 : 1;
	}
});

module.exports = Releases;