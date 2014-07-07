/**
 * @jsx React.DOM
 */


var React = require('react');
var Router = require('react-router-component');

var Locations = Router.Locations;
var Location = Router.Location;
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
		}];

		var comps = links.map(function(link){
			return (
				<span key={link.label}>
					<Link href={link.link }>{link.label}</Link>
				</span>
			)
		});
		comps.push(<span key={"bloglink"}><a target="_blank" href="http://enoughrecords.org">Blog</a></span>);

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