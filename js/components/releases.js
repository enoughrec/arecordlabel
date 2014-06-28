/**
 * @jsx React.DOM
 */

var React = require('react');
// var InfiniteScroll = require('react-infinite-scroll')(React);

var Release = require('./release');

var Releases = React.createClass({
	componentWillMount : function() {
		document.title = 'Enough Records';
		this.props.data.on("reset", function() {
			this.forceUpdate();
		}.bind(this));
    },
	render: function(){
		
		var currentYear = false;

		var releaseNodes = this.props.data.map(function(release){
			var yearSep = null;
			var releaseYear = release.get('momented').year();
			if (releaseYear !== currentYear) {
				yearSep = <h1 className="year-sep" key={releaseYear} data-year="{releaseYear}">{releaseYear}</h1>;
				currentYear = releaseYear;
			}
			return (
				<div key={release.get('cat')}>
					{yearSep}
					<Release data={release} />
				</div>
			);
		});

		return (
			<div className="release-holder">{releaseNodes}</div>
		)
	}
});

module.exports = Releases;