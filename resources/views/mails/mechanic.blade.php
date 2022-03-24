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
    <p>you're invited to be mechanic in CarWorkshop, with the following user account:</p>
    <ul>
        <li>Email : <b>{{ $data['email'] }}</b></li>
        <li>Password : <b>{{ $data['password'] }}</b></li>
    </ul>
    <p> Now you can login in link below:
        <br>
        {{ url('') }}
    </p>

</body>

</html>
