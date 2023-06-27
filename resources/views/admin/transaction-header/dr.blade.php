<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>{{$transactionHeader->ref_no }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <style>
            .articles table, .articles th, .articles td {
                border: 1px solid black;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div class="d-flex justify-content-center mt-3">
            <h2> Kumpadres Trading Corp. - {{ $transactionHeader->branch->name }}</h2>
        </div>
        <div class="d-flex justify-content-center mt-0 text-center">
            <p> {{ $transactionHeader->branch->address }}<br>Non-VAT Reg TIN. {{ $transactionHeader->branch->tin }}</p>
        </div>
        <div class="px-3 mt-5">
            <table width=100%>
                <tr>
                    <td width=60% class="text-uppercase"><h4><u>Delivery Receipt</u></h4></td>
                    <td><h5><u>No. {{ $transactionHeader->ref_no }}</u></h5></td>
                </tr>
                <tr>
                    <td class="align-text-top">Delivered To Branch: {{ $deliveryBranch->name }}</td>
                    <td class="align-text-top">Transfer Date: {{ $transactionHeader->transaction_date }}</td>
                </tr>
                <tr>
                    <td class="align-text-top">Source Branch: {{ $transactionHeader->branch->name }}</td>
                    <td class="align-text-top">Created By: {{ $transactionHeader->created_by }}</td>
                </tr>
                @if($deliveryTransaction?->status == 1)
                <tr>
                    <td class="align-text-top">Delivered By: {{ $deliveryTransaction->delivered_by ? $deliveryTransaction->delivered_by : '--' }}</td>
                    <td class="align-text-top">Received By: {{ $deliveryTransaction->received_by }}</td>
                </tr>
                @endif
            </table>
        </div>
        <div class="px-3 mt-5">
            <table width=100%  class="articles text-center">
                <thead >
                    <th width=15%>QR Code</th>
                    <th width=25%>Particulars</th>
                    <th width=15%>Qty. (kg)</th>
                </thead>

                <tbody>
                    @foreach ($data as $detail)
                    <tr>
                        <td class="text-start"> {{ $detail->qr_code }}</td>
                        <td class="text-start"> {{  $detail->item->brand->name . ' ' . $detail->item->name }}</td>
                        <td class="text-end">{{ number_format($detail->quantity, 2, '.', ',')  . ' kg' }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class="text-end" colspan="2"> Total Quantity (kg)</th>
                        <th class="text-end">{{ number_format($data->sum('quantity'), 2, '.', ',')  . ' kg' }}</th>
                    </tr>
                    <tr>
                        <th class="text-end" colspan="2"> Total Boxes </th>
                        <th class="text-end">{{ count($data) }}</th>
                    </tr>
                </tbody>


            </table>
        </div>

        {{-- <div class="d-flex justify-content-end mt-0 px-3 text-end">
            <p><br><br>By: ____________________________<br>Authorized Signature &nbsp;&nbsp;&nbsp;</p>
        </div> --}}
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>
