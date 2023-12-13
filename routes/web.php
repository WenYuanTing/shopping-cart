<?php
use App\Models\Item;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\UsersShoppingCart;
use App\Models\User;

use App\Mail\LaravelMail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    $items=Item::latest()->get();

    return view('index',compact('items'));
})->name('items.index');

Route::get('/allItems', function () {
    $items=Item::latest()->get();

    return view('allItems',compact('items'));
})->name('items.all');

Route::view('/items/create','create')->name('items.create');

Route::get('/items/{id}/edit', function ($id) {
    return view('edit',[
        'item'=>Item::findorFail($id),
    ]);
})->name('items.edit');

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

Route::put('/items/{id}',function($id, Request $request){
    $data=$request->validate([
        'name'=>'required|max:25',
        'description'=> 'required|max:255',
        'price'=> 'required|max:99999' ,
        'quantity'=> 'required',
        'push_notification' => 'nullable|boolean',
        'original_price' => 'required|numeric',
    ]);
    $item=Item::findorFail($id);
    $item->name=$data['name'];
    $item->description=$data['description'];
    $item->price=$data['price'];
    $item->quantity=$data['quantity'];
    $item->save();

    UsersShoppingCart::where('product_id', $item->id)
    ->update(['price' => $data['price']]);

    $item->push_notification = $data['push_notification'] ?? false;
    if ($item->push_notification) {
        $users = User::all();
        foreach ($users as $user) {
            // 使用 Laravel 郵件發送郵件給每個用戶
            Mail::to($user->email)->send(new LaravelMail($item));
        }
    }

    if($data['original_price']>=$data['price']){
        $usersWithItemInCart = UsersShoppingCart::where('product_id', $item->id)->pluck('user_id');
        $userEmails = User::whereIn('id', $usersWithItemInCart)->pluck('email');
        foreach ($userEmails as $email) {
            Mail::to($email)->send((new LaravelMail($item))->setCustomView('SpecialPrice'));
        }
    }

    return redirect()->route('items.index');

})->name('items.update');




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


Route::get('/login', function () {
    return Inertia::render('dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('login');

Route::get('/dashboard', function () {
    return redirect()->route('items.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



Route::get('/shoppingCart/{id}', function ($id) {
    $userShoppingCartItems = UsersShoppingCart::where('user_id', $id)->latest()->get();

    
    return view('shoppingCart',compact('userShoppingCartItems'));
})->name('userShoppingCartItems');

Route::get('/itemAddToCart/{id}', [ItemController::class, 'addItemToCart'])->name('itemAddToCart');




Route::put('/item/increaseQuantity/{itemId}', function($itemId, Request $request){
    try {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|integer', // 添加用户ID的验证规则
        ]);

        $userId = $data['user_id'];

        // 根据 user_id 和 item_id 找到购物车项
        $item = UsersShoppingCart::where('user_id', $userId)
            ->where('product_id', $itemId)
            ->firstOrFail();
        $itemStockQuantity = Item::where('id', $itemId)->value('quantity');
        if( $item->quantity < $itemStockQuantity ){
            // 更新数量
            $item->quantity++;
            $item->save();
        }
       
        ItemController::getTotalQuantity();

        return response()->json(['message' => 'Quantity increased successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('item.increaseQuantity');


Route::put('/item/decreaseQuantity/{itemId}', function($itemId, Request $request){
    try {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'user_id' => 'required|integer', // 添加用户ID的验证规则
        ]);

        $userId = $data['user_id'];

        // 根据 user_id 和 item_id 找到购物车项
        $item = UsersShoppingCart::where('user_id', $userId)
            ->where('product_id', $itemId)
            ->firstOrFail();

        // 更新数量
        $item->quantity--;
        if($item->quantity==0){
            $item->delete();
        }else{
            $item->save();

        }
        ItemController::getTotalQuantity();

        return response()->json(['message' => 'Quantity decreased successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('item.decreaseQuantity');
