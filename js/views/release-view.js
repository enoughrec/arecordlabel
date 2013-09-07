var Backbone = require('backbone');
var tpl = require('../templates/release.hbs');
window.tpl = tpl;

var ReleaseView = Backbone.View.extend({
	tagName: 'div',
	className: 'release',
	template: tpl,
	initialize: function(){
		this.render();
	},
	render: function(){
		var html = this.template(this.model.toJSON());
		this.setElement(html);
		// this.$el.empty().html(html);
	}
});

module.exports = ReleaseView;