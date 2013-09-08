var Backbone = require('backbone');

var Router = Backbone.Router.extend({
	routes: {
		'': 'home',
		'release/*cat': 'release'
	},
	home: function(){
		this.trigger('home');
	},
	release: function(cat){
		this.trigger('release',cat);
	}
});

module.exports = Router;