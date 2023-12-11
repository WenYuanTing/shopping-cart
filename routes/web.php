<?php
use App\Models\Item;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
    ]);
    //$data['is_active'] = $request->has('is_active');
    $item=Item::findorFail($id);
    $item->name=$data['name'];
    $item->description=$data['description'];
    $item->price=$data['price'];
    $item->quantity=$data['quantity'];
    //$item->is_active = $data['is_active'];
    $item->save();

    //$item=Item::create($request->validate());

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
