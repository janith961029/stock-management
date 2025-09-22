<!DOCTYPE html>
<html>
<head>
    <title>Items List</title>
</head>
<body>
    <h1>All Items</h1>

    @foreach($allItems as $item)
        <p>{{ $item->id }} - {{ $item->titlenames->name ?? 'No title' }}</p>
    @endforeach
</body>
</html>
