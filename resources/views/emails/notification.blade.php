<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>{{$mailData['title']}}</h1>
    <p>Class : {{$mailData['class']}}</p>
    <p>Subject : {{$mailData['subject']}}</p>
    <p>Time : {{$mailData['meeting_time']}}</p>
    <p>Link : {{$mailData['meeting_link']}}</p>
</body>

</html>