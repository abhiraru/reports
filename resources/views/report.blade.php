<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterTableByDate() {
            const input = document.getElementById("dateSearch");
            const filter = input.value;
            console.log(filter);
            if (!filter) return;
            fetch(`/report?date=${filter}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h3>Employee Details</h3>
        <form action="{{ route('index') }}" method="GET">
            <div class="mb-3">
                <label for="dateSearch" class="form-label">Enter Date:</label>
                <input type="date" id="dateSearch" name="date" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>sl.no</th>
                    <th>Interval Start</th>
                    <th>Interval End</th>
                    <th>Apps Used</th>
                    <th>Unproductive</th>
                    <th>Productive</th>
                    <th>neutral</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($final as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td> 
                        <td>
                            {{ \Carbon\Carbon::parse($row['start'])->format('h:i A') }}

                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($row['start'])->addMinutes(5)->format('h:i A') }}
                        </td>
                        <td><ul>
                        @foreach ($row['appname'] as $key => $app)
                            <li><strong>{{ $key }}:</strong> {{ $app }}</li>
                        @endforeach
                        </ul></td>
                        <td>{{ $row['unproductive'] }}</td>
                        <td>{{ $row['neutral'] }}</td>
                        <td>{{ $row['productive'] }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
