
var xhr;
var page = 1;
var real_search_txt = '';
$(function () {
  // $.uniform.restore("[type='checkbox']");
  $.uniform.restore("select");

  if (!!window.chrome) {
    setTimeout(() => {

      $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
      $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
    }, 500);

  } else {
    setTimeout(() => {

      $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
      $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
    }, 0);

  }

  document.addEventListener("visibilitychange", function () {
    // console.log(document.hidden, document.visibilityState);
    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
  }, false);

  $(document).on('click', '.pagination li a', function (e) {
    e.preventDefault();
    var href = $(this).prop('href');
    arr = href.split('/');
    page = arr[arr.length - 1];
    if (!isNaN(page)) {
      // page_link_click = true;
      update_listing_based_on_filteration();
      $(this).blur();
    }
    return false;
  });

  $(document).on('click', '.search_btn', function () {
    try {
      $("#search_keyword").tagsinput('add', real_search_txt);
      $("#search_keyword").parent().find('.bootstrap-tagsinput input').val('').focus();
      $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
    } catch (err) {
      console.log(err);
    }

  });

  $('input[data-role=tagsinput]').on('itemAdded', function (event) {
    real_search_txt = '';
    page = 1;
    all_category_set = false;
    var items = $(this).tagsinput('items');
    if (items.length > 0) {
      $(this).parent().find('.bootstrap-tagsinput').find('input').attr('placeholder', '');
    }
    // event.item: contains the item
    // update_listing_based_on_filteration();
    total_result = 1;
  });
  // $('#autocomplete').on('itemAdded', function (event) {
  //   real_search_txt = '';
  //   page = 1;
  //   var items = $(this).tagsinput('items');
  //   if (items.length > 0) {
  //     $(this).parent().find('.bootstrap-tagsinput').find('input').attr('placeholder', '');
  //   }
  //   // event.item: contains the item
  //   update_listing_based_on_filteration();
  // });
  $('input[data-role=tagsinput], #autocomplete').on('itemRemoved', function (event) {
    // event.item: contains the item
    page = 1;
    all_category_set = false;
    var items = $(this).tagsinput('items');
    if (items.length == 0) {
      //   if ($(this).attr('id') == 'autocomplete') {
      //     $(this).parent().find('.bootstrap-tagsinput').find('input').attr('placeholder', 'Search Location');
      //   } else {
      $(this).parent().find('.bootstrap-tagsinput').find('input').attr('placeholder', 'Search keyword');
      //   }
    }
    update_listing_based_on_filteration();
  });

  // manage search type for manging search result
  $(document).on('click', '.search_type', function () {
    var id = $(this).attr('id');

    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');

    var flag = false;
    if ($(this).prop('checked')) {
      flag = true;
    }

    $('.search_type').prop('checked', false);
    $(this).prop('checked', true);
    page = 1;
    if ($('#search_keyword').tagsinput('items').length > 1 && flag) {
      update_listing_based_on_filteration();
    }
  });

  // clearall search text and location filter
  $(document).on('click', '.search_clear', function () {
    real_search_txt = '';

    var update_flag = false;
    if ($('input[data-role=tagsinput]').tagsinput('items').length > 0) {
      update_flag = true;
    }

    if ($('.bootstrap-tagsinput input').val() != '') {
      update_flag = true;
    }
    $('.bootstrap-tagsinput input').val('');
    $("input[data-role=tagsinput]").tagsinput('removeAll');
    // $("#autocomplete").tagsinput('removeAll');
    $('.bootstrap-tagsinput').find('input').attr('placeholder', 'Search keyword');
    // $('#autocomplete').parent().find('.bootstrap-tagsinput').find('input').attr('placeholder', 'Search Location');
    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
    page = 1;

    if (update_flag) {
      update_listing_based_on_filteration();
    }
    return false;
  });
  var prev_str = '';
  // manage real time search event
  $(document).on('keyup input paste', '.search_text input', function (e) {
    if (e.which === 44 && this.value.length) {
      e.preventDefault();
      return false;
    }
    this.value = this.value.replace(/ +/g, ' ');
    page = 1;
    if ($(this).parents('.search_text').next('#autocomplete').length <= 0) {
      var str = $(this).val().trim();
      if (/[@*]/g.test(str)) {
        $(this).val('');
        return false;
      }
      if (str != prev_str) {
        if (str.length != 0 && (str.length > 0)) {
          real_search_txt = $(this).val();
          update_listing_based_on_filteration();
        } else if (str.length != 0 && (str.length == 0)) {
          real_search_txt = '';
          update_listing_based_on_filteration();
        }
      }
    }

  });
  $(document).on('cut', '.search_text input', function (e) {
    var str = $(this).val();
    if (str != '') {
      real_search_txt = '';
      prev_str = '';
      update_listing_based_on_filteration();
    }
  });
  $(document).on('keydown', '.search_text input', function (e) {
    var position = $(this).getCursorPosition();
    var deleted = '';
    if (e.which === 32 && (!this.value.length)) {
      e.preventDefault();
    }
    var input = e.target;
    var val = input.value;
    var start = input.selectionStart;
    var end = input.selectionEnd;

    if (e.keyCode == 32 && (start == 0 || val[end - 1] == " " || val[end] == " ")) {
      e.preventDefault();
      return false;
    }
    var str = $(this).val().trim();
    var flag = false;
    prev_str = str;
    if ($(this).parents('.search_text').next('#autocomplete').length <= 0) {
      if (e.keyCode == 8) {
        flag = true;
      } else if (e.keyCode == 46) {
        var val = $(this).val();
        if (position[0] == position[1]) {

          if (position[0] === val.length)
            deleted = '';
          else
            deleted = val.substr(position[0], 1);
        }
        else {
          deleted = val.substring(position[0], position[1]);
        }

        if (val == deleted && deleted != '') {
          real_search_txt = '';
          prev_str = '';
          flag = true;
        } else if (deleted != '') {
          flag = true;
        }
      } else if (e.shiftKey && (e.keyCode == 50 || e.keyCode == 56)) {
        e.preventDefault();
      }
      if (flag) {
        real_search_txt = '';
        prev_str = '';
        update_listing_based_on_filteration();
      }
    }

  });
  $.fn.getCursorPosition = function () {
    var el = $(this).get(0);
    var pos = 0;
    var posEnd = 0;
    if ('selectionStart' in el) {
      pos = el.selectionStart;
      posEnd = el.selectionEnd;
    } else if ('selection' in document) {
      el.focus();
      var Sel = document.selection.createRange();
      var SelLength = document.selection.createRange().text.length;
      Sel.moveStart('character', -el.value.length);
      pos = Sel.text.length - SelLength;
      posEnd = Sel.text.length;
    }
    return [pos, posEnd];
  };
  // manage detailed filter drop down
  $(document).on('click', '.selectBox', function (e) {
    e.stopPropagation();
    var visibility = $(this).next('div').is(':visible');
    $.each($('.selectBox'), function () {
      if ($(this).next('div').is(':visible')) {
        $(this).next('div').hide();
      }
    });

    if (visibility) {
      $(this).next('div').hide();
    } else {
      $(this).next('div').show();
    }
    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
  });

  $(document).click(function () {
    $.each($('.selectBox'), function () {
      if ($(this).next('div').is(':visible')) {
        $(this).next('div').hide();
      }
    });
    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');
  });

  // stop hiding dropdown when click on any label
  $('.visible_option .drpChk').click(function (e) {
    e.stopPropagation();
  });

  // handle drop down check box click evnet to display proper tags
  $('.visible_option label input').on('click', function (e) {
    var val = $(this).val();
    var parents_id = $(this).parents('.visible_option').prop('id');
    $('#' + parents_id).hide();
    var display_txt = $(this).parent().siblings('small').text();

    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');

    var ele_id = $(this).attr('id');
    display_txt = display_txt.trim();
    display_txt += " <i class='fa fa-times remove_filter' data-id='" + ele_id + "' data-parent='" + parents_id + "' aria-hidden='true'></i>";
    var all_clicked = false;
    if (val.includes('all')) {
      if ($(this).prop('checked')) {
        all_clicked = true;
      }
      $(this).prop('checked', true);
    }
    if ($(this).prop('checked')) {
      //when check checkboxes bold
      $(this).parent().siblings('small').addClass('boldCat');
      $(this).parents('.multiselect').find('select').addClass('boldCat');
      $('#' + parents_id + ' .drpChk:first-child').find('small').removeClass('boldCat');

      if (val.includes('all')) {
        //when check checkboxes bold
        $('#' + parents_id + ' .drpChk').find('small').removeClass('boldCat');
        $('#' + parents_id + ' .drpChk:first-child').find('small').addClass('boldCat');
        $(this).parents('.multiselect').find('select').removeClass('boldCat');

        $('.' + parents_id + ' small').first().css({ 'display': 'block' });
        $.each($(this).parents('.visible_option').children(), function () {
          $(this).find('input').prop('checked', false);
          var dataid = $(this).find('input').prop('id');
          $('.' + parents_id + ' small[data-id="' + dataid + '"]').remove();
        });
        $(this).prop('checked', true);
      } else {

        if (parents_id == 'checkboxes3') {
          $.each($('#' + parents_id).children(), function () {
            $(this).find('input').prop('checked', false);
            var dataid = $(this).find('input').prop('id');
            $('.' + parents_id + ' small[data-id="' + dataid + '"]').remove();
            //when check checkboxes bold
            $('#' + parents_id + ' .drpChk').find('small').removeClass('boldCat');
          });
          $(this).prop('checked', true);
          $(this).parent().siblings('small').addClass('boldCat');//when check checkboxes bold
        }

        $(this).parents('.visible_option').children().first().find('input').prop('checked', false);
        $('.' + parents_id + ' small').first().css('cssText', 'display:none !important');
        var first_child = $('.' + parents_id + ' label:first-child').html();
        $('<small class="tagSecond">' + first_child + '</small>').appendTo('.' + parents_id);

        $('.' + parents_id + ' small').last().attr('data-id', ele_id).show();
        $('.' + parents_id + ' small').last().html(display_txt);
        // When user select all option rather then none and default all then reset selection to all considering he want's to select all
        var total_option = $('#' + parents_id + ' .drpChk').find('input').length
        var checked_cnt = $('#' + parents_id + ' .drpChk').find('input:checked').length;
        if (checked_cnt == (total_option - 1)) {
          $('#' + parents_id + ' .drpChk').find('small').removeClass('boldCat');
          $('.' + parents_id + ' small').first().css({ 'display': 'block' });

          $.each($(this).parents('.visible_option').children(), function () {
            $(this).find('input').prop('checked', false);
            var dataid = $(this).find('input').prop('id');
            $('.' + parents_id + ' small[data-id="' + dataid + '"]').remove();
          });
          $('#' + parents_id + ' .drpChk:first-child').find('input').prop('checked', true);
          //when check checkboxes bold "All"
          $('#' + parents_id + ' .drpChk:first-child').find('small').addClass('boldCat');
          $(this).parents('.multiselect').find('select').removeClass('boldCat');
        }
      }
    } else {
      //when check checkboxes bold
      $(this).parent().siblings('small').removeClass('boldCat');
      var cnt = 0;
      $.each($('#' + parents_id).children(), function (index, value) {
        if (index != 0 && $(this).find('input').prop('checked')) {
          cnt++;
        }
      });
      if (cnt == 0) {
        $('.' + parents_id + ' small').first().css({ 'display': 'block' });
        $('#' + parents_id + ' .drpChk:first-child').find('input').prop('checked', true);
        //when check checkboxes bold "All"
        $('#' + parents_id + ' .drpChk:first-child').find('small').addClass('boldCat');
        $(this).parents('.multiselect').find('select').removeClass('boldCat');
      }
      $('.' + parents_id + ' small[data-id="' + ele_id + '"]').remove();
    }
    page = 1;
    all_category_set = false;
    if (!val.includes('all') || all_clicked) {
      update_listing_based_on_filteration();
    }
  });

  // remove filter
  $(document).on('click', '.remove_filter', function () {
    var id = $(this).attr('data-id');
    var parent_id = $(this).attr('data-parent');

    $('.' + parent_id + ' small[data-id="' + id + '"]').remove();
    $('#' + parent_id + ' #' + id).prop('checked', false);
    $('#' + parent_id + ' #' + id).parent().siblings('small').removeClass('boldCat');
    var cnt = 0;
    $.each($('#' + parent_id).children(), function (index, value) {
      if (index != 0 && $(this).find('input').prop('checked')) {
        cnt++;
      }
    });
    if (cnt == 0) {
      $('.' + parent_id + ' small').first().css({ 'display': 'block' });
      $('#' + parent_id + ' .drpChk:first-child').find('input').prop('checked', true);
      $('#' + parent_id + ' .drpChk:first-child').find('small').addClass('boldCat');
      $('#' + parent_id).parents('.multiselect').find('select').removeClass('boldCat');
    }
    page = 1;
    update_listing_based_on_filteration();
  });
  // clear all filter
  $(document).on('click', '.clear_all_filter', function (e) {

    $("#search_keyword").parent().find('.bootstrap-tagsinput input').focus();
    $("#search_keyword").parent().find('.bootstrap-tagsinput').addClass('focus input_focus');

    $('#checkboxes2').parents('.multiselect').removeClass('disSbox');
    $('.checkboxes2').parent('label').show();
    $.each($('.selectBox'), function () {
      if ($(this).next('div').is(':visible')) {
        $(this).next('div').hide();
      }
    });
    var flag = false;
    var chbx2 = $('#checkboxes2 label input:checked').val();
    // var chbx3 = $('#checkboxes3 label input:checked').val();
    // var chbx4 = $('#checkboxes4 label input:checked').val();
    // // var chbx5 = $('#checkboxes5 label input:checked').val();
    if (!chbx2.includes('all')) {
      flag = true;
    }
    $.each($('.checkboxes2').children('small'), function (index, val) {
      if (index == 0) {
        $(this).show();
        $('#checkboxes2 .drpChk:first-child').find('input').prop('checked', true);
        $('#checkboxes2 .drpChk:first-child').find('small').addClass('boldCat');
        $('#checkboxes2').parents('.multiselect').find('select').removeClass('boldCat');
      } else {
        var id = $(this).attr('data-id');
        $(this).remove();
        $('#' + id).prop('checked', false);
        $('#checkboxes2 #' + id).parent().siblings('small').removeClass('boldCat');
      }
    });
    // $.each($('.checkboxes3').children('small'), function (index, val) {
    //   if (index == 0) {
    //     $(this).show();
    //     $('#checkboxes3 .drpChk:first-child').find('input').prop('checked', true);
    //     $('#checkboxes3 .drpChk:first-child').find('small').addClass('boldCat');
    //     $('#checkboxes3').parents('.multiselect').find('select').removeClass('boldCat');
    //   } else {
    //     var id = $(this).attr('data-id');
    //     $(this).remove();
    //     $('#' + id).prop('checked', false);
    //     $('#checkboxes3 #' + id).parent().siblings('small').removeClass('boldCat');
    //   }
    // });
    // $.each($('.checkboxes4').children('small'), function (index, val) {
    //   if (index == 0) {
    //     $(this).show();
    //     $('#checkboxes4 .drpChk:first-child').find('input').prop('checked', true);
    //     $('#checkboxes4 .drpChk:first-child').find('small').addClass('boldCat');
    //     $('#checkboxes4').parents('.multiselect').find('select').removeClass('boldCat');
    //   } else {
    //     var id = $(this).attr('data-id');
    //     $(this).remove();
    //     $('#' + id).prop('checked', false);
    //     $('#checkboxes4 #' + id).parent().siblings('small').removeClass('boldCat');
    //   }
    // });
    // $.each($('.checkboxes5').children('small'), function (index, val) {
    //   if (index == 0) {
    //     $(this).show();
    //     $('#checkboxes5 .drpChk:first-child').find('input').prop('checked', true);
    //     $('#checkboxes5 .drpChk:first-child').find('small').addClass('boldCat');
    //     $('#checkboxes5').parents('.multiselect').find('select').removeClass('boldCat');
    //   } else {
    //     var id = $(this).attr('data-id');
    //     $(this).remove();
    //     $('#' + id).prop('checked', false);
    //     $('#checkboxes5 #' + id).parent().siblings('small').removeClass('boldCat');
    //   }
    // });
    page = 1;

    if (flag) {
      update_listing_based_on_filteration();
    }
    return false;
  });

})

