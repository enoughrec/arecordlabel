/**
 * @jsx React.DOM
 */

var React = window.React = require('react');
var App = require('./app');

React.renderComponent(
  <App name="Enough Records" />,
  document.body
)