<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>test</title>
</head>

<body>
  <form action="/upload/images" method="post" enctype="multipart/form-data">
    Файлы:<br />
    <input type="file" name="images[]" id="images" multiple accept="image/*">
    <input type="button" value="Отправить" />
  </form>
  <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    var formData = new FormData();
    var imagefile = document.querySelector('#images');
    formData.append("images", imagefile.files[0]);
    console.log(formData.values);
    axios.post('/upload/images', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  </script>
</body>

</html>