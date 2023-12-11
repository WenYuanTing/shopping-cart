<?php
use App\Models\Item;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// 登入路由
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// 註冊路由
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', function () {
    $items=Item::latest()->get();

    return view('index',compact('items'));
})->name('items.index');

Route::get('/allItems', function () {
    $items=Item::latest()->get();

    return view('allItems',compact('items'));
})->name('items.all');

Route::view('/items/create','create')->name('items.create');



Route::post('/items',function(Request $request){
    $data=$request->validate([
        'name'=>'required|max:25',
        'description'=> 'required|max:255',
        'price'=> 'required|max:99999' ,
        'quantity'=> 'required',
    ]);
    $data['is_active'] = $request->has('is_active');

    $item=new Item;
    $item->name=$data['name'];
    $item->description=$data['description'];
    $item->price=$data['price'];
    $item->quantity=$data['quantity'];
    $item->is_active = $data['is_active'];

    $item->save();

    //$item=Item::create($request->validate());

    return redirect()->route('items.index');

})->name('items.store');

Route::post('/items/{itemId}/activate', function ($itemId) {
    // 根據 $itemId 從資料庫中找到對應的商品
    $item = Item::find($itemId);
    if($item->is_active == true){
        $item->is_active = false;
    }else{
        $item->is_active = true;
    }
    $item->save();
    
    // 回傳成功訊息或其他需要的資料
    return redirect()->route('items.index');
});
