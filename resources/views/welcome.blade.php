
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    @if (app()->environment('local'))
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @endif
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>

<body class="has-navbar-fixed-top">
    <div class="container" id="app">
        <h1 class="title is-1 has-text-centered">All ICD10 Codes Billed by UC Attendings</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Number of Charges</th>
                    <th>ICD10 Code Name</th>
                    <th>ICD10 Code Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row['numberofcharges'] }}</td>
                        <td>{{ $row['icd10codename'] }}</td>
                        <td>{{ $row['icd10codedescription'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>