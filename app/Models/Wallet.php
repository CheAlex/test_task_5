<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $currency
 * @property string $address
 * @property Carbon $created_at
 * @property WalletBalance $latestBalance
 */
class Wallet extends Model
{
    use HasUuids;

    protected $table = 'wallets';

    protected $fillable = ['currency', 'address', 'balance'];

    public $timestamps = false;

    protected $visible = ['address', 'created_at', 'balance', 'balance_updated_at'];

    protected $appends = ['balance', 'balance_updated_at'];

    public function latestBalance()
    {
        return $this->hasOne(WalletBalance::class)->latestOfMany('created_at');
    }

    protected function balance(): Attribute
    {
        return new Attribute(
            get: fn () => $this->latestBalance->balance,
        );
    }

    protected function balanceUpdatedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->latestBalance->created_at,
        );
    }
}
