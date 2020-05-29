<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>

<body>
<input type="file" multiple="multiple" accept=".txt,image/*">
<a href="#" class="upload_files button">Загрузить файлы</a>
<div class="ajax-reply"></div>

<script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script>
        $('.upload_files').on('click', function(event) {

            event.stopPropagation(); // остановка всех текущих JS событий
            event.preventDefault(); // остановка дефолтного события для текущего элемента - клик для <a> тега

            // ничего не делаем если files пустой
            if (typeof files == 'undefined') return;

            // создадим объект данных формы
            var data = new FormData();

            // заполняем объект данных файлами в подходящем для отправки формате
            $.each(files, function(key, value) {
                data.append(key, value);
            });

            // добавим переменную для идентификации запроса
            data.append('my_file_upload', 1);

            // AJAX запрос
            $.ajax({
                url: '/upload/images',
                type: 'POST', // важно!
                data: data,
                cache: false,
                dataType: 'json',
                // отключаем обработку передаваемых данных, пусть передаются как есть
                processData: false,
                // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
                contentType: false,
                // функция успешного ответа сервера
                success: function(respond, status, jqXHR) {

                    // ОК - файлы загружены
                    if (typeof respond.error === 'undefined') {
                        // выведем пути загруженных файлов в блок '.ajax-reply'
                        var files_path = respond.files;
                        var html = '';
                        $.each(files_path, function(key, val) {
                            html += val + '<br>';
                        })

                        $('.ajax-reply').html(html);
                    }
                    // ошибка
                    else {
                        console.log('ОШИБКА: ' + respond.data);
                    }
                },
                // функция ошибки ответа сервера
                error: function(jqXHR, status, errorThrown) {
                    console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
                }

            });

        });
    </script>
</body>

</html>