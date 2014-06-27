/**
 * @jsx React.DOM
 */


var React = require('react');
var Releases = require('./components/releases');
var ReleaseDetail = require('./components/release-detail');
var Topbar = require('./components/topbar');
var Bottombar = require('./components/bottombar');


var Router = require('react-router-component');
var Locations = Router.Locations;
var Location = Router.Location;


var App = React.createClass({
	componentWillMount: function(){
		this.props.data.fullReset();
	},
	render: function(){
		// this.props.data.fullReset();
		return (
			<div className="app">
				<Topbar data={this.props.data} />
			 	<Locations>
					<Location path="/" handler={Releases} data={this.props.data} />
					<Location path="/release/:cat" handler={ReleaseDetail} data={this.props.data} />
		        </Locations>
				<Bottombar />
			</div>
    	)
	}
});


module.exports = App;