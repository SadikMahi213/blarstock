<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code', 'balance', 'kyc_data'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
        'author_data'       => 'object'
    ];

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=' , ManageStatus::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function form() {
        return $this->belongsTo(Form::class);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function approvedImages() {
        return $this->images()->where('status', ManageStatus::IMAGE_APPROVED);
    }

    public function followers() {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following() {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function collections() {
        return $this->hasMany(Collection::class);
    }

    public function collectionImages() {
        return $this->hasManyThrough(CollectionImage::class, Collection::class);
    }

    public function refBy()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class,'ref_by');
    }

    public function allReferrals(){

        return $this->referrals()->with('refBy');
    }


    public function commissions() {
        return $this->hasMany(CommissionLog::class, 'to_id', 'id');
    }

    public function earnings() {
        return $this->hasMany(EarningRecord::class, 'author_id', 'id')->whereHas('author', fn($query) => $query->where('author_status', ManageStatus::AUTHOR_APPROVED));
    }

    public function totalEarnings() {
        return $this->hasMany(EarningRecord::class, 'author_id', 'id')->whereHas('author', fn($query) => $query->whereIn('author_status', [ManageStatus::AUTHOR_APPROVED, ManageStatus::AUTHOR_BANNED]));
    }

    public function getTotalEarningsSumAttribute() {
        return $this->totalEarnings->sum('amount');
    }

    public function socialProfiles() {
        return $this->hasMany(SocialProfile::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', ManageStatus::ACTIVE)->where('ec', ManageStatus::VERIFIED)->where('sc', ManageStatus::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', ManageStatus::INACTIVE);
    }

    public function scopeEmailUnconfirmed($query)
    {
        return $query->where('ec', ManageStatus::UNVERIFIED);
    }

    public function scopeMobileUnconfirmed($query)
    {
        return $query->where('sc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycUnconfirmed($query)
    {
        return $query->where('kc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kc', ManageStatus::PENDING);
    }

    public function scopePendingAuthor($query) {
        return $query->where('author_status', ManageStatus::AUTHOR_PENDING);
    }

    public function scopeApprovedAuthor($query) {
        return $query->where('author_status', ManageStatus::AUTHOR_APPROVED);
    }

    public function scopeRejectedAuthor($query) {
        return $query->where('author_status', ManageStatus::AUTHOR_REJECTED);
    }

    public function scopeBannedAuthor($query) {
        return $query->where('author_status', ManageStatus::AUTHOR_BANNED);
    }

    public function  scopeAuthorIndex($query) {
        return $query->whereIn('author_status', [ManageStatus::AUTHOR_APPROVED, ManageStatus::AUTHOR_BANNED, ManageStatus::AUTHOR_PENDING, ManageStatus::AUTHOR_REJECTED]);
    }

    public function authorStatusBadge(): Attribute {
        return new Attribute(function() {
            $html = '';

            if ($this->author_status == ManageStatus::AUTHOR_APPROVED) {
                $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
            } else if ($this->author_status == ManageStatus::AUTHOR_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else if ($this->author_status == ManageStatus::AUTHOR_BANNED) {
                $html = '<span class="badge badge--dark">' . trans('Banned') . '</span>';
            } else if ($this->author_status == ManageStatus::AUTHOR_REJECTED) {
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            }

            return $html;
        });
    }
}
