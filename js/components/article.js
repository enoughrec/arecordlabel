/**
* @jsx React.DOM
*/

/**
* @jsx React.DOM
*/

var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var readingTime = require('reading-time');

var Article = React.createClass({
    componentDidMount: function(){
        document.title = this.props.data.get('title');
    },
    render: function() {

        var json    = this.props.data.toJSON(),
            article = this.props.data, 
            time = readingTime(json.body);

        return (
            <div className="centered-article">
                <h1 className="article-title">{json.title}</h1>
                <div className="article-date">{article.get('date').format('MMMM Do YYYY')}</div>
                <div>{time.text}</div>
                <div className="post" dangerouslySetInnerHTML={{__html: json.body}}></div>
            </div>
        );
    }

});

module.exports = Article;
