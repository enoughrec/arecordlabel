var Backbone = require('backbone');
var _  = require('underscore'); // augmented by _.str in index.js



var ReleaseModel = Backbone.Model.extend({
	// initialize: function(hash){
		
	// },
	parse: function(hash){
		
		_.each(hash,function(item,key){

			item = _.str.clean(item);
			item = item.length ? item : false;
			this[key] = item;

		},hash);
		
		return hash;
	},
	defaults: {
		album: "no name",
		artist: "no artist",
		bandcamp: null,
		cat: "ENR",
		clearbits: false,
		cover: "http://tpolm.org/~ps/enough/covers/enrmp001.jpg",
		discogs: false,
		download: false,
		info_en: false,
		info_pt: false,
		jamendo: false,
		release_date: "2006-11-01",
		scene_org: false,
		sonicsquirrel: false,
		soundcloud: false,
		tags: [],
		visible: false
	}
});

module.exports = ReleaseModel;