@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Sales & Stock Forecasting')

@section('body')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Sales & Stock Forecasting Summary
                    </div>

                <div class="card-body" style="padding:0rem;">
                    <div class="card-block" style="padding:0rem;">
                        <table class="table  table-bordered table-hover" style="margin-bottom: 0rem;">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    @foreach ($months as $month)
                                        <th> {{$month}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th> Sales</th>
                                    @foreach ($summaryData['sales_data'] as $detail)
                                        <td> {{ number_format($detail, 2) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th> Qty</th>
                                    @foreach ($summaryData['qty_data'] as $detail)
                                        <td> {{ number_format($detail, 3) }}</td>
                                    @endforeach

                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Item Sales & Stock Forecasting

                        </div>
                        <div class="card-body" v-cloak>
                            <input id="search" class="form-control" placeholder="Filter by Item Name..."  onkeyup="myFunction()" />

                        </div>
                    <div class="card-body" style="padding:0rem;">
                        <div class="card-block" style="padding:0rem;">
                            <table id="item-forecasting" class="table  table-bordered table-hover" style="margin-bottom: 0rem;">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>&nbsp;</th>
                                        @foreach ($months as $month)
                                            <th> {{$month}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td rowspan="2"> {{$item['item_name']}}</td>
                                        <th> Sales</th>
                                        @foreach ($item['sales_data'] as $detail)
                                            <td> {{ number_format($detail, 2) }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td style="display:none;"> {{$item['item_name']}}</td>
                                        <th> Qty</th>
                                        @foreach ($item['qty_data'] as $detail)
                                            <td> {{ number_format($detail, 3) }}</td>
                                        @endforeach

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('bottom-scripts')
<script>
    function myFunction() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("search");
      filter = input.value.toUpperCase();
      table = document.getElementById("item-forecasting");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            tr[i+1].style.display = "";
          } else {
            tr[i].style.display = "none";
            tr[i+1].style.display = "none";
          }
        }
      }
    }
    </script>
@endsection
