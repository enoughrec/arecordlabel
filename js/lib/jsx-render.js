// code from jsx-render package by elie rotenberg
// not using the package as it references an old version of react
// and updating it to convert some HTML attributes to DOM

var _ = require("lodash");
var htmlparser = require("htmlparser2");
var React = require("react");
var ReactDOM = React.DOM;
var assert = require("assert");

var parseJSX = function parseJSX(source, env) {
    env = _.extend({}, React.DOM, env);
    var root = {
        parent: null,
        children: []
    };
    var curr = root;
    var parser = new htmlparser.Parser({
        onopentag: function onopentag(tagName, attribs) {
            attribs = attribs || {};
            curr = {
                parent: curr,
                children: [],
                tagName: tagName,
                attribs: attribs,
            };

            // convert html class attribute into DOM attribute
            if (attribs.class) {
                attribs.className = attribs.class;
                delete attribs.class;
            }

        },
        onclosetag: function onclosetag(tagName) {
            curr.parent.children.push(curr);
            curr = curr.parent;
        },
        ontext: function ontext(text) {
            curr.children.push(text);
        },
    }, {
        xmlMode: true,
    });
    var bfsConstruct = function bfsConstruct(curr) {
        if(!_.has(env, curr.tagName)) {
            throw new Error("Unknown tag " + curr.tagName);
        }
        else if(env[curr.tagName] === null) {
            throw new Error("Forbidden tag " + curr.tagName);
        }
        return env[curr.tagName].apply(null, [curr.attribs].concat(_.map(curr.children, function(child) { return _.isString(child) ? child : bfsConstruct(child); })));
    };
    parser.write(source);
    parser.end();
    return _.map(root.children, function(child) {
        return bfsConstruct(child);
    });
};

var JSXRender = React.createClass({
    displayName: "JSXRender",
    propTypes: {
        env: React.PropTypes.object,
        code: React.PropTypes.string.isRequired,
    },
    shouldComponentUpdate: function shouldComponentUpdate(nextProps) {
        return !(_.isEqual(this.props.env, nextProps.env) && (this.props.code === nextProps.code));
    },
    render: function render() {
        var env = this.props.env || {};
        var code = this.props.code;
        try {
            return ReactDOM.div.apply(null, [{ className: "JSXRender" }].concat(parseJSX(code, env)));
        }
        catch(e) {
            debugger;
            return ReactDOM.div({ className: "JSXRender JSXRender-error" }, "Error: ", e.toString());
        }
    },
});

module.exports = JSXRender;
