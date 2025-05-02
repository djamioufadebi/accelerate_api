<!DOCTYPE html>
<html>

<head>
    <title>FACTURE N° : {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .client-info {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Invoice #{{ $invoice->invoice_number }}</h1>
        <p>Issued: {{ $invoice->issue_date->toDateString() }} | Due: {{ $invoice->due_date->toDateString() }}</p>
    </div>

    <div class="client-info">
        <h3>Client</h3>
        <p>
            {{ $client->name }}<br>
            {{ $client->email }}<br>
            @if ($client->phone)
                {{ $client->phone }}<br>
            @endif
            @if ($client->address)
                {{ $client->address }}
            @endif
        </p>
    </div>

    <h3>Invoice Lines</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lines as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td>{{ number_format($line->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total HT: {{ number_format($invoice->total_ht, 2) }} €
    </div>
</body>

</html>
