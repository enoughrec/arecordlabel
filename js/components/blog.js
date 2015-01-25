/**
 * @jsx React.DOM
 */


var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var ArticleList = require('./article-list');
var Article = require('./article');

var Blog = React.createClass({
    getArticle: function(slug){
        return this.props.articles.getBySlug(slug);
    },
    render: function() {
        var toRender;
        var slug = this.props.params.titleSlug;
        
        var article = this.getArticle(slug);
        if (slug) {
            if (article) {
                toRender = <Article data={article} />;
            } else {
                Router.transitionTo('blog');
            }
        } else {
            toRender = <ArticleList articles={this.props.articles} />
        }
        return (
            <div className="release-full article">
                {toRender}
            </div>
        );    
    }

});

module.exports = Blog;
