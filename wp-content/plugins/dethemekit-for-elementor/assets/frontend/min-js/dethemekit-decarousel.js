// var $carouselElem = find(".de-carousel-slider-parent")
// $carouselElem.find(".de-carousel-slider-parent .de-carousel-slider-childs").each(function (index, slide) {
$(document).ready( function() {
   
    $('.dethemekit_child_de_carousel_1 .dethemekit_child_de_carousel_1').click(function() {
        // alert($(this).index());
        $('.dethemekit_parent_de_carousel_1 .dethemekit-carousel-inner').slick('slickGoTo',$(this).index());
        $('.dethemekit_child_de_carousel_1 .dethemekit_child_de_carousel_1').removeClass('de-carousel-active');
        $('.dethemekit_child_de_carousel_1 .dethemekit_child_de_carousel_1').eq($(this).index()).addClass('de-carousel-active');
    })

    $('.dethemekit_child_de_carousel_2 .dethemekit_child_de_carousel_2').click(function() {
        // alert($(this).index());
        $('.dethemekit_parent_de_carousel_2 .dethemekit-carousel-inner').slick('slickGoTo',$(this).index());
        $('.dethemekit_child_de_carousel_2 .dethemekit_child_de_carousel_2').removeClass('de-carousel-active');
        $('.dethemekit_child_de_carousel_2 .dethemekit_child_de_carousel_2').eq($(this).index()).addClass('de-carousel-active');
    })

    $('.dethemekit_child_de_carousel_3 .dethemekit_child_de_carousel_3').click(function() {
        // alert($(this).index());
        $('.dethemekit_parent_de_carousel_3 .dethemekit-carousel-inner').slick('slickGoTo',$(this).index());
        $('.dethemekit_child_de_carousel_3 .dethemekit_child_de_carousel_3').removeClass('de-carousel-active');
        $('.dethemekit_child_de_carousel_3 .dethemekit_child_de_carousel_3').eq($(this).index()).addClass('de-carousel-active');
    })

    $('.dethemekit_child_de_carousel_4 .dethemekit_child_de_carousel_4').click(function() {
        // alert($(this).index());
        $('.dethemekit_parent_de_carousel_4 .dethemekit-carousel-inner').slick('slickGoTo',$(this).index());
        $('.dethemekit_child_de_carousel_4 .dethemekit_child_de_carousel_4').removeClass('de-carousel-active');
        $('.dethemekit_child_de_carousel_4 .dethemekit_child_de_carousel_4').eq($(this).index()).addClass('de-carousel-active');
    })

    
})
