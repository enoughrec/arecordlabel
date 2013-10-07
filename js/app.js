
var events = require('events');


var App = function(){

	if (this instanceof App === false) {
		throw new Error('App should be called as a constructor');
	};

	this.bus = new events.EventEmitter();
	this.pages = {};


};





module.exports = App;