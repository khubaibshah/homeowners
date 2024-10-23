<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV</title>
</head>
<body>
    <h1>Upload CSV to Parse Homeowners</h1>
    <form action="/parse-csv" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Upload and Parse</button>
    </form>
</body>
</html>
