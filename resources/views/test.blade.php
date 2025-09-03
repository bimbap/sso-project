<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Hello World!</h1>
    <p>This is a test page to verify view rendering works.</p>
    <p>App Name: {{ config('app.name') }}</p>
    <p>Current Time: {{ date('Y-m-d H:i:s') }}</p>
</body>
</html>
