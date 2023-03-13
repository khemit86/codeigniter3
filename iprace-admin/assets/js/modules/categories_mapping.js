$(document).ready(function(){
    $("#addButton").click(function (){
        var ids = $.map($('.professional_cate'), function(ele) {
            return $(ele).val();
        });
        var flag = false;
        var selected_category = [];
        if(professional_category_limit == ($('.child').length + 1)) {
            flag = true;
            $.each($('.proj_cate'), function() {
                var val = $(this).val();
                if(selected_category.indexOf(val) == -1) {
                    selected_category.push(val);
                }
            });
        }   
        if(professional_category_length >= 2)  {
            $('.allCategory').children().first('.parent').find('.i-cancel-circle-2').removeClass('i-cancel-disabled');
        }
        var str = $('.parent').html();
        var append_str = '<div class="child">'+str+'</div>';
        $('.allCategory').append(append_str);
        $('.allCategory').children().last().find('.remove').show();
        $('.allCategory').children().last().find('.i-cancel-circle-2').removeClass('i-cancel-disabled');
        $('.allCategory').children().last().find('.proj_cate option[value=""]').prop('selected', true);
        $('.allCategory').children().last().find('.proj_cate').prop('id', 'proj_cate_'+counter);
        $('.allCategory').children().last().find('.professional_cate option').removeAttr('selected');
        $('.allCategory').children().last().find('.professional_cate option[value=""]').prop('selected', true);
        $('.allCategory').children().last().find('.professional_cate').prop('id', 'professional_cate_'+counter++);
        $('.allCategory').children().last().find('.professional_cate').prop('disabled', true);
        $('#addButton').prop('disabled', true);
        $('#saveButton').prop('disabled', true);
        $('.professional_cate option[value=""]').hide();
        $.each(ids, function(key, val) {
            $('.allCategory').children().last().find('.professional_cate option[value="'+val+'"]').hide();
        });
        if($('.child').length > 0) {
            $.each($('.proj_cate'), function() {
                $('option[value=""]').hide();
            });
        }
        selected_category.forEach(function(value) {
            $('.allCategory').children().last().find('.proj_cate option[value="'+value+'"]').hide();
        });
        
        
    });

    $(document).on('click', '.remove', function() {
        if($(this).parents().hasClass('parent') && $(this).find('i').hasClass('i-cancel-disabled') == false) {
            if($('.child').length > 0) {
                var remove_val = $(this).parents('.parent').find('.professional_cate').val();
                $(this).parents('.parent').remove();
                $('.allCategory').children().first().removeClass('child').addClass('parent');
                $('.allCategory').children().first().find('.proj_cate').prop('id', '');
                $('.allCategory').children().first().find('.professional_cate').prop('id', '');
                if(remove_val != '') {
                    $.each($('.professional_cate'), function(ele) {
                        $('option[value="'+remove_val+'"]', $(this)).show();
                    });
                }    

            } else {
                $('.allCategory').children().first().find('.i-cancel-circle-2').addClass('i-cancel-disabled');
            }
        }

        $(this).parents('.child').remove();
        // if($('.child').length == 0 && !$(this).find('i').hasClass('i-cancel-disabled')) {
        //     $('#saveButton').prop('disabled', true);
        // }
        if($('.child').length == 0) {
            $('#addButton').prop('disabled', true);
            if(!$('.allCategory').children().first().find('i').hasClass('i-cancel-disabled')) {
                $('#saveButton').prop('disabled', true);
            } 
            $('.professional_cate option[value=""]').show();
            $('.proj_cate option[value=""]').show();
            var professional_cat = $('.professional_cate').val();
            if(professional_cat != '') {
                $('#addButton').prop('disabled', false);
                if(!$('.allCategory').children().first().find('i').hasClass('i-cancel-disabled')) {
                    $('#saveButton').prop('disabled', false);                
                }
            }
            $('.allCategory').children().first().find('.i-cancel-circle-2').addClass('i-cancel-disabled');
        } else {
            var professional_cat = $('.allCategory').children().last().find('.professional_cate').val();
            if(professional_cat != '') {
                $('#addButton').prop('disabled', false);
                $('#saveButton').prop('disabled', false);
            }
        }
        var remove_val = $(this).parents('.child').find('.professional_cate').val();
        if(remove_val != '') {
            $.each($('.professional_cate'), function(ele) {
                $('option[value="'+remove_val+'"]', $(this)).show();
            });
        }    
        counter--;
    });

    // manage project category selection
    $(document).on('change', '.proj_cate', function() {
        if($(this).val() == '') {
            $(this).parent().parent().parent().find('.professional_cate').parent().addClass('disabled');
            $(this).parent().parent().parent().find('.professional_cate').prop('disabled', true);
            $(this).parent().parent().parent().find('.professional_cate option[value=""]').prop('selected', true);
            $('#saveButton').prop('disabled', false);
            $('#addButton').prop('disabled', true);
        } else {
            $(this).parent().parent().parent().find('.professional_cate').parent().removeClass('disabled');
            $(this).parent().parent().parent().find('.professional_cate').prop('disabled', false);
            $('#saveButton').prop('disabled', true);
            var professional_cat = $(this).parent().parent().parent().find('.professional_cate option:selected').val();
            if(professional_cat != '') {
                $('#saveButton').prop('disabled', false);
            } else {
                $('#saveButton').prop('disabled', true);
            }
        }
        
    });

    var previous;
    // manage professional category selection previous value
    $(document).on('focus', '.professional_cate', function() {
        previous = this.value;
    });
    // manage professional category selection
    $(document).on('change', '.professional_cate', function() {
        var val = $(this).val();
        var id = $(this).prop('id');
        if($(this).val() == '') {
            $('#addButton').prop('disabled', true);
            $('#saveButton').prop('disabled', true);
        } else {
            $('#addButton').prop('disabled', false);
            $('#saveButton').prop('disabled', false);
            $.each($('.professional_cate'), function(ele) {
                if(id != $(this).prop('id')) {
                    console.log($(this).prop('id'));
                    $('option[value="'+val+'"]', $(this)).hide();
                }
            });
            if(previous != '') {
                $('.professional_cate option[value="'+previous+'"]').show();
            }
        }
        var val =  $('.allCategory').children().last().find('.professional_cate').val();
        if(val == '') {
            $('#addButton').prop('disabled', true);
            $('#saveButton').prop('disabled', true);
        }
    });
    // manage category save button event
    $(document).on('click', '#saveButton', function(){
        var category = [];
        var professional_category = [];
        var final_array = [];
        $.each($('.proj_cate'), function(){
            category.push($(this).val());
        });
        $.each($('.professional_cate'), function(){
            professional_category.push($(this).val());
        });
        category.forEach((val, index) => {
            if(val != '') {
                var data = { 
                    'projects_category_id' : val,
                    'professionals_category_id' : professional_category[index]
                };
                final_array.push(data);
            }
        });
        $.ajax({
            url : 'categories_mapping/save_projects_professionals_categories_mapping_data',
            method : 'POST',
            data : {data : final_array},
            success : function(res) {
                console.log(res);
                res = JSON.parse(res);
                if(res && res['status'] == 200) {
                    // $('.alert-success').show();
                    var succ_str = '<div class="alert alert-success" >';
                    succ_str += '  <button type="button" class="close" data-dismiss="alert">&times;</button>';
                    succ_str += '  <strong><i class="icon24 i-checkmark-circle"></i> Well done!</strong> Category mapping data saved successfully';
                    succ_str += '</div>';
                    $('.succ-alert').html(succ_str);
                    $('#saveButton').prop('disabled', true);
                    if($('.child').length > 0) {
                        $('.allCategory').children().first().find('.i-cancel-circle-2').removeClass('i-cancel-disabled');
                    }
                    professional_category_length = $('.child').length + 1;
                }
            }
        });
    });
});