<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_hash',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'description',
        'evidence_path',
        'status',
        'admin_notes',
    ];

    const STATUS_PENDING       = 'pending';
    const STATUS_INVESTIGATING = 'investigating';
    const STATUS_RESOLVED      = 'resolved';
    const STATUS_REJECTED      = 'rejected';
}
