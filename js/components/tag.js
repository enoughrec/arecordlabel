/**
 * @jsx React.DOM
 */

var Router = require('react-router');
var Link = Router.Link;

var React = require('react');

var Tag = React.createClass({

    render: function() {
        var url = '/tag/' + this.props.tag;

        return (
            <Link className="tag fontawesome-tag" to={url}>{this.props.children}</Link>
        );
    }

});

module.exports = Tag;
