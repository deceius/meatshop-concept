@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Income Statement')
@inject('carbon', 'Carbon\Carbon')
@section('body')

    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Income Statement Summary
                    <a class="btn btn-danger btn-sm pull-right m-b-0 ml-2" href="{{ url('app/transaction-details/sales-report/export') }}" role="button"><i class="fa fa-file-pdf-o"></i>&nbsp; Export PDF</a>

                     <a class="btn btn-success btn-sm pull-right m-b-0 ml-2" href="{{ url('app/transaction-details/sales-report/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp; Export</a>
                     <div class="pull-right m-b-0 ml-2">
                        <div class="input-group input-group-sm">
                            <datetime class="flatpickr input-sm" placeholder="Select date" value="{{ $date }}"></datetime>
                            <span class="input-group-append">
                                <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-calendar"></i>&nbsp;Apply</button>
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
                                    <td>{{ $key + 1}}</td>
                                    <td>{{ $item->ref_no }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->type == 1 ? 'Paid' : 'Accounts Receivable' }}</td>
                                    <td>{{ $item->branch_name }}</td>
                                    <td>{{ $item->remarks }}</td>
                                    <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
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
    </div>
        <div class="row">
            <div class="col">
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
                                        <td>{{ $key + 1}}</td>
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
                                        <td>&nbsp;</td>
                                        <th>Total Payments</td>
                                        <th >{{ number_format($payments->sum('amount'), 2) }}</th>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                @endif
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
                        <i class="fa fa-align-justify"></i> Expenses

                        </div>
                    <div class="card-body" style="padding:0rem;">
                        <div class="card-block" style="padding:0rem; overflow-x:auto;">
                            <table id="item-forecasting" class="table  table-bordered table-hover" style="margin-bottom: 0rem;">

                            @include('admin.expense.income-headers')
                                <tbody>
                                @foreach($expenses as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
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
                                    <td>&nbsp;</td>
                                    <th>Total Expenses</td>
                                    <th >{{ number_format($expenses->sum('amount'), 2) }}</th>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
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

