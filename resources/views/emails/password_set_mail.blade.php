<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Welcome to the team {{ $name }}! An administrator has created your account profile in our employee management system, and we
        are excited to have you on board.To complete your onboarding setup, please click the secure registration link
        below to set your account password and log into your workspace:</p>

    <a href="{{ $password_link }}">Set You Password</a>
</body>

</html>
