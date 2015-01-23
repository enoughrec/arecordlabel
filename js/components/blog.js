/**
 * @jsx React.DOM
 */


var React = require('react');
var Link = require('react-router').Link;

var Article = require('./article');

var Blog = React.createClass({
    renderTitles:function(){
        var articles = this.props.articles;
        var comps = articles.map(function(article){
            var json = article.toJSON();
            return (
                <Link className="article-link" to={'/blog/' + json.slug}>
                    <div className="article-title">{json.title}</div>
                    <div className="article-date">{article.get('date').format('YYYY-MM-DD')}</div>
                </Link>
            )
        });
        return comps;
    },
    render: function() {
        if (this.props.params.titleSlug) {
            var article = this.props.articles.getBySlug(this.props.params.titleSlug);
            if (!article) {
                this.transitionTo('/');
            } else {
                return (
                    <Article data={article} />
                );    
            }
            
        } else {

            var listing = this.renderTitles();

            return (
                <div className="release-full article">
                    <h1>Blog</h1>
                    {listing}
                </div>
            );    
        }
        
    }

});

module.exports = Blog;
