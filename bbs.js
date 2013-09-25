var filter_link = function(){
    var pattern = /((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d\.\\:#@%\/;$()~_?\+\-=&]*)/g;
    $('.border').html('<pre>' + $('.border').html().replace(pattern, function(url){
		return $('<span></span>').append(
		    $('<a></a>')
		    .attr('target', '_blank')
		    .attr('href', url)
		    .text(url)
		    .attr('rel', 'nofollow')
		    ).html();
    }) + '</pre>');
};

var filter_doublecolor = function(){
    if (!$('.border .right_word').length) {
	return;
    }
    var width = $('.border .right_word').width();
    $('.border .right_word').each(function(){
	var self = $(this);
	self.show();
	self.html($('<span>').text(self.text()).css("margin-left", -1 * Math.floor(width / 2))).css({
	    overflow: 'hidden',
	    display: 'inline',
	    left: self.position().left - Math.floor(width / 2),
	    width: Math.floor(width / 2)
	});
    });
};

filter_link();
filter_doublecolor();
