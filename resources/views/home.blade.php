
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizo HTML Template - V.5</title>
    <!-- FontAwesome-cdn include -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <!-- Google fonts include -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700;800&family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap-css include -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Animate-css include -->
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <!-- Main-StyleSheet include -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div id="app" class="wrapper" style="background-image: url({!! asset('images/bg_0.png') !!})">
    <!-- Top content -->
</div>
<!-- jQuery-js include -->
<script src="{{ asset('js/jquery.js') }}"></script>
<!-- jquery-count-down include -->
<script src="{{ asset('js/countdown.js') }}"></script>
<!-- Bootstrap-js include -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- jQuery-validate-js include -->
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<!-- Custom-js include -->

<script src="{!! mix('js/app.js') !!}"></script>

</body>
</html>
