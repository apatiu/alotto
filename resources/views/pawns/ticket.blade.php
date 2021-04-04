<div class="flex space-x-4">
    <div class="flex-1">
        <div class="flex space-x-2">
            <div>logo</div>
            <div class="flex-1 text-center">สัญญา</div>
            <div>{{ $pawn->id }}</div>
        </div>
        <div class="flex space-x-2">
            <div>ชื่อ:</div>
            <div>{{ $pawn->customer->name }}</div>
        </div>
        <div class="flex space-x-2">
            <div>ที่อยู่:</div>
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
