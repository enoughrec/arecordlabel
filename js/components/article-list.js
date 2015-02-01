/**
 * @jsx React.DOM
 */

var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var ArticleList = React.createClass({
    renderTitles:function(){
        var articles = this.props.articles;
        var comps = articles.map(function(article, idx){


            var json = article.toJSON();
            var bgStyle = {
                backgroundImage: 'url('+json.image+')'
            };

            var date = article.get('date');
            var year = date.format('YYYY');
            var dayMonth = date.format('MMMM Do');
            var dateString = [dayMonth, (<br/>), year];
            var key = idx;

            return (
                <div className="list-article" key={key}>
                    <Link to={'/blog/' + json.slug} style={bgStyle} className="image-link"></Link>
                    <div className="right-content">
                        <Link to={'/blog/' + json.slug}><h1 className="title">{json.title}</h1></Link>
                        <div className="date">{dateString}</div>
                        <div className="reading-time">{json.readingTime.text}</div>
                        <div className="description">{json.description}</div>
                    </div>
                </div>
            )
        });
        return comps;
    },
    render: function() {
        var list = this.renderTitles();
        return (
            <div>
                {list}
            </div>
        );
    }

});

module.exports = ArticleList;
