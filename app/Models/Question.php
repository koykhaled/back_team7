<?php

namespace App\Models;

use App\Http\Controllers\Api\UuidGenerater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'reference',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'importants', 'question_id');
    }
    public function choices(): BelongsToMany
    {
        return $this->belongsToMany(Choice::class, 'question_choices', 'question_id');
    }
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_questions', 'question_id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}