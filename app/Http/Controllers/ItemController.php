<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\UsersShoppingCart;
use Illuminate\Support\Facades\Auth;

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
    
    public function addItemToCart($id)
    {
        $data = Item::findOrFail($id);

        $existingItem = UsersShoppingCart::where('user_id', auth()->user()->id)
        ->where('product_id', $id)
        ->first();
        if ($existingItem) {
            // 如果已存在，则更新数量
            $existingItem->quantity += 1;
            $existingItem->save();
        } else {
            // 如果不存在，则创建新记录
            $data = Item::findOrFail($id);
            $item = new UsersShoppingCart;
            $item->user_id = auth()->user()->id;
            $item->product_id = $data['id'];
            $item->name = $data['name'];
            $item->price = $data['price'];
            $item->quantity = 1;
            $item->save();
        }
        ItemController::getTotalQuantity();

        return redirect()->back()->with('success', 'Book has been added to cart!');
    }

    public static function getTotalQuantity()
{
    $userId = Auth::id();
    $userShoppingCartItems = UsersShoppingCart::where('user_id', $userId)->get();
    $totalQuantity = $userShoppingCartItems->sum('quantity');

    // 儲存 totalQuantity 到 session
    session(['cartQuantity' => $totalQuantity]);
    return $totalQuantity;
}


}
