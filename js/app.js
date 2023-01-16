/**
 * @jsx React.DOM
 */

var React = require('react');
var createReactClass = require('create-react-class');

var Topbar = require('./components/topbar');
var Bottombar = require('./components/bottombar');
var Releases = require('./components/releases');

var App = createReactClass({
    getInitialState: function(){
        return {
            fullData: this.props.data.clone()
        };
    },
    render: function(){
        return (
            <div className="app">
                <Topbar data={this.props.data} searchData={this.state.fullData} />
                <div className="main">
                     {this.props.activeRouteHandler() || Releases({data:this.props.data})}
                </div>
                <Bottombar />
            </div>
        )
    }
});

module.exports = App;
