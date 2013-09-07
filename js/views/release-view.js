var Backbone = require('backbone');


var ReleaseView = Backbone.View.extend({
	tagName: 'div',
	className: 'release',
	initialize: function(){
		this.render();
	},
	render: function(){

		this.$el.empty().html('hi');
	}
});

module.exports = ReleaseView;