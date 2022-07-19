<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Comment extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tag()
    {
        return $this->belongsTo(User::class, 'tag_id', 'id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new User)->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    public function comment_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comment_tag()
    {
        return $this->belongsTo(User::class, 'tag_id', 'id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new User)->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    public function replies_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies_tag()
    {
        return $this->belongsTo(User::class, 'tag_id', 'id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new User)->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies_user', 'replies_tag');
    }

    public function donation_request()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id');
    }
}
