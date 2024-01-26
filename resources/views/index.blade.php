@extends('layouts.base')

@section('js')
<script>
    let currencies = [];
    fetch('/api/currencies')
        .then(response => response.json())
        .then(jsonResponse => {
            currencies = jsonResponse.data;
            currencies.forEach(currency => {
                addCurrencyToSelect(currency);
                addCurrencyListTableRow(currency);
            });
        });

    function addCurrencyToSelect(currency){
        const selectElement = document.getElementById('currency_iso'),
            option = document.createElement('option');
        option.value = currency.iso;
        option.text = currency.iso;
        selectElement.appendChild(option);
    }

    function addCurrencyListTableRow(currency){
        const tableRow = document.createElement('tr');
        tableRow.innerHTML = `<td>${currency.iso}</td>
                              <td>${currency.exchange_rate}</td>
                              <td>${currency.surcharge_percentage}%</td>
                              <td>${currency.discount_percentage}%</td>`;
        document.getElementById('currencyList').getElementsByTagName('tbody')[0].appendChild(tableRow);
    }

    function calculateExchange() {
        const purchasedAmount = parseFloat(document.getElementById('purchased_amount').value) || 0,
            selectedCurrency = document.getElementById('currency_iso').value,
            selectedCurrencyData = currencies.find(currency => currency.iso === selectedCurrency);

        if (selectedCurrencyData) {
            const exchangeRate = selectedCurrencyData.exchange_rate,
                surchargePercentage = selectedCurrencyData.surcharge_percentage,
                discountPercentage = selectedCurrencyData.discount_percentage;

            const exchangeCost = (purchasedAmount / exchangeRate) * (1 + surchargePercentage / 100) * (1 - discountPercentage / 100);

            document.getElementById('exchange_cost').value = exchangeCost.toFixed(2);
        }
    }

    document.getElementById('purchased_amount').addEventListener('input', calculateExchange);
    document.getElementById('currency_iso').addEventListener('change', calculateExchange);
    document.getElementById('exchangeForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/api/orders', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const responseBody = await response.json();
            const alertDiv = document.getElementById('alertDiv');
            alertDiv.innerHTML = '';
            if (response.ok) {
                alertDiv.innerHTML = `
                    <div class="alert alert-success" role="alert">
                        Order #${responseBody.data.id} successfuly created!
                    </div>`;
            } else {
                alertDiv.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Error: ${responseBody.message}
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alertDiv = document.getElementById('alertDiv');
            alertDiv.innerHTML = '';
            alertDiv.innerHTML = `
            <div class="alert alert-danger" role="alert">
                An unexpected error occurred.
            </div>`;
        });
    });
</script>
@endsection

@section('content')
<div class="container mt-3">
    <h1 class="text-center mb-3">Exchange Office</h1>

    <div id="alertDiv"></div>
    <form id="exchangeForm" method="POST" action="/api/">
        @csrf
        <div class="row align-items-end">
            <div class="col">
                <label for="purchased_amount">Amount:</label>
                <input type="number" name="purchased_amount" value="0.00" step="0.01" class="form-control" id="purchased_amount">
            </div>
            <div class="col">
                <label for="currency_iso">Currency:</label>
                <select name="currency_iso" required class="form-control" id="currency_iso"></select>
            </div>
            <div class="col">
                <label for="exchange_cost">Exchange cost (in {{$defaultCurrency}}):</label>
                <input type="text" name="exchange_cost" value="0.00" disabled class="form-control" id="exchange_cost">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary mt-4">Buy</button>
            </div>
        </div>
    </form>

    <table class="table mt-5" id="currencyList">
        <thead>
            <tr>
                <th>Currency</th>
                <th>Rate</th>
                <th>Surcharge</th>
                <th>Discount</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
