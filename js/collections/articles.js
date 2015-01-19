var Backbone = require('backbone');
var _ = require('lodash');

var moment = require('moment');

var data = require('../../data/articles');

var Article = Backbone.Model.extend({
    parse: function(attrs){
        var data = {
            title: attrs.attributes.title,
            date: moment(attrs.attributes.date),
            body: attrs.body
        }
        return data;
    }
});

var Articles = Backbone.Collection.extend({
    model: Article
});

var articles = new Articles(data, {parse: true});
window.a = articles;


