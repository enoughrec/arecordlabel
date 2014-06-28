/**
 * @jsx React.DOM
 */

var React = window.React = require('react');
var App = require('./app');

var ReleasesCollection = require('./collections/releases');
var enrReleases = window.e = new ReleasesCollection();
enrReleases.fullReset();

React.renderComponent(
  <App name="Enough Records" data={enrReleases} />,
  document.body
);