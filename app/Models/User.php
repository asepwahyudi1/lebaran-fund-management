<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone_number', 'address', 'package_id', 'start_date', 'duration_weeks'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'start_date' => 'date',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    public function getElapsedWeeksAttribute(): int
    {
        if (!$this->start_date) {
            return 0;
        }
        $diff = $this->start_date->diffInDays(now(), false);
        if ($diff < 0) return 0;
        return (int) floor($diff / 7);
    }

    public function getCurrentWeekAttribute(): int
    {
        if (!$this->package) return 0;
        return min($this->duration_weeks, $this->elapsed_weeks + 1);
    }

    public function getCurrentWeekDeadlineAttribute()
    {
        if (!$this->start_date) return null;
        $currentWeek = $this->current_week;
        return $this->start_date->copy()->addDays($currentWeek * 7);
    }

    public function getExpectedPaidAmountAttribute(): float
    {
        $package = $this->package;
        if (!$package) return 0;
        $duration = $this->duration_weeks > 0 ? $this->duration_weeks : ($package->duration_weeks ?: 40);
        $weekly = (float) ($package->price / $duration);
        return $weekly * $this->elapsed_weeks;
    }

    public function getTotalVerifiedPaidAttribute(): float
    {
        return (float) $this->payments()->where('status', 'verified')->sum('amount');
    }

    public function getWeeklyStatusAttribute(): string
    {
        if (!$this->package) return 'Lancar';
        return $this->total_verified_paid >= $this->expected_paid_amount ? 'Lancar' : 'Menunggak';
    }

    public function getArrearsAmountAttribute(): float
    {
        if ($this->weekly_status === 'Lancar') {
            return 0;
        }
        return max(0, $this->expected_paid_amount - $this->total_verified_paid);
    }

    public function getInstallmentCalendar(): array
    {
        $weeks = [];
        $package = $this->package;
        if (!$package) {
            return [];
        }
        
        $duration = $this->duration_weeks > 0 ? $this->duration_weeks : ($package->duration_weeks ?: 40);
        $weeklyInstallment = (float) ($package->price / $duration);
        
        $totalVerified = $this->total_verified_paid;
        $totalPending = (float) $this->payments()->where('status', 'pending')->sum('amount');
        
        $allocatedVerified = $totalVerified;
        $allocatedPending = $totalPending;
        
        for ($w = 1; $w <= $duration; $w++) {
            $deadline = $this->start_date ? $this->start_date->copy()->addDays($w * 7) : null;
            $isDeadlinePassed = $deadline ? $deadline->isPast() : false;
            
            if ($allocatedVerified >= $weeklyInstallment) {
                $status = 'verified'; // Green
                $allocatedVerified -= $weeklyInstallment;
            } elseif (($allocatedVerified + $allocatedPending) >= $weeklyInstallment) {
                $status = 'pending'; // Yellow
                $allocatedPending = ($allocatedVerified + $allocatedPending) - $weeklyInstallment;
                $allocatedVerified = 0;
            } else {
                $allocatedVerified = 0;
                $allocatedPending = 0;
                
                if ($isDeadlinePassed) {
                    $status = 'late'; // Red
                } else {
                    $status = 'unpaid'; // Grey
                }
            }
            
            $weeks[] = [
                'number' => $w,
                'status' => $status,
                'deadline' => $deadline,
            ];
        }
        
        return $weeks;
    }
}
