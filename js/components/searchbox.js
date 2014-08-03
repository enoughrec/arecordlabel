/**
 * @jsx React.DOM
 */


var React = require('react/addons');
var _ = require('lodash');
var appState = require('../state');

var bus = require('../bus');

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
		appState.setSearch(searchTerm);
	},
	render: function(){

		var controlClasses = React.addons.classSet({
			'fontawesome-remove':true,
			'hidden': this.state.value.length == 0
		});
		
		return (
			<div className="top-bar-searchbox">
				<input onKeyUp={this.onKeyUp} onChange={this.handleChange} value={this.state.value} placeholder="search releases"/>
				<div className={controlClasses} onClick={this.resetValue} />
			</div>
			
		)
	}
});

module.exports = Searchbox;