// var xhr;
// // update listing of member based on filter criteria
// function update_listing_based_on_filteration() {

//   if (xhr) {
//     xhr.abort();
//   }

//   var project_type = [];
//   $.each($('#checkboxes2').children(), function () {
//     var value = $(this).find('input').val();
//     if (!value.includes('all') && $(this).find('input').prop('checked')) {
//       project_type.push(value);
//     }
//   });
//   var data = {
//     searchtxt_arr: $('#search_keyword').tagsinput('items'),
//     real_time_search_txt: real_search_txt,
//     search_type: $('.search_type:checked').val(),
//     project_type: project_type
//   };
//   console.log(data);

//   xhr = $.ajax({
//     url: SITE_URL + 'project/ajax_filter_open_for_bidding/' + page,
//     method: 'POST',
//     dataType: 'json',
//     data: data,
//     success: function (response) {
//       if (response['status'] == 200) {
//         $('.member_wrapper').html(response['data']);
//       }
//     }
//   });
// }

function showMoreSearch() {
  var moreSearch = $("#moreSearch").val();
  if (moreSearch == 1) {
    $("#moreSearch").val(0);
    $(".receive_notification a").html('show Less <small>( - )</small>');
    $("#rcv_notfy").show();

  } else {
    $("#moreSearch").val(1);
    $(".receive_notification a").html('show more <small>( + )</small>');
    $("#rcv_notfy").hide();
  }
}