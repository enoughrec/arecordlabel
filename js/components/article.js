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
    render: function() {

        var json    = this.props.data.toJSON(),
            article = this.props.data, 
            time = readingTime(json.body);

        return (
            <div className="release-full article">
                <Link to="/blog">back</Link>
                <div className="centered-article">
                    <h1 className="article-title">{json.title}</h1>
                    <div className="article-date">{article.get('date').format('YYYY-MM-DD')}</div>
                    <div>{time.text}</div>
                    <div className="post" dangerouslySetInnerHTML={{__html: json.body}}></div>
                </div>
            </div>
        );
    }

});

module.exports = Article;
