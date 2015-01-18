var img = document.createElement('img');
img.setAttribute('id', 'slimstatimg');
img.setAttribute('width', '1');
img.setAttribute('height', '1');
img.setAttribute('alt', '');

var inserted = false;

function SlimStat() {
    var ref = escape(document.referrer);
    var res = escape(window.screen.availHeight + 'x' + window.screen.availWidth);
    var url = escape(window.location.href);
    
    img.setAttribute('src', '/slimstat/stats_js.php?ref=' + ref + '&res=' + res + '&url=' + url + '&r=' + Math.floor(Math.random() * 10000));

    if (!inserted) {
        if (document.documentElement) {
            document.documentElement.appendChild(img);
        } else {
            document.appendChild(img);
        }
    }
}

module.exports = SlimStat;
