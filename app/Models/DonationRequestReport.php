<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationRequestReport extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new User())->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    public function donation_request()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new DonationRequest())->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    public function reasons()
    {
        return $this->hasOne(Reason::class, 'id', 'reason_id');
    }
}
