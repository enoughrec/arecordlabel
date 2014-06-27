/**
 * @jsx React.DOM
 */


var React = require('react');
var Releases = require('./components/releases');
var Topbar = require('./components/topbar');
var Bottombar = require('./components/bottombar');


var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection();

// reset to the full data store which the collection has a reference to
enrReleases.fullReset();

var App = React.createClass({
	getInitialState: function() {
    	return {data: enrReleases};
  	},
	render: function(){
		return (
			<div className="app">
				<Topbar data={this.state.data} />
				<Releases data={this.state.data} />
				<Bottombar />
			</div>
    	)
	}
});


module.exports = App;