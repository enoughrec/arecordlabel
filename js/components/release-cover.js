/**
 * @jsx React.DOM
 */

var React = require('react');
var bus = require('../bus');

var Releases = require('../collections/releases');

var releases = new Releases();
releases.fullReset();

var ReleaseCover = React.createClass({
    componentWillMount: function(){
        var cat = this.props.cat;
        this.props.release = releases.findWhere({cat:cat});
    },
    startPlaying: function(){
        bus.emit('queue', this.props.release);
    },
    render: function() {
        var data = this.props.release.toJSON();

        return (
            <div className="release-cover">
                <div className="cover">
                    <div className="playbutton fontawesome-play" onClick={this.startPlaying}></div>
                    <img src={data.cover} alt={data.album + ' - ' + data.artist} />
                </div>
                <div className="titles">
                    <span className="album title">{data.album}</span>
                    <span className="artist title">{data.artist}</span>
                </div>
            </div>
        );
    }

});

module.exports = ReleaseCover;
