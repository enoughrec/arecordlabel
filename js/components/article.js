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


    render: function() {

        var json    = this.props.data.toJSON(),
            article = this.props.data;

        return (
            <div className="release-full article">
                <Link to="/blog">back</Link>
                <div className="article-title">{json.title}</div>
                <div className="article-date">{article.get('date').format('YYYY-MM-DD')}</div>
                <div className="post" dangerouslySetInnerHTML={{__html: json.body}}></div>
            </div>
        );
    }
 
 });
 
 module.exports = Article;
