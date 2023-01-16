/**
* @jsx React.DOM
*/


var React = require('react');
var createReactClass = require('create-react-class');
var Router = require('react-router');
var JSXRender = require('jsx-render');

// components available to articles
var Tag = require('./tag');
var Link = Router.Link;

var Release = require('./release-cover');

var Article = createReactClass({
    componentDidMount: function(){
        document.title = this.props.data.get('title');
    },
    render: function() {

        var json    = this.props.data.toJSON(),
            article = this.props.data, 
            time = json.readingTime.text;

        var date = article.get('date');
        var year = date.format('YYYY');
        var dayMonth = date.format('MMMM Do');
        var dateString = [dayMonth, (<br/>), year];

        var articleEnvironment = {
            tag: Tag,
            release: Release,
            link: Link,
            script: null
        };

        var renderedArticle = <JSXRender env={articleEnvironment} code={json.body} />;

        return (
            <div className="centered-article">
                <h1 className="article-title">{json.title}</h1>
                <div className="article-date">{dateString}</div>
                <div>{time}</div>
                <div className="post">{renderedArticle}</div>
            </div>
        );
    }

});

module.exports = Article;
