
var pluralize = function(num, single, plural) {
	if (parseInt(num, 10) === 1) {
		return single;
	} else {
		return plural;
	}
});

var removeDot = function(word) {
	if (word) {
		if (word[0] === '.') {
			word = word.substr(1, word.length - 1)
		};
	};

	return word;
});

var formatTitle = function(ctx){
	var artist = ctx.artist ? ctx.artist : '';
	var album = ctx.album ? ctx.album : '';

	var formattedTitle = false;

	if (album && artist) {
		formattedTitle = "["+ctx.cat+"] "+artist+" - "+album;
	} else if (album && !artist){
		formattedTitle = album;
	} else if (artist && !album) {
		formattedTitle = artist;
	}

	return formattedTitle;
});

module.exports = {
	pluralize: pluralize,
	removeDot: removeDot,
	formatTitle: formatTitle
}; 
