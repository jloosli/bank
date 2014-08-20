<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Oauth Confirmation</title>
</head>
<body>
    <div class="welcome">
    <h2>Authenticated</h2>
        <p>{{ $message }}</p>
        <p>Your unique token is {{{ $token }}}</p>
        <?php var_dump($result); ?>
    </div>
<script>
window.opener.angular.element('#loginForm').scope().login.storeToken('{{$token}}');
setTimeout(function(){
    self.close();
    },
    5000);
</script>
</body>
</html>
