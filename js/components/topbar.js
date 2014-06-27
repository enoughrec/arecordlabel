/**
 * @jsx React.DOM
 */


var React = require('react');
var Router = require('react-router-component');

var Locations = Router.Locations;
var Location = Router.Location;
var Link = Router.Link;

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
				<span>
					<Link href={link.link } hash>{link.label}</Link>
				</span>
			)
		});
		comps.push(<span><a href="http://enoughrecords.org">Blog</a></span>);

		return (
			<div className="top-bar">
				{comps}
			</div>
		);
	}
})

module.exports = Topbar;