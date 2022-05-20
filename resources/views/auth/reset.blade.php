<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="robots" content="noindex"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        body { text-align: center; padding: 20px; font: 20px Helvetica, sans-serif; color: #333; }
        @media (min-width: 768px){
            body{ padding-top: 150px; }
        }
        h1 { font-size: 50px; }
        article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
        a { color: #dc8100; text-decoration: none; }
        a:hover { color: #333; text-decoration: none; }
    </style>
</head>
<body>
    <article>
        <h1>Congratulations!</h1>
        <div>
            <p>Your Password has been changed, check your email to <strong>get it !</strong></p>
            <p>&mdash; {{ config('app.name', 'Laravel') }}</p>
        </div>
    </article>
</body>
</html>