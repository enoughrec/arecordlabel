/**
 * @jsx React.DOM
 */


var React = require('react');
var _ = require('lodash');

var Searchbox = React.createClass({
	getInitialState: function() {
		return {value: ''};
	},
	keys: {
		13: 'enter',
		27: 'escape'
	},
	componentWillMount: function(){
		console.log('sb: ',this.props)
	},
	handleChange: function(evt){
		this.setState({value: event.target.value},this.doSearch.bind(null, event.target.value));

	},
	doSearch: _.debounce(function(searchTerm){
		var results = this.props.searchData.search(searchTerm);
		this.props.data.reset(results.toJSON());
	},300),
	render: function(){
		return (
			<input onChange={this.handleChange} value={this.state.value}/>
		)
	}
});

module.exports = Searchbox;