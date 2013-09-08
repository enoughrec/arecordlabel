# arecordlabel

## Rebooted site for enough records (in progress)

Site is built using browserify, and is using npm.

### installation

To build the site, clone the repo, then:

`npm install && npm run-script build`

It will need to be running on a web server to work, or if you are on some *nix like system, can do this too:

`python -m SimpleHTTPServer`

..to get a server running on localhost:8000

### developing

While developing, can use:

`watchify -v -t hbsfy js/index.js -o build/build.js`

to watch the 



