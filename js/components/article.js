/**
* @jsx React.DOM
*/

/**
* @jsx React.DOM
*/

var React = require('react');
var Router = require('react-router');
var Link = Router.Link;


var Article = React.createClass({
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

        return (
            <div className="centered-article">
                <h1 className="article-title">{json.title}</h1>
                <div className="article-date">{dateString}</div>
                <div>{time}</div>
                <div className="post" dangerouslySetInnerHTML={{__html: json.body}}></div>
            </div>
        );
    }

});

module.exports = Article;
