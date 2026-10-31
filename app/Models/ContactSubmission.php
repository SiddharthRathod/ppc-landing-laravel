<?php

namespace App\Models;

use App\Enums\CrmStatus;
use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\ContactSubmissionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'service',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'notes',
        'crm_status',
        'crm_response',
        'sent_at',
    ];

    /**
     * New enquiries start as "new" (same default as the database column),
     * so the model has the value right after create().
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'status' => 'new',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => EnquiryStatus::class,
            'crm_status' => CrmStatus::class,
            'sent_at' => 'datetime',
        ];
    }
}
