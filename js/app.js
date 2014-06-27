/**
 * @jsx React.DOM
 */


var React = require('react');
var Releases = require('./components/releases');
var _ = require('lodash');
var moment = require('moment');

var data = require('../data/all.json').reverse().map(function(item){
	item.key = "rel_"+_.uniqueId();
	item.momented = moment(item.release_date);
	return item;
});

var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection(data);

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