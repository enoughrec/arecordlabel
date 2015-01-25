/**
 * @jsx React.DOM
 */

var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var ArticleList = React.createClass({
    renderTitles:function(){
        var articles = this.props.articles;
        var comps = articles.map(function(article){
            var json = article.toJSON();
            return (
                <Link className="article-link" to={'/blog/' + json.slug}>
                    <h1 className="title">{json.title}</h1>
                    <div className="date">{article.get('date').format('YYYY-MM-DD')}</div>
                </Link>
            )
        });
        return comps;
    },
    render: function() {
        var list = this.renderTitles();
        return (<div>{list}</div>);
    }

});

module.exports = ArticleList;
