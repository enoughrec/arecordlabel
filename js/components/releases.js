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
	resetState: function() {
		this.setState(this.getInitialState());
	},
	componentWillMount : function() {
		document.title = 'Enough Records';
		window.e = this.props.data;
		this.props.data.on("reset", this.resetState);
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
    updateList: function(page){

    	var releaseNodes = this.getReleaseList(page);
    	var offset = page * this.loadMoreQuantity;
    	this.setState({
    		hasMore: this.props.data.length > offset,
    		items: releaseNodes
    	});
    	// setTimeout(this.setState.bind(this,),66)
    	
    },
	render: function(){
		
		var releaseNodes = this.state.items;

		return (
			<div className="release-holder"> 	
				<InfiniteScroll
					threshold={330}
					pageStart={1}
				    loadMore={this.updateList}
				    hasMore={this.state.hasMore}
				    loader={<div className="loader"></div>}>
				  {releaseNodes}
				</InfiniteScroll>
			</div>
		)
	}
});

module.exports = Releases;