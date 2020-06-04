<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album CRUD</title>
</head>

<body>
    <div id="content"></div>
    <input type="text" id="title">
    <textarea id="text"></textarea>
    <button type="button" id="button">Отправить</button>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    const token = 'iTH1Q1DoWnIfBEadODAwcGtCNXFtTlpQRDJ6bmFsMThhZz09bUtjbUltTUlGc3c5WUJ5NjRSeWJMUGVhYlUwPQ==';
    
    $("#button").click(function(){
        let title = $("#title").val();
        let text = $("#text").val();
        let id = 2;
        axios
            .post(
                'http://api.memory-lane.ru/db/deleteAlbum', {
                    //'first_name':`Грачья`,
                    //'phone': `6348374837948394`,
                    //'story_id': id
                    //'id_album': 2
                    'id_album':63
                }, {
                    headers: {
                        //'Content-Type': 'multipart/form-data',
                        'Content-Type':'application/x-www-form-urlencoded',
                        'Authorization': `iTH1Q1DoWnIfBEadODAwcGtCNXFtTlpQRDJ6bmFsMThhZz09bUtjbUltTUlGc3c5WUJ5NjRSeWJMUGVhYlUwPQ==`,
                        //'Access-Control-Request-Method': 'POST',
                        //'Access-Control-Allow-Origin': 'http://legacy.loc'
                    }
                })
            .then(res => {
                console.log(res);
                $("#content").text(res);
                //var content = res.data.content;
                //console.log(content);
                
                /*for(volume in content){
                   console.log( content[volume].album_name);
                }
                */
                //$("#content").html(content[2].album_name);
                //console.log(res);
            })
            .catch(function(error) {
                //console.error("Error response:");
                console.log(error); // ***
                $("#content").text(error);
                //console.error(error.response.status); // ***
                //console.error(error.response.headers); // ***

            });
    });
        
    </script>
</body>

</html>