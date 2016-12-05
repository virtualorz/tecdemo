<!DOCTYPE html>
<html>
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $data_email_send['name'] }}</title>
    </head>
    <body>
        <div>            
            <div>
            <p>宇翼設計樣板回覆專欄</p>
            </div>   

            <div>
                {{$data_email_send['content']}}
            </div>

            <div>
            <p>宇翼設計樣板回覆專欄表尾</p>
            </div>
        </div>
    </body>
</html>
