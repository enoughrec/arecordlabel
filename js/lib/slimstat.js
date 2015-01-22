var img = document.createElement('img');
img.setAttribute('style', 'position:absolute;top:-10px;left:0;z-index:-1;');
img.setAttribute('id', 'slimstatimg');
img.setAttribute('width', '1');
img.setAttribute('height', '1');
img.setAttribute('alt', '');

var inserted = false;

function SlimStat() {

    var ssSrc = '/slimstat/stats_js.php?ref=' + encodeURIComponent(document.referrer)
        + '&url=' + encodeURIComponent(document.URL)
        + '&res=' + encodeURIComponent(screen.width+'x'+screen.height)
        + '&ttl=' + encodeURIComponent(document.title)
        + '&ts=' + Date.now();

    
    img.setAttribute('src', ssSrc);

    if (!inserted) {
        if (document.documentElement) {
            document.documentElement.appendChild(img);
        } else {
            document.appendChild(img);
        }
    }
}

module.exports = SlimStat;
