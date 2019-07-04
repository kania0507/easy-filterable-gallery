jQuery(document).ready(function( $ ) {
		var selectedClass = "";
		$(".filter-cat").click(function(){
            
            selectedClass = $(this).attr("data-rel");                 
            $("#gallery_container").fadeTo(100, 0.1);
            $("#gallery_container div").not("."+selectedClass).fadeOut().removeClass('scaleit');
            
            setTimeout(function() {
              $("."+selectedClass).fadeIn().addClass('scaleit');
              $("#gallery_container").fadeTo(300, 1);
            }, 300); 

	});
});