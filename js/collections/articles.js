var Backbone = require('backbone');
var _ = require('lodash');
var slugify = require('slugify');
var moment = require('moment');
var readingTime = require('reading-time');
var url = require('url');

var convertLinks = require('../lib/convert-links');

var data = require('../../data/articles');

var Article = Backbone.Model.extend({
    
    parse: function(data){

        // convert html to text via browser parser
        var el = document.createElement('div');
        data.body = convertLinks(data.body);
        el.innerHTML = data.body;

        var text = el.textContent || el.innerText || '';

        var firstImage = el.querySelector('img');

        if (firstImage && firstImage.src) {
            firstImage = firstImage.src;
        } else {
            // use the blue e logo as a backup, if there is no image in the article
            firstImage = '/covers/enrmix17.jpg';
        }
        
        // snip long first paragraphs at 150 characters
        if (text.length > 153) {
           text = text.substr(0,150)+'...'; 
        }

        var data = {
            title: data.attributes.title,
            date: moment(data.attributes.date),
            body: data.body,
            slug: slugify(data.attributes.title).replace(/[:']/g, '').toLowerCase(),
            tags: data.tags,
            description: text,
            image: firstImage,
            readingTime: readingTime(data.body)
        }
        
        return data;
    }
});

var Articles = Backbone.Collection.extend({
    comparator: function(model1, model2) {
        return model1.get('date') > model2.get('date') ? -1 : 1;
    },
    model: Article,
    getBySlug: function(slug){
        return this.findWhere({
            slug: slug
        });
    }
});

var articles = new Articles(data, {parse: true});

// expose to window for playing
// @todo remove me when done!
// window.articles = articles;

module.exports = articles;
