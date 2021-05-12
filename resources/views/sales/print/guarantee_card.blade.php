<h2 class="text-center">{{ \App\Http\Helpers\MetaHelper::get('company_name') }}</h2>
<p class="text-center">{{ \App\Http\Helpers\MetaHelper::get('company_addr') }}</p>
<p class="text-center font-bold text-xl">ใบรับประกันสินค้า</p>
<div class="flex justify-between">
    <div class="">
        ลูกค้า: {{ $sale->customer->name }} โทร: {{ $sale->customer->phone }}<br>
        ที่อยู่: {{ $sale->customer->addr }}
    </div>
    <div>
        <div>วันที่: {{ date('d/m/Y H:i',strtotime($sale->dt)) }}</div>
        <div>วันที่พิมพ์: {{ date('d/m/Y H:i') }}</div>
    </div>
</div>
<table class="table-auto w-full">
    <thead class="border-b border-t">
    <th class="text-center">ประเภท</th>
    <th class="text-left">รายการ</th>
    <th class="text-right">น้ำหนัก</th>
    <th class="text-right">ราคา</th>
    </thead>
    <tbody>
    @foreach($sale->details as $detail)
        <tr class="border-b">
            <td class="text-center">{{ $detail->status === 'sale' ? 'ขาย' : 'ซื้อ' }}</td>
            <td>{{ $detail->product_name }}</td>
            <td class="text-right">{{ $detail->wt }}</td>
            <td class="text-right p-2">{{ $detail->price_sale_total }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="bg-yellow-100">
        <td colspan="3" class="text-right pr-4">ยอดสุทธิ</td>
        <td class="text-right font-bold p-2">{{ number_format($sale->total_amount,2) }}</td>
    </tr>
    </tfoot>
</table>
<div class="text-right mt-2 ">ผู้ทำรายการ: {{ $sale->user->name }}</div>
