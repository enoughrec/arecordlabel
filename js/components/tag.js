/**
 * @jsx React.DOM
 */


var React = require('react');
var createReactClass = require('create-react-class');

var Router = require('react-router');
var Link = Router.Link;

var Tag = createReactClass({

    render: function() {
        var url = '/tag/' + this.props.tag;

        return (
            <Link className="tag fontawesome-tag" to={url}>{this.props.children}</Link>
        );
    }

});

module.exports = Tag;
