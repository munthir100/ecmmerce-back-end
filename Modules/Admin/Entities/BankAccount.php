<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'holder_name',
        'details',
        'iban',
        'bank_id',
    ];

    function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
