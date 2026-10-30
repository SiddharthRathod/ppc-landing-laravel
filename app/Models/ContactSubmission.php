<?php

namespace App\Models;

use App\Enums\CrmStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\ContactSubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'service',
        'message',
        'ip_address',
        'user_agent',
        'crm_status',
        'crm_response',
        'sent_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'crm_status' => CrmStatus::class,
            'sent_at' => 'datetime',
        ];
    }
}
