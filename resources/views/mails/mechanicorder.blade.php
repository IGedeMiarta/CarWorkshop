<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email By CarWorkshop</title>
    <style>
        ul {
            list-style-type: none;
        }

    </style>
</head>

<body>
    <p>Hi, {{ $data['name'] }}</p>
    <p>you're have new work order, please check and update status the work order at CarWorkshop</p>
    <p>Big Thanks</p>
    <br>
    <a href="{{ url('/') }}">&copy; CarWorkshop</a>
    <br>
    {{ url('/') }}

</body>

</html>
