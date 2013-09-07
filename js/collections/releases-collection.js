var Backbone = require('backbone');
var ReleaseModel = require('../models/release-model');


var Releases = Backbone.Collection.extend({
	model: ReleaseModel
});

module.exports = Releases;