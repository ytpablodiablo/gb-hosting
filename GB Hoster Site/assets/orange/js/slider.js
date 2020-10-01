$(document).ready(function(){
  var currentPosition = 0;
  var slideWidth = 520;
  var slides = $('.slide');
  var numberOfSlides = slides.length;

  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl"></span>')
    .append('<span class="control" id="rightControl"></span>');

  // Hide left arrow control on first load
  manageControls(currentPosition);

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    
	// Hide / show controls
    manageControls(currentPosition);		
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    });	
  });
  
  var timerl = setInterval(function() {
	var test = Math.floor(Math.random() * numberOfSlides) + 0;
    manageControls(test);		
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-test)
    });		
  }, 5000);
  


  // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0){ 
		//clearTimeout(timerl)
		$('#leftControl').hide();
	}
	else{
		//clearTimeout(timerr)
		$('#leftControl').show();
		//var timerl = setTimeout(function() {$('#leftControl.control').trigger('click');}, 5000);
	}
	// Hide right arrow if position is last slide
    if(position==numberOfSlides-1){ 
		//clearTimeout(timerr)
		$('#rightControl').hide(); 
	} 
	else
	{ 
		//clearTimeout(timerl)
		$('#rightControl').show();
		//var timerr = setTimeout(function() {$('#rightControl.control').trigger('click');}, 5000);
	}
  }	
});