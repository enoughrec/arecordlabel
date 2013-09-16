var Backbone = require('backbone');
var tpl = require('../templates/release.hbs');

var ReleaseView = Backbone.View.extend({
	tagName: 'div',
	className: 'release',
	template: tpl,
	initialize: function(){
		this.render();
		this.model.on('change:visible', this.toggleVisible.bind(this));
		
	},
	toggleVisible: function(){
		this.$el.toggleClass('hidden', !this.model.get('visible'));
	},
	render: function(){
		var html = this.template(this.model.toJSON());
		this.setElement(html);
	}
});

module.exports = ReleaseView;
