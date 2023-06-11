@inject('carbon', 'Carbon\Carbon')
<table>
    <thead>
        <tr>
            <th colspan="7">{{ $date }} - Sales</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Reference Number</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Branch</th>
            <th>Remarks</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach($sales as $key => $item)
        <tr>
            <td>{{ $key + 1}}</td>
            <td>{{ $item->ref_no }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->type == 1 ? 'Paid' : 'Accounts Receivable' }}</td>
            <td>{{ $item->branch_name }}</td>
            <td>{{ $item->remarks }}</td>
            <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="7">{{ $date }} - Payments</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Reference Number</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Branch</th>
            <th>Remarks</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach($payments as $key => $item)
        <tr>
            <td>{{ $key + 1}}</td>
            <td>{{ $item->ref_no }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->branch_name }}</td>
            <td>{{ $item->remarks }}</td>
            <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    <tr>
        <td>&nbsp;</td>
        <td>TOTAL Payments</td>
        <td>{{ $payments->sum('amount') }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="7">{{ $date }} - Expenses</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Reference Number</th>
            <th>Amount</th>
            <th>Payment Type</th>
            <th>Branch</th>
            <th>Remarks</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach($expenses as $key => $item)
        <tr>
            <td>{{ $key + 1}}</td>
            <td>{{ $item->ref_no }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->branch_name }}</td>
            <td>{{ $item->remarks }}</td>
            <td>{{ $carbon::parse($item->updated_at)->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    <tr>
        <td>&nbsp;</td>
        <td>TOTAL Expenses</td>
        <td>{{ $expenses->sum('amount') }}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>
<br>
<table>
    <thead>
        <tr>
            <th colspan="3">Summary</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>Summary</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>&nbsp;</td>
            <td>GROSS</td>
            <td>{{ $payments->sum('amount') }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Expenses</td>
            <td>{{ $expenses->sum('amount') }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>Net Today</td>
            <td>{{ $payments->sum('amount') - $expenses->sum('amount') }}</td>
        </tr>
    </tbody>
</table>
