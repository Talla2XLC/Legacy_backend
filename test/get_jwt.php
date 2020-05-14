<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test JWT</title>
</head>

<body>
    <div id="content"></div>
    <input type="text" placeholder="JWT">
    <button type="button">Отправить</button>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        /*  $.ajax({
            url:'http://api.memory-lane.ru/checkToken',
            type: 'POST',
            data:{},
            success:function(data){
                console.log('success');
                console.log(data);
            },
            error:function(e,t,v){
                //console.log('error');
                //console.log(e);
                console.log(e.responseText);
            }
        }) */
        /* axios.post('http://api.memory-lane.ru/testrequest',

        {
            firstName: 'Fred',
            lastName: 'Flintstone'
        }, 
        {
            headers: {
                'Content-Type: application/json'
            }
        })
        .then(function(response) {
                console.log(response);
            })
            .catch(function(error) {
                console.error("Error response:");
                console.error(error.response.data); // ***
                console.error(error.response.status); // ***
                console.error(error.response.headers); // ***

            }); */
            
            axios
            .post(
                'http://api.memory-lane.ru/testrequest',
                { 
                    'der':'test'
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `${token}`
                        //'Content-Type':'application/json',
                        //'Access-Control-Request-Method': 'POST',
                        //'Access-Control-Allow-Origin': 'http://legacy.loc'
                    }
                })
                .then(res => {
                    var content = res.data.content;
                    $("#content").text(content);
                })
                .catch(function(error) {
                //console.error("Error response:");
                console.log(error.response.data); // ***
                //console.error(error.response.status); // ***
                //console.error(error.response.headers); // ***

            });
    </script>
</body>

</html>