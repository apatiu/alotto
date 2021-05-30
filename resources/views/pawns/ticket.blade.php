@php
    $team = Auth::user()->currentTeam
@endphp
<div class="flex space-x-4">
    <div class="flex-1">
        <div class="flex items-center space-x-2">
            <div class="w-1/12">
                <img src="{{ $team->profile_photo_url }}"
                     alt="">
            </div>
            <div class="flex flex-col">
                <div>{{ $team->company_name }}</div>
                <div>{{ $team->addr }} โทร.{{ $team->phone }}</div>
            </div>
            <div class="flex-1 text-center text-lg">สัญญาขายฝาก</div>
            <div class="text-lg">{{ $pawn->code }}</div>
        </div>
        <div class="flex flex-col space-y-2">
            <div class="flex space-x-2 mt-2">
                <div class="w-1/6">ชื่อ:</div>
                <div>{{ $pawn->customer->name }}</div>
            </div>
            <div class="flex space-x-2">
                <div class="w-1/6">ที่อยู่:</div>
                <div>{{ $pawn->customer->address }}</div>
            </div>
            <div class="flex space-x-2">
                <div class="w-1/6">จำนวนเงิน:</div>
                <div>{{ number_format($pawn->price) }} บาท</div>
            </div>
            <table class="w-full border-t mt-4 table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>รายการ</th>
                    <th>น้ำหนัก</th>
                    <th>ราคา</th>
                </tr>
                </thead>
                <tbody>

                @foreach($pawn->items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->index + 1 }}</td>
                        <td>{{ $item->product_type }}</td>
                        <td class="text-center">{{ $item->weight }}</td>
                        <td class="text-right">{{ $item->price }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div style="width: 5cm;" class="pl-2 border-l text-sm">
        <div class="text-center border">{{$pawn->id}}</div>
        <div class="flex space-x-2">
            <div class="w-20">ชื่อ:</div>
            <div>{{ $pawn->customer->name }}</div>
        </div>
        <div class="flex space-x-2">
            <div class="w-20">ที่อยู่:</div>
            <div>{{ $pawn->customer->address }}</div>
        </div>
        <div class="flex space-x-2">
            <div>จำนวนเงิน:</div>
            <div>{{ $pawn->price }}</div>
        </div>
        <table class="w-full">
            <thead>
            <tr>
                <th>#</th>
                <th>รายการ</th>
                <th>น้ำหนัก</th>
                <th>ราคา</th>
            </tr>
            </thead>
            <tbody>

            @foreach($pawn->items as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item->product_type }}</td>
                    <td>{{ $item->weight }}</td>
                    <td>{{ $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
