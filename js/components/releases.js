/**
 * @jsx React.DOM
 */

var React = require('react');
var InfiniteScroll = require('react-infinite-scroll')(React);

var Release = require('./release');

var Releases = React.createClass({
	loadMoreQuantity: 1,
	getInitialState: function(){
		return {
			items: [],
			hasMore: true
		};
	},
	resetState: function() {
		this.setState(this.getInitialState());
	},
	componentWillMount : function() {
		document.title = 'Enough Records';
		window.e = this.props.data;
		this.props.data.on("reset", this.resetState);
		// console.log(window.scrollY);
    },
    componentDidMount: function(){
    	// console.log(window.scrollY);
    },
    componentWillUnmount: function(){
    	this.props.data.off('reset', this.resetState);
    },
    getReleaseList: function(page){

    	var currentYear = false;
    	var offset = page * this.loadMoreQuantity;
    	

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

    	
		return releaseNodes;

		
    },
    updateList: function(page){

    	var releaseNodes = this.getReleaseList(page);
    	var offset = page * this.loadMoreQuantity;
    	
    	this.setState({
    		hasMore: this.props.data.length > offset,
    		items: releaseNodes
    	});
    },
	render: function(){
		
		var releaseNodes = this.state.items;

		return (
			<div className="release-holder"> 	
				<InfiniteScroll
					pageStart={1}
				    loadMore={this.updateList}
				    hasMore={this.state.hasMore}
				    loader={<div className="loader">Loading ...</div>}>
				  {releaseNodes}
				</InfiniteScroll>
			</div>
		)
	}
});

module.exports = Releases;