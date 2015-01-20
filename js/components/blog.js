/**
 * @jsx React.DOM
 */


var React = require('react');

var Blog = React.createClass({
    renderTitles:function(){
        var articles = this.props.articles;
        var comps = articles.map(function(article){
            var json = article.toJSON();
            return (
                <div className="article-link">
                    <div className="article-title">{json.title}</div>
                    <div className="article-date">{article.get('date').format('YYYY-MM-DD')}</div>
                    <div className="post" dangerouslySetInnerHTML={{__html: json.body}}></div>
                </div>
            )
        });
        return comps;
    },
    render: function() {
        var listing = this.renderTitles();
        return (
            <div className="release-full article">
                <h1>Blog</h1>  
                {listing}
            </div>
        );
    }

});

module.exports = Blog;
