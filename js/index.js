/**
 * @jsx React.DOM
 */

var React = window.React = require('react');
var App = require('./app');

var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection();
enrReleases.fullReset();

var Router = require('react-router');
var Routes = Router.Routes;
var Route = Router.Route;


var ReleaseDetail = require('./components/release-detail');
var About = require('./components/about');


React.renderComponent(
	<Routes>
		<Route name="home" path="/" handler={App} data={enrReleases}>
			<Route name="release" 	path="release/:cat" handler={ReleaseDetail} data={enrReleases} />
			<Route name="about" 	path="about" handler={About} data={enrReleases} />
		</Route>
	</Routes>,
  	document.body
);