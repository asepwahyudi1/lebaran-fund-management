<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'price', 'description', 'status', 'duration_weeks', 'image_path'])]
class Package extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('start_date', 'duration_weeks')
            ->withTimestamps();
    }

    public function getWeeklyInstallmentAttribute(): float
    {
        if ($this->duration_weeks <= 0) {
            return 0;
        }
        return (float) ($this->price / $this->duration_weeks);
    }

    public function imageUrl(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800';
    }
}
