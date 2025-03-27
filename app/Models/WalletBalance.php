<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $balance
 * @property Carbon $created_at
 * @property string $wallet_id
 */
class WalletBalance extends Model
{
    use HasUuids;

    protected $table = 'wallet_balances';

    public $timestamps = false;
}
