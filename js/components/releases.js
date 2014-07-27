/**
 * @jsx React.DOM
 */

var React = require('react/addons');
var InfiniteScroll = require('react-infinite-scroll')(React);
var cx = React.addons.classSet;
var Release = require('./release');

var Releases = React.createClass({
	loadMoreQuantity: 12,
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
		window.scrollTo(0,0);
    },
    componentWillUnmount: function(){
    	this.props.data.off('reset', this.resetState);
    },
    getReleaseList: function(page){

    	var currentYear = false;
    	var offset = page * this.loadMoreQuantity;

    	var releaseNodes = this.props.data.first(offset).reduce(function(nodes, release){
			var yearSep = null;
				

			var releaseYear = release.get('momented').year();
			if (releaseYear !== currentYear) {
				yearSep = <h1 className="year-sep" key={'sep'+releaseYear}>{releaseYear}</h1>;
				nodes.push(<br/>);
				nodes.push(yearSep);
				currentYear = releaseYear;
			}
			
			nodes.push(<Release key={release.get('cat')} data={release} />);
			return nodes;
		}.bind(this), []);

    	
		return releaseNodes;

		
    },
    resetLoading: function(){
    	this.setState({
    		loading: false
    	});
    },
    updateList: function(page){

    	var releaseNodes = this.getReleaseList(page);
    	var offset = page * this.loadMoreQuantity;
    	this.setState({
    		loading: true,
    		hasMore: this.props.data.length > offset,
    		items: releaseNodes
    	}, this.resetLoading);
    },
	render: function(){
		
		var releaseNodes = this.state.items;
		var loaderClasses = cx({
    		'hidden':!this.state.loading,
    		'loader':true
    	});

		return (
			<div className="release-holder"> 	
				<InfiniteScroll
					threshold={330}
					pageStart={1}
				    loadMore={this.updateList}
				    hasMore={this.state.hasMore}>
				    
				  {releaseNodes}
				</InfiniteScroll>
			</div>
		)
	}
});

module.exports = Releases;