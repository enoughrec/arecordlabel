var Backbone = require('backbone');

var Router = Backbone.Router.extend({
	routes: {
		'': 'home',
		'release/:cat': 'release'
	},
	home: function(){
		console.log('home')
	},
	release: function(cat){
		console.log(cat);
	}
});

module.exports = Router;