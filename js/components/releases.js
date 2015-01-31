/**
 * @jsx React.DOM
 */

var React = require('react/addons');
var InfiniteScroll = require('react-infinite-scroll')(React);
var cx = React.addons.classSet;
var Release = require('./release');

var appState = require('../state');

var Tag = require('./tag');

var Releases = React.createClass({
    loadMoreQuantity: 12,
    getInitialState: function(){
        return {
            filteredReleases: [],
            items: [],
            hasMore: true
        };
    },
    getTagFilter: function(){
        return this.props.params && this.props.params.tag;
    },
    
    componentWillMount : function() {
        window.scrollTo(0,0);
        document.title = 'Enough Records';
        window.e = this.props.data;
        appState.on('change:userSearch', this.updateSearch);
        this.updateSearch(null, appState.get('userSearch'));

        // are we filtering on a tag?
        if (this.getTagFilter()) {
            this.setState({
                filteredReleases: this.props.data.searchByTag(this.getTagFilter())
            }, this.updateList.bind(this, 1));
        }
    },
    componentWillUnmount: function(){
        appState.off('change:userSearch', this.updateSearch);
    },
    componentDidUpdate: function(){
        var tag = this.getTagFilter();
        if (tag) {
            document.title = 'Releases tagged as:' + tag;
        } else {
            document.title = 'Enough Records';
        }
    },
    updateSearch: function(model, value){
        window.scrollTo(0,0);
        this.setState({
            filteredReleases: this.props.data.search(value)
        }, this.updateList.bind(this, 0));
    },
    getReleaseList: function(page){

        var currentYear = false;
        var offset = page * this.loadMoreQuantity;

        var releaseNodes = this.state.filteredReleases.first(offset).reduce(function(nodes, release){
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
            hasMore: this.state.filteredReleases.length > offset,
            items: releaseNodes
        });
    },
    render: function(){
        
        var releaseNodes = this.state.items;
        var tag = this.getTagFilter();
        if (tag) {
            tag = (
                <div className='top-heading'>
                    <Tag tag={tag}>{tag}</Tag>
                </div>
            );
        };

        return (
            <div className="release-holder">
                {tag}  
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
