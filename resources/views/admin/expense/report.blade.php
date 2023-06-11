@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Income Statement')
@inject('carbon', 'Carbon\Carbon')
@section('body')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> {{ $date }} Income Statement Summary
                    <button  onClick="monthlyIncomeReport()" class="d-print-none btn btn-primary btn-sm pull-right m-b-0 ml-2"  role="button"><i class="fa fa-file-excel-o"></i>&nbsp; Export {{ $carbon::parse($date)->format('F Y') }}</button>
                    <button  onClick="dailyIncomeReport()" class="d-print-none btn btn-success btn-sm pull-right m-b-0 ml-2"  role="button"><i class="fa fa-file-excel-o"></i>&nbsp; Export {{ $carbon::parse($date)->format('Y-m-d') }}</button>
                     <div class="d-print-none pull-right m-b-0 ml-2">
                        <div class="input-group input-group-sm">
                            <datetime id="datePickerText" class="flatpickr input-sm" placeholder="Select date" value="{{ $date }}"></datetime>
                            <span class="input-group-append">
                                <button type="button" class="btn btn-primary btn-sm" onClick="applyFilters()"><i class="fa fa-calendar-check-o"></i>&nbsp;Apply</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding:0rem;">
                    <div class="card-block" style="padding:0rem;">
                        <table class="table  table-bordered table-hover" style="margin-bottom: 0rem;">
                            <thead>
                                <tr>
                                    <th width="20%">Summary</th>
                                    <th>Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gross</td>
                                    <td >{{ number_format($payments->sum('amount'), 2) }}</td>

                                </tr>
                                <tr>
                                    <td>Expenses</td>
                                    <td >{{ number_format($expenses->sum('amount'), 2) }}</td>

                                </tr>
                                <tr>
                                    <th>Net Income</th>
                                    <th @class(['text-success' => $isProfit, 'text-danger' => !$isProfit]) >{{ number_format($payments->sum('amount') - $expenses->sum('amount'), 2) }}</th>

                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Sales

                    </div>
                <div class="card-body" style="padding:0rem;">
                    <div class="card-block" style="padding:0rem; overflow-x:auto;">
                        <table id="item-forecasting" class="table  table-bordered table-hover" style="margin-bottom: 0rem;">
                            @include('admin.expense.income-headers')
                            <tbody>
                            @foreach($sales as $key => $item)
                                <tr>
                                    {{-- <td>{{ $key + 1}}</td> --}}
                                    <td>{{ $item->ref_no }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->type == 1 ? 'Paid (' . $carbon::parse($item->updated_at)->format('Y-m-d') .')'  : 'Accounts Receivable' }}</td>
                                    <td>{{ $item->branch_name }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $carbon::parse($item->transaction_date)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                            @if (!count($sales) > 0)
                                <td colspan="7" class="text-center">No Data.</td>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Payments

                    </div>
                <div class="card-body" style="padding:0rem;">
                    <div class="card-block" style="padding:0rem; overflow-x:auto;">
                        <table id="item-forecasting" class="table  table-bordered table-hover" style="margin-bottom: 0rem;">

                            @include('admin.expense.income-headers')
                            <tbody>
                            @foreach($payments as $key => $item)
                                <tr>
                                    {{-- <td>{{ $key + 1}}</td> --}}
                                    <td>{{ $item->ref_no }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->branch_name }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                            @if (!count($payments) > 0)
                                <td colspan="7" class="text-center">No Payments.</td>
                            @else
                                <tr class="text-right">
                                    <th>Total Payments</td>
                                    <th >{{ number_format($payments->sum('amount'), 2) }}</th>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Expenses

                    </div>
                <div class="card-body" style="padding:0rem;">
                    <div class="card-block" style="padding:0rem; overflow-x:auto;">
                        <table id="item-forecasting" class="table  table-bordered table-hover" style="margin-bottom: 0rem;">

                        @include('admin.expense.income-headers')
                            <tbody>
                            @foreach($expenses as $key => $item)
                                <tr>
                                    {{-- <td>{{ $key + 1}}</td> --}}
                                    <td>{{ $item->ref_no }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->branch_name }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach

                            @if (!count($expenses) > 0)
                                <td colspan="7" class="text-center">No Expenses.</td>
                            @else
                            <tr class="text-right">
                                <th>Total Expenses</td>
                                <th >{{ number_format($expenses->sum('amount'), 2) }}</th>
                                <td colspan="4">&nbsp;</td>
                            </tr>
                            @endif
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
    function applyFilters() {
        window.location.assign('?date=' + document.getElementById('datePickerText').value);
    }
    function dailyIncomeReport() {
        window.location.assign('?export=true&date={!! $date !!}');
    }
    function monthlyIncomeReport() {
        window.location.assign('?export=true&month=true&date={!! $date !!}');
    }
    </script>
@endsection

