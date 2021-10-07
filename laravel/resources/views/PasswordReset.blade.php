<html>
    <head>
        <style>
        </style>
    </head>
    <body>
        <p>Hi {{$data->receiver}},</p>
        <p>You requested to reset your password.</p>
        <p> Please, click the link below to reset your password</p>
        <a href={{ $data->url }}>Reset password</a>
    </body>
</html>
