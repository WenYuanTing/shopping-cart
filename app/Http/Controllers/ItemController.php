<?php

namespace App\Http\Controllers;
use App\Models\Item;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function updateItemStatus(Request $request)
    {
        $itemId = $request->input('itemId');

        $item = Item::find($itemId);
        if ($item) {
            $item->is_active = true;
            $item->save();
            // 其他相關更新操作...
            return response()->json(['message' => '商品狀態已更新']);
        } else {
            return response()->json(['error' => '找不到該商品']);
        }
    }
}
