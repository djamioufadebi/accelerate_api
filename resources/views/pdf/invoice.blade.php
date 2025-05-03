<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Accelerate">
    <meta name="description" content="Facture générée par Accelerate">
    <title>Facture {{ e($invoice->invoice_number) }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            margin: 40px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24pt;
            color: #007bff;
            margin: 0;
        }

        .header .company-info {
            text-align: right;
            font-size: 10pt;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        .invoice-info table {
            width: 100%;
            font-size: 11pt;
        }

        .invoice-info td {
            padding: 5px;
        }

        .client-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .client-info h3 {
            margin-top: 0;
            color: #007bff;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table td {
            font-size: 11pt;
        }

        .total {
            text-align: right;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 10pt;
            color: #777;
            position: fixed;
            bottom: 20px;
            width: calc(100% - 80px);
        }

        @page {
            margin: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>Facture {{ e($invoice->invoice_number) }}</h1>
            <div class="company-info">
                <strong>Accelerate</strong><br>
                123 Boulevard de l'Innovation<br>
                {{-- 75001 Paris, France<br> --}}
                Email : contact@accelerate.com<br>
                {{-- SIRET : 123 456 789 00012 --}}
            </div>
        </div>

        <!-- Informations de la facture -->
        <div class="invoice-info">
            <table>
                <tr>
                    <td>
                        <strong>Date d'émission :</strong>
                        {{ e(\Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y')) }}
                    </td>
                    <td>
                        <strong>
                            Date d'échéance :
                        </strong>
                        {{ e(\Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y')) }}
                    </td>
                    {{-- <td><strong>Date d'émission :</strong> {{ e($invoice->issue_date) }}</td>
                    <td><strong>Date d'échéance :</strong> {{ e($invoice->due_date) }}</td> --}}
                </tr>
                <tr>
                    <td><strong>Statut :</strong>
                        @switch($invoice->status)
                            @case('draft')
                                Brouillon
                            @break

                            @case('paid')
                                Payée
                            @break

                            @case('cancelled')
                                Annulée
                            @break

                            @default
                                Inconnu
                        @endswitch
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- Informations du client -->
        <div class="client-info">
            <h3>Client</h3>
            @if ($invoice->client)
                <p>
                    {{ e($invoice->client->name) }}<br>
                    {{ e($invoice->client->email) }}<br>
                    @if ($invoice->client->phone)
                        {{ e($invoice->client->phone) }}<br>
                    @endif
                    @if ($invoice->client->address)
                        {{ e($invoice->client->address) }}
                    @endif
                </p>
            @else
                <p>Client non spécifié</p>
            @endif
        </div>

        <!-- Lignes de facture -->
        <h3>Lignes de facture</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Montant (€)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoice->lines as $line)
                    <tr>
                        <td>{{ e($line->description) }}</td>
                        <td>{{ number_format($line->amount, 2, ',', ' ') }} €</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">Aucune ligne de facture</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Total -->
        <div class="total">
            Total HT : {{ number_format($invoice->total_ht, 2, ',', ' ') }} €
        </div>

        <!-- Pied de page -->
        <div class="footer">
            Facture générée le {{ now()->format('d/m/Y') }} par Accelerate<br>
            Page {{ '{PAGENO}' }}
        </div>
    </div>
</body>

</html>
