var Backbone = require('backbone');
var ReleaseView = require('./release-view');

var ReleaseListView = Backbone.View.extend({
	tagName: 'div',
	className: 'release-holder',
	initialize: function(){
		// this.render();
	},
	render: function(){
		
		this.collection.each(function(release){

			var releaseView = new ReleaseView({
				model: release
			});

			this.$el.append(releaseView.el);
			
		},this);
	}
});

module.exports = ReleaseListView;