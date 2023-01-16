/**
 * @jsx React.DOM
 */

var React = require('react');
var createReactClass = require('create-react-class');
var bus = require('../bus');
var Link = require('react-router').Link;
var Releases = require('../collections/releases');

var releases = new Releases();
releases.fullReset();

var ReleaseCover = createReactClass({
    componentWillMount: function(){
        var cat = this.props.cat;
        this.props.release = releases.findWhere({cat:cat});
    },
    startPlaying: function(){
        bus.emit('queue', this.props.release);
    },
    render: function() {
        var data = this.props.release.toJSON();
        var url = '/release/'+this.props.cat;
        return (
            <div className="release-cover">
                <div className="cover">
                    <div className="playbutton fa fa-play" onClick={this.startPlaying}></div>
                    <img src={data.cover} alt={data.album + ' - ' + data.artist} />
                </div>
                <Link to={url} className="titles">
                    <span className="album title">{data.album}</span>
                    <span className="artist title">{data.artist}</span>
                </Link>
            </div>
        );
    }

});

module.exports = ReleaseCover;
