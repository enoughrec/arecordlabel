/**
 * @jsx React.DOM
 */


var React = require('react');
var Releases = require('./components/releases');


var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection();

// reset to the full data store which the collection has a reference to
enrReleases.fullReset();

var App = React.createClass({
	render: function(){
		return (
			<div className="app">
				<div className="top-bar" />
				<Releases data={enrReleases} />
				<div className="bottom-bar" />
			</div>
    	)
	}
});


module.exports = App;