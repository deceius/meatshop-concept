<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>{{$transactionHeader->ref_no }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <style>
            .articles table, .articles th, .articles td {
                border: 0px solid black;
            }
        </style>
    </head>
    <body>
        <!--<div class="d-flex justify-content-center mt-3 hidden">-->
        <!--    <h2> Kumpadres Trading Corp. - {{ $transactionHeader->branch->name }}</h2>-->
        <!--</div>-->
        <!--<div class="d-flex justify-content-center mt-0 text-center">-->
        <!--    <p> {{ $transactionHeader->branch->address }}<br>Non-VAT Reg TIN. {{ $transactionHeader->branch->tin }}</p>-->
        <!--</div>-->
        <!--<div class="px-3 mt-5">-->
        <!--    <table width=100%>-->
        <!--        <tr>-->
        <!--            <td width=60% class="text-uppercase"><h4><u>Sales Invoice</u></h4></td>-->
        <!--            <td><h5><u>No. {{$transactionHeader->invoice_no}}</u></h5></td>-->
        <!--        </tr>-->
        <!--        <tr>-->
        <!--            <td class="align-text-top">Sold To: {{ ($transactionHeader->customer == null) ? '--' : $transactionHeader->customer->name }}</td>-->
        <!--            <td class="align-text-top">Transaction Date: {{ $transactionHeader->transaction_date }}</td>-->
        <!--        </tr>-->
        <!--         <tr>-->
        <!--            <td class="align-text-top">Address: {{ ($transactionHeader->customer == null) ? '--' : $transactionHeader->customer->address }}</td>-->
        <!--            <td class="align-text-top">TIN: {{ ($transactionHeader->customer == null) ? '--' : $transactionHeader->customer->tin }}</td>-->
        <!--        </tr> -->
        <!--    </table>-->
        <!--</div>-->
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="px-3 mt-5">
            <table width=100%  class="articles text-center">
                <thead >
                    <th width=15%>&nbsp;</th>
                    <th width=50%></th>
                    <th width=15%></th>
                    <th></th>
                </thead>

                <tbody>
                    @foreach ($data as $detail)
                    <tr>
                        <td>{{ number_format($detail->quantity, 2, '.', ',')  . ' kg' }}</td>
                        <td class="text-start"> {{  $detail->item->brand->name . ' ' . $detail->item->name }}</td>
                        <td>{{ number_format($detail->amount, 2, '.', ',') }}</td>
                        <td>{{  number_format($detail->selling_price, 2, '.', ',') }}</td>
                    </tr>
                    @endforeach
                    @foreach (range(1, 15 - count($data)) as $item)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
               <tfoot>
                    <th></th>
                    <th>&nbsp;</th>
                    <th></th>
                    <th>{{  number_format($sum, 2, '.', ',') }}</th>
                </tfoot>
            </table>
        </div>

        <!--<div class="d-flex justify-content-end mt-0 px-3 text-end">-->
        <!--    <p><br><br>By: ____________________________<br>Authorized Signature &nbsp;&nbsp;&nbsp;</p>-->
        <!--</div>-->
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>
