/**
 * @jsx React.DOM
 */


var React = require('react/addons');
var _ = require('lodash');

var Searchbox = React.createClass({
	getInitialState: function() {
		return {value: ''};
	},
	keys: {
		13: 'enter',
		27: 'escape'
	},
	resetValue: function(){
		this.setState({
			value: ''
		}, this.doSearch);
	},
	componentWillMount: function(){
		console.log('sb: ',this.props)
	},
	onKeyUp: function(evt){
		var keyPressed = this.keys[evt.keyCode];
		switch(keyPressed) {
			case 'escape': 
				this.resetValue();
				break;
		}
	},
	handleChange: function(evt){
		this.setState({value: event.target.value},this.queueSearch.bind(null, event.target.value));
	},
	queueSearch: _.debounce(function(searchTerm){
		this.doSearch(searchTerm);
	},300),
	doSearch: function(searchTerm){
		var results = this.props.searchData.search(searchTerm);
		this.props.data.reset(results.toJSON());
	},
	render: function(){

		var controlClasses = React.addons.classSet({
			'fontawesome-remove':true,
			'hidden': this.state.value.length == 0
		});
		
		return (
			<div className="top-bar-searchbox">
				<input onKeyUp={this.onKeyUp} onChange={this.handleChange} value={this.state.value}/>
				<div className={controlClasses} onClick={this.resetValue} />
			</div>
			
		)
	}
});

module.exports = Searchbox;