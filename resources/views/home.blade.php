<!doctype html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    	<h1>Hello</h1>
    	<div>
    		<form action="/lastfm" method="POST">
                {{ csrf_field() }}
                <input type="text" name="username">
                <input type="submit" value="Submit">
    		</form>
    	</div>
    </body>
</html>
