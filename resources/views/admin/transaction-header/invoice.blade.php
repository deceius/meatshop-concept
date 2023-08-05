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
                        <td><small>{{ number_format($detail->quantity, 2, '.', ',')  . ' kg' }}</small></td>
                        <td class="text-start"><small>{{  $detail->item->brand->name . ' ' . $detail->item->name }}</small></td>
                        <td><small>{{ number_format($detail->amount, 2, '.', ',') }}</small></td>
                        <td><small>{{  number_format($detail->selling_price, 2, '.', ',') }}</small></td>
                    </tr>
                    @endforeach
                    @foreach (range(1, 15 - count($data)) as $item)
                    <tr>
                        <td><small>&nbsp;</small></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
               <tfoot>
                    <th></th>
                    <th><small>&nbsp;</small></th>
                    <th></th>
                    <th><small>{{  number_format($sum, 2, '.', ',') }}</small></th>
                </tfoot>
            </table>
        </div>

        <!--<div class="d-flex justify-content-end mt-0 px-3 text-end">-->
        <!--    <p><br><br>By: ____________________________<br>Authorized Signature &nbsp;&nbsp;&nbsp;</p>-->
        <!--</div>-->
    </body>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>
