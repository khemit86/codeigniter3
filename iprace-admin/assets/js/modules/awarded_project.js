var xhr;
// update listing of member based on filter criteria
function update_listing_based_on_filteration() {

  if (xhr) {
    xhr.abort();
  }

  var project_type = [];
  $.each($('#checkboxes2').children(), function () {
    var value = $(this).find('input').val();
    if (!value.includes('all') && $(this).find('input').prop('checked')) {
      project_type.push(value);
    }
  });
  var data = {
    searchtxt_arr: $('#search_keyword').tagsinput('items'),
    real_time_search_txt: real_search_txt,
    search_type: $('.search_type:checked').val(),
    project_type: project_type
  };
  // console.log(data);

  xhr = $.ajax({
    url: SITE_URL + 'project/ajax_filter_awarded/' + page,
    method: 'POST',
    dataType: 'json',
    data: data,
    success: function (response) {
      if (response['status'] == 200) {
        $('.member_wrapper').html(response['data']);
      }
    }
  });
}