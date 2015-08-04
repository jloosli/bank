<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>JrBank Password Reset</h2>

<div>
    Hey, don't worry about it. It happens to the best of us. Just go to the following link:
</div>
<div>
    {{ Request::header('Origin') }}/#/user/login/reset/{{$token}}
</div>
</body>
</html>