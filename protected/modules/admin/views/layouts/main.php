<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <!--    <meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->

    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link href="/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <!--<![endif]-->
</head>
<body>
<div class="container">
    <?php $this->widget('application.modules.admin.widgets.Menu'); ?>
    <div class="l-page">
        <div class="l-page-i">
            <?php echo $content; ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="/js/fileinput.min.js"></script>
<script src="/js/fileinput_locale_ru.js"></script>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="/js/tinymce/tinymce.min.js"></script>


<script>
    $("#fileInput").fileinput({
        showUpload: false,
        initialPreview: function () {
            return $('#filePreview').attr('src') ? "<img src='" + $('#filePreview').attr('src') + "' class='file-preview-image'>" : '';
        }(),
        overwriteInitial: true
    });
    $('.save').on('click', function (event) {
        event.preventDefault();
        var $form = $('#' + $(event.currentTarget).data('form')),
            url = $(event.currentTarget).data('action'),
            params = new FormData($form[0]);
        console.warn(url, params, $form, $form[0]);
        $.ajax({
            type: 'POST',
            url: url,
            data: params,
            contentType: false,
            processData: false,
            success: function (data) {
                alert('Сохранено');
            },
            error: function (xhr, str) {
                console.warn('Возникла ошибка: ', arguments);
            }
        });

    });


    $('#confirm-delete').on('show.bs.modal', function (e) {
        console.warn($(e.relatedTarget).data('id'));
        $(this).find('.btn-ok').on('click', function () {
            var id = $(e.relatedTarget).data('id'),
                url = $(e.relatedTarget).data('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: {id: id},
                success: function (data) {
                    window.location.reload();
                },
                error: function (xhr, str) {
                    console.warn('Ошибка', xhr, str);
                }
            });
        });
    });
    tinymce.init({
        plugins: "image,link",
        selector: ".wysiwyg",
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
    $("#sortable").sortable({
        sort: function (event, ui) {
            console.warn('sort', arguments);
        },
        start: function (event, ui) {
            $('#categorySaveButton').show();
        },
        stop: function (event, ui) {
            console.warn('stop', arguments);
        }

    });
    $("#sortable").disableSelection();

</script>
</body>
</html>