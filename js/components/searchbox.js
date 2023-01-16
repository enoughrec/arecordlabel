/**
 * @jsx React.DOM
 */

var React = require('react');
var createReactClass = require('create-react-class');
var _ = require('lodash');
var appState = require('../state');
var Router = require('react-router');

var Searchbox = createReactClass({
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
        this.setState({value: evt.target.value},this.queueSearch.bind(null, evt.target.value));
    },
    queueSearch: _.debounce(function(searchTerm){
        this.doSearch(searchTerm);
    },300),
    doSearch: function(searchTerm){
        appState.setSearch(searchTerm);
        
        if (searchTerm && !Router.ActiveState.statics.isActive('/')) {
            Router.transitionTo('home');
        }
        
    },
    render: function(){

        var hasSearch = this.state.value.length > 0;

        var controlClasses = React.addons.classSet({
            'fontawesome-remove': hasSearch,
            'fontawesome-search': !hasSearch
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
