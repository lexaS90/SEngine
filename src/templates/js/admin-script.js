var link;

$("#myModal").on("show.bs.modal", function(e) {

    link = $(e.relatedTarget);
    var modal = $(this);
    $(this).find('.alert').html('');
    $(this).find('.alert').hide();

    $.ajax({
        url: link.attr("href"),
        type: "GET",
        dataType: "json",
        success: function(html){
            modal.find('.modal-body').html(html.body);
            modal.find('.modal-title').html(html.title);
        },
        complete: function() {
            tinymce.init({
                selector:'textarea',
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                },
            });
            up();
        }
    });

});

up();

$("body").on('click', '#ajax', function(){
    var $form = $('#myModal').find('form');
    $.ajax({
        url: link.attr("href"),
        type: "POST",
        dataType: "json",
        data: $form.serialize(),
        success: function(html){

            if (html.status == 0){
                $('.alert').show();

                $('.alert').html('');
                $form.find("[name]").css('border', '');

                $.each(html.errors, function (field, error) {
                    $('<p/>').text(error.errorText).appendTo('.alert');
                    $form.find("[name='" + error.field + "']").css('border', '1px solid red');
                });
            }
            else{
                $("#myModal").modal('hide');
                location = html.redirect;

            }
        }
    });
});

// Upload

function up() {
    'use strict';

    var fileupload = $('.fileupload');
    var url = fileupload.attr('data-url');
    var uploadData = fileupload.siblings('input');
    var urlParam = uploadData.attr('name');

    console.log(urlParam);
    console.log('ok');

    fileupload.fileupload({
        replaceFileInput: false,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result[urlParam], function (index, file) {
                uploadData.val(file.name);
                var deleteBtn = "<button data-url='"+file.name+"' data-type='DELETE' class='delete' type='button'><span class='glyphicon glyphicon-remove'></span></button>";
                $('#files').html('<img width="30%" src=' + file.url +'>'+deleteBtn);
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    $("body").on('click', ".delete", function(){
        $.ajax({
            type: "DELETE",
            url: url + '?file=' + $(this).attr('data-url'),
            dataType: "json",
            success: function(html){
                uploadData.val('');
                $('#files').html('');
            },
        });
    });
};