<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone_number', 'address'])]
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

    public function packages()
    {
        return $this->belongsToMany(Package::class)
            ->withPivot('start_date', 'duration_weeks')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
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

    public function getEarliestStartDate()
    {
        $firstPkg = $this->packages->sortBy(function($pkg) {
            return $pkg->pivot->start_date;
        })->first();
        return $firstPkg ? \Carbon\Carbon::parse($firstPkg->pivot->start_date) : null;
    }

    public function getElapsedWeeksAttribute(): int
    {
        $startDate = $this->getEarliestStartDate();
        if (!$startDate) {
            return 0;
        }
        $diff = $startDate->diffInDays(now(), false);
        if ($diff < 0) return 0;
        return (int) floor($diff / 7);
    }

    public function getCurrentWeekAttribute(): int
    {
        if ($this->packages->isEmpty()) return 0;
        $maxDuration = $this->packages->max(function($pkg) {
            return $pkg->pivot->duration_weeks;
        });
        return min($maxDuration, $this->elapsed_weeks + 1);
    }

    public function getCurrentWeekDeadlineAttribute()
    {
        $startDate = $this->getEarliestStartDate();
        if (!$startDate) return null;
        $currentWeek = $this->current_week;
        return $startDate->copy()->addDays($currentWeek * 7);
    }

    public function getExpectedPaidAmountAttribute(): float
    {
        $total = 0;
        foreach ($this->packages as $pkg) {
            $startDate = \Carbon\Carbon::parse($pkg->pivot->start_date);
            $diff = $startDate->diffInDays(now(), false);
            if ($diff < 0) continue;
            $elapsed = (int) floor($diff / 7);
            $duration = $pkg->pivot->duration_weeks ?: 40;
            $elapsed = min($duration, $elapsed);
            
            $weekly = (float) ($pkg->price / $duration);
            $total += $weekly * $elapsed;
        }
        return $total;
    }

    public function getTotalVerifiedPaidAttribute(): float
    {
        return (float) $this->payments()->where('status', 'verified')->sum('amount');
    }

    public function getWeeklyStatusAttribute(): string
    {
        if ($this->packages->isEmpty()) return 'Lancar';
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
        if ($this->packages->isEmpty()) {
            return [];
        }

        $earliestStart = $this->getEarliestStartDate();
        if (!$earliestStart) {
            return [];
        }

        // 1. Calculate overall calendar length based on relative end weeks of all packages
        $overallDuration = 0;
        $packagesData = [];

        foreach ($this->packages as $pkg) {
            $pkgStart = \Carbon\Carbon::parse($pkg->pivot->start_date);
            // Calculate start week offset (0-indexed offset in weeks)
            $diffDays = $earliestStart->diffInDays($pkgStart, false);
            $offsetWeeks = max(0, (int) round($diffDays / 7));
            
            $duration = $pkg->pivot->duration_weeks ?: 40;
            $endWeek = $offsetWeeks + $duration;
            if ($endWeek > $overallDuration) {
                $overallDuration = $endWeek;
            }

            $packagesData[] = [
                'weekly_installment' => (float) ($pkg->price / $duration),
                'start_week' => $offsetWeeks + 1, // 1-indexed for comparison
                'end_week' => $endWeek,
            ];
        }

        // 2. Map weekly expected installments and deadlines
        $weeklyExpectations = [];
        for ($w = 1; $w <= $overallDuration; $w++) {
            $expectedForWeek = 0;
            foreach ($packagesData as $data) {
                if ($w >= $data['start_week'] && $w <= $data['end_week']) {
                    $expectedForWeek += $data['weekly_installment'];
                }
            }
            $deadline = $earliestStart->copy()->addDays($w * 7);
            $weeklyExpectations[$w] = [
                'amount' => $expectedForWeek,
                'deadline' => $deadline,
            ];
        }

        // 3. Allocate paid and pending balances across the weeks
        $totalVerified = $this->total_verified_paid;
        $totalPending = (float) $this->payments()->where('status', 'pending')->sum('amount');
        
        $weeks = [];
        for ($w = 1; $w <= $overallDuration; $w++) {
            $req = $weeklyExpectations[$w]['amount'];
            $deadline = $weeklyExpectations[$w]['deadline'];
            $isDeadlinePassed = $deadline->isPast();

            $allocatedVerified = min($totalVerified, $req);
            $totalVerified -= $allocatedVerified;

            $remainingReq = $req - $allocatedVerified;
            $allocatedPending = min($totalPending, $remainingReq);
            $totalPending -= $allocatedPending;

            if ($allocatedVerified >= $req) {
                $status = 'verified'; // Green
            } elseif (($allocatedVerified + $allocatedPending) >= $req) {
                $status = 'pending'; // Yellow
            } else {
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
                'expected_amount' => $req,
                'paid_amount' => $allocatedVerified,
                'pending_amount' => $allocatedPending,
            ];
        }

        return $weeks;
    }

    public function checkDueNotifications()
    {
        if ($this->packages->isEmpty()) return;
        
        $calendar = $this->getInstallmentCalendar();
        foreach ($calendar as $week) {
            if (in_array($week['status'], ['late', 'unpaid']) && $week['deadline']) {
                $isPast = $week['deadline']->isPast();
                // diffInDays will return positive if deadline is in the future, negative if in the past
                $diffDays = now()->diffInDays($week['deadline'], false);
                $isNear = $diffDays <= 3 && $diffDays >= 0; // deadline is within 3 days from now
                
                if ($isPast || $isNear) {
                    $type = "due_warning_week_{$week['number']}";
                    
                    $exists = Notification::where('user_id', $this->id)
                        ->where('type', $type)
                        ->exists();
                        
                    if (!$exists) {
                        $dueDateStr = $week['deadline']->format('d M Y');
                        $weeklyAmt = number_format($week['expected_amount'], 0, ',', '.');
                        
                        if ($isPast) {
                            $title = "Tagihan Minggu ke-{$week['number']} Terlambat! ⚠️";
                            $message = "Setoran cicilan Minggu ke-{$week['number']} Anda telah melewati batas tenggat ({$dueDateStr}). Harap segera lakukan transfer sebesar Rp {$weeklyAmt} untuk menjaga kelancaran tabungan Anda.";
                        } else {
                            $title = "Pengingat Tenggat Cicilan Minggu ke-{$week['number']} ⏰";
                            $message = "Setoran cicilan Minggu ke-{$week['number']} sebesar Rp {$weeklyAmt} akan jatuh tempo pada {$dueDateStr}. Harap lakukan pembayaran tepat waktu.";
                        }
                        
                        Notification::create([
                            'user_id' => $this->id,
                            'title' => $title,
                            'message' => $message,
                            'type' => $type,
                            'is_read' => false,
                        ]);
                    }
                }
            }
        }
    }
}
