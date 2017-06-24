/**
 * @jsx React.DOM
 */

var React = require('react/addons');

var Tag = require('./tag');

var TagList = React.createClass({
    getTags:function(){
        return this.props.data.getTags().map(function(tag){
            return (<Tag tag={tag}>{tag}</Tag>);
        });
    },
    getCountries:function(){
        return this.props.data.getCountries().map(function(country){
            var country = country.replace('.','');
            return (<img className="artist-country" title="" src={"/flags/_flag_" + country + ".png"} />);
        });
    },
    render: function() {

        return (
            <div className="release-full article">
                <div className="centered-article">
                    
                    <div className="post">
                        <h1 className="article-title">Countries</h1>
                        <p>{this.getCountries()}</p>
                        <h1 className="article-title">Tags</h1>
                        <p>{this.getTags()}</p>
                    </div>
                </div>
            </div>
        );
    }

});

module.exports = TagList;
