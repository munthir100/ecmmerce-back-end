<?php

namespace Modules\Admin\Entities;

use App\Filters\BankAccountsFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory,Filterable,SoftDeletes;

    protected $fillable = [
        'account_number',
        'holder_name',
        'details',
        'iban',
        'admin_id',
        'bank_id',
    ];
    protected string $default_filters = BankAccountsFilters::class;

    function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function scopeForAdmin($query, $adminId)
    {
        return $query->whereHas('admin', function ($query) use ($adminId) {
            $query->where('id', $adminId);
        });
    }
}
