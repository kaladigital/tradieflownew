<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model {

    public $timestamps = true;

    protected $table = 'client_review';
    protected $primaryKey = 'client_review_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'client_value_id',
        'client_name',
        'client_company',
        'rate',
        'reviewer_name',
        'reviewer_email',
        'reviewer_phone',
        'reviewer_phone_country',
        'reviewer_phone_format',
        'description',
        'has_invited',
        'is_public_review'
    ];

    protected $guarded = [];
}
