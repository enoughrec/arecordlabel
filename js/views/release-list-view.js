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
		var currentYear = false;
		var yearVisible = false;
		this.collection.each(function(release){
			releaseyear = release.get('momented').year();
			if (releaseyear !== currentYear) {
				yearVisible = false;
				currentYear = releaseyear;
				this.$el.append('<h1 class="year-sep hidden" year="'+currentYear+'">'+currentYear+'</h1>');
			};
			if (!yearVisible && release.get('visible') === true) {
				yearVisible = true;
				this.$el.find('h1[year='+currentYear+']').removeClass('hidden');
			};

			var releaseView = new ReleaseView({
				model: release
			});

			this.$el.append(releaseView.el);

		},this);

		this.$el.removeClass('hidden');
	}
});

module.exports = ReleaseListView;