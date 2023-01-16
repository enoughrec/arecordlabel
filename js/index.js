/**
 * @jsx React.DOM
 */

var React = window.React = require('react');
var App = require('./app');
//TestApp  = React.createFactory(require('./app'))

var articles = require('./collections/articles');
var ReleasesCollection = require('./collections/releases');
var enrReleases = new ReleasesCollection();
enrReleases.fullReset(); // load data from our JSON

//var ReactDOM = require('react-dom');
var ReactDOM = require('react-dom/client');
var Router = require('react-router-dom');
var BrowserRouter = Router.BrowserRouter;
var Routes = Router.Routes;
var Route = Router.Route;

var Releases = require('./components/releases');
var ReleaseDetail = require('./components/release-detail');
var About = require('./components/about');
var Blog = require('./components/blog');
var Radio = require('./components/radio');


//React.RenderComponent(
ReactDOM.render(
  <BrowserRouter>
    <Routes location="history">
		<Route name="home" path="/" handler={App} data={enrReleases}>
			<Route name="tag"       path="tag/:tag" handler={Releases} data={enrReleases} />
			<Route name="release"   path="release/:cat" handler={ReleaseDetail} data={enrReleases} />
			<Route name="about"     path="about" handler={About} data={enrReleases} />
			<Route name="blog"      path="blog" handler={Blog} articles={articles} data={enrReleases} />
			<Route name="blogPage"  path="blog/:titleSlug" handler={Blog} articles={articles} data={enrReleases} />
			<Route name="radio"     path="radio" handler={Radio} data={enrReleases} />
		</Route>
	</Routes>
  </BrowserRouter>,
  root
);


