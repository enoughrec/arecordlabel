var bulk = require('bulk-require');
var _ = require('lodash');

var sections = bulk(__dirname, '../js/articles/**/*.fm');

var output = sections['..']['js']['articles'];

var articles = [];

_.each(output, function(article){
    articles.push(article);
});

module.exports = articles;
