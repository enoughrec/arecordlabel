var currentHost = document.location.host;
var url = require('url');

module.exports = function convertLinks(html){
    var frag = document.createElement('div');
    frag.innerHTML = html;

    // convert all non relative or site links to open in new window
    var links = frag.querySelectorAll('a');

    [].forEach.call(links, function(link){
        var href = link.href;
        var parsedUrl = url.parse(href);
        if (parsedUrl.host !== currentHost) {
            link.setAttribute('target', '_blank');
        }
    });

    return frag.innerHTML;
}
