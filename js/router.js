// load views

var Backbone = require('backbone');

var Router = Backbone.Router.extend({
	routes: {
		'': 'home',
		'about':'about',
		'release/*cat': 'release'
	},
	about: function(){
		this.trigger('about');
	},
	home: function(){
		this.trigger('home');
	},
	release: function(cat){
		this.trigger('release',cat);
	}
});

module.exports = Router;
