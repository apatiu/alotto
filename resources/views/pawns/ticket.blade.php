<div class="flex space-x-4">
    <div class="flex-1 border">
        <div class="flex space-x-2">
            <div>logo</div>
            <div class="flex-1 text-center">สัญญา</div>
            <div>{{ $pawn->id }}</div>
        </div>
    </div>
    <div style="width: 5cm;" class="border">
        <div class="text-center border">{{$pawn->id}}</div>
        <div class="grid grid-cols-4">
            <div class="col-span-1">ชื่อ</div>
            <div class="col-span-3">{{ $pawn->customer->name }}</div>
        </div>

    </div>
</div>
