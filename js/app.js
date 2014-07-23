/**
 * @jsx React.DOM
 */


var React = require('react');

var Releases = require('./components/releases');
var ReleaseDetail = require('./components/release-detail');
var About = require('./components/about');

var Topbar = require('./components/topbar');
var Bottombar = require('./components/bottombar');

var Router = require('react-router-component');
var Locations = Router.Locations;
var Location = Router.Location;


var App = React.createClass({
	getInitialState: function(){
		console.log('geting getInitialState')	
		return {
			fullData: this.props.data.clone()
		}
	},
	render: function(){
		
		return (
			<div className="app">
				<Topbar data={this.props.data} searchData={this.state.fullData} />
				<div className="main">
				 	<Locations>
						<Location path="/" handler={Releases} data={this.props.data} />
						<Location path="/release/:cat" handler={ReleaseDetail} data={this.props.data} />
						<Location path="/about" handler={About} data={this.props.data} />
			        </Locations>
		        </div>
				<Bottombar />
			</div>
    	)
	}
});


module.exports = App;