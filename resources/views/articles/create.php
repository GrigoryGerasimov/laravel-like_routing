<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create</title>
</head>
<body>
<form action='/articles' method='POST' enctype='application/x-www-form-urlencoded'>
    <label for='test-id'>Entry</label>
    <input id='test-id' name='title' placeholder='Testing post route'/>
    <button type='submit'>Submit</button>
</form>
</body>
</html>
