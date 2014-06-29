/**
 * @jsx React.DOM
 */

var React = require('react');
var InfiniteScroll = require('react-infinite-scroll')(React);

var Release = require('./release');

var Releases = React.createClass({
	loadMoreQuantity: 12,
	getInitialState: function(){
		return {
			items: [],
			hasMore: true
		};
	},
	componentWillMount : function() {
		document.title = 'Enough Records';
		window.e = this.props.data;
		this.props.data.on("reset", function() {
			this.setState(this.getInitialState());
		}.bind(this));
    },
    componentWillUnmount: function(){

    },
    getReleaseList: function(page){

    	var currentYear = false;
    	var offset = page * this.loadMoreQuantity;
    	var hasMore = this.props.data.length > offset;

    	var releaseNodes = this.props.data.first(offset).map(function(release){
			var yearSep = null;
			var releaseYear = release.get('momented').year();
			if (releaseYear !== currentYear) {
				yearSep = <h1 className="year-sep" key={'sep'+releaseYear} data-year="{releaseYear}">{releaseYear}</h1>;
				currentYear = releaseYear;
			}
			return (
				<div key={'holder'+release.get('cat')}>
					{yearSep}
					<Release key={release.get('cat')} data={release} />
				</div>
			);
		}.bind(this));

    	
		this.setState({
    		hasMore: hasMore,
    		items: releaseNodes
    	});	

		
    },
	render: function(){
		
		var releaseNodes = this.state.items;

		return (
			<div className="release-holder"> 	
				<InfiniteScroll
					pageStart={1}
				    loadMore={this.getReleaseList}
				    hasMore={this.state.hasMore}
				    loader={<div className="loader">Loading ...</div>}>
				  {releaseNodes}
				</InfiniteScroll>
			</div>
		)
	}
});

module.exports = Releases;