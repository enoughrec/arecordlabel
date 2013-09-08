var Backbone = require('backbone');
var ReleaseView = require('./release-view');

var ReleaseListView = Backbone.View.extend({
	tagName: 'div',
	className: 'release-holder',
	initialize: function(){
		
		this.collection.on('sort',this.render.bind(this));
	},
	render: function(){
		
		this.$el.empty().addClass('hidden');
		this.collection.each(function(release){
			var releaseView = new ReleaseView({
				model: release
			});

			this.$el.append(releaseView.el);

		},this);

		this.$el.removeClass('hidden');
	}
});

module.exports = ReleaseListView;