(function($) {
  'use strict';

  // Document ready!!!
  $(function() {

    var update_start_date = function(date) {
      $('.js-start-date-number').html($.datepicker.formatDate( 'dd', new Date(date)));
      $('.js-fl-start-month').html($.datepicker.formatDate( 'MM yy', new Date(date)));
    };

    var update_end_date = function(date) {
      $('.js-end-date-number').html($.datepicker.formatDate( 'dd', new Date(date)));
      $('.js-fl-end-month').html($.datepicker.formatDate( 'MM yy', new Date(date)));
    };

    var datePickerCallBack = function() {
      $('.js-input-modern').each(function () {
        var el = $(this);
        var start_date = new Date();

        if ( el.hasClass('awebooking-start-date') ) {
          el.datepicker('setDate', start_date);
        }

        if ( el.hasClass('awebooking-end-date') ) {
          var minDate = start_date;
          minDate.setDate(minDate.getDate() + 1);
          el.datepicker('setDate', minDate);
        }
      });
    };

    $(window).on("load", function(){
      var startDate = new Date();
      var endDate =  new Date();
      endDate.setDate(endDate.getDate() + 1);
      update_start_date(startDate);
      update_end_date(endDate);
      datePickerCallBack();
    });

    $('.js-input-modern').datepicker({
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      beforeShow: function() {
        $('#ui-datepicker-div').addClass('awebooking-datepicker');
      },
      onClose: function(date) {
        var el = $(this);
        if ( el.hasClass('awebooking-start-date') ) {
          update_start_date(date);
          var el_endDate = $('.awebooking-end-date');
          var startDate = $(this).datepicker('getDate');
          var minDate = $(this).datepicker('getDate');
          minDate.setDate(minDate.getDate() + 1);
          el_endDate.datepicker('setDate', minDate);
          el_endDate.datepicker('option', 'minDate', minDate);
          $('.js-end-date-number').html($.datepicker.formatDate( 'dd', new Date(minDate)));
          $('.js-fl-end-month').html($.datepicker.formatDate( 'MM yy', new Date(minDate)));
        }

        if ( el.hasClass('awebooking-end-date') ) {
          update_end_date(date);
        }
      },
    });

  });

})(jQuery);
