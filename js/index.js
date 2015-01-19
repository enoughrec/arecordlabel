/**
 * @jsx React.DOM
 */

var React = window.React = require('react');
var App = require('./app');

var ArticlesCollection = require('./collections/articles');
var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection();
enrReleases.fullReset(); // load data from our JSON

var Router = require('react-router');
var Routes = Router.Routes;
var Route = Router.Route;

var Releases = require('./components/releases');
var ReleaseDetail = require('./components/release-detail');
var About = require('./components/about');

// slim stat tracking
// the router calls it every time the route changes, so we track internal navigation 
// as well as just first page hits
var SlimStat = require('./lib/slimstat');

React.renderComponent(
	<Routes onActiveStateChange={SlimStat}>
		<Route name="home" path="/" handler={App} data={enrReleases}>
    		<Route name="tag"       path="tag/:tag" handler={Releases} data={enrReleases} />
			<Route name="release" 	path="release/:cat" handler={ReleaseDetail} data={enrReleases} />
			<Route name="about" 	path="about" handler={About} data={enrReleases} />
		</Route>
	</Routes>,
  	document.body
);
