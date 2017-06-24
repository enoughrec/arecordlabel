/**
 * @jsx React.DOM
 */


var React = require('react');
var Router = require('react-router');

var Locations = Router.Routes;
var Location = Router.Route;
var Link = Router.Link;

var Searchbox = require('./searchbox');

var Topbar = React.createClass({
    render: function(){
        
        var links = [{
            link:'/',
            label:'Enough Records'
        }, {
            link: '/about',
            label: 'About'
        }, {
            link: '/tag',
            label: 'Tags'
        }, {
            link: '/blog',
            label: 'Blog'
        }];

        var comps = links.map(function(link){
            return (
                <span key={link.label}>
                    <Link to={link.link }>{link.label}</Link>
                </span>
            )
        });
        // comps.push(<span key={"bloglink"}><a target="_blank" href="https://web.archive.org/web/20141217092309/http://enoughrecords.org/">Blog</a></span>);

        return (
            <div className="top-bar">
                {comps}
                <div className="right">
                    <Searchbox data={this.props.data} searchData={this.props.searchData}/>
                </div>
            </div>
        );
    }
})

module.exports = Topbar;
