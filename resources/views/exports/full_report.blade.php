<!DOCTYPE html>
<html>
<head>
    <title>Full Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
    <h1 style ="text-align:center;">Full Report</h1>
    <p style ="text-align: center;">{{$start-> format('d/m/Y')}}-{{$end-> format('d/m/Y')}}</p>

    
    <h2>Summary by Type</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Total</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $row)
                <tr>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->total }}</td>
                    <td>{{ $row->created_count }}</td>
                    <td>{{ $row->updated_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Data</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Detail</th>
                <th>Type</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->detail }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>{{ $product->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>