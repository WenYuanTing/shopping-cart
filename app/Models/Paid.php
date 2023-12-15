<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    use HasFactory; 
    protected $table = 'paid'; // 指定資料表名稱
    protected $fillable = [
        'user_id',
        'MerchantTradeNo',
        'PaymentDate',
        'TradeAmt',
        'TradeDate',
        'RtnMsg',
        'Recipient',
        'ContactNumber',
        'ShippingAddress',
        'ItemName',
        'ItemPrice',
        'ItemQuantity',
        'invoiceNo', // 新增欄位
        'invoiceDate', // 新增欄位
        'randomNumber', // 新增欄位
    ];
}
