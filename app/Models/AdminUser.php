<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'password_change_required',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
            'password_change_required' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }



    /**
     * Scope for active admin users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if admin has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if admin is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if admin is editor
     */
    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    /**
     * Check if admin is regular admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get available roles
     */
    public static function getAvailableRoles(): array
    {
        return [
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator', 
            'editor' => 'Editor'
        ];
    }

    /**
     * Check if user can manage other users
     */
    public function canManageUsers(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can access system settings
     */
    public function canAccessSystemSettings(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can access integrations
     */
    public function canAccessIntegrations(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can manage backups
     */
    public function canManageBackups(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(?string $ip = null): void
    {
        try {
            $this->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip ?: request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Fallback if last_login_ip column doesn't exist
            $this->update([
                'last_login_at' => now(),
            ]);
        }
    }

    /**
     * Get the admin user who created this user
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    /**
     * Get users created by this admin
     */
    public function createdUsers()
    {
        return $this->hasMany(AdminUser::class, 'created_by');
    }

    /**
     * Get formatted last login time
     */
    public function getLastLoginFormatted(): string
    {
        if (!$this->last_login_at) {
            return 'First time';
        }

        try {
            $loginTime = is_string($this->last_login_at) 
                ? \Carbon\Carbon::parse($this->last_login_at) 
                : $this->last_login_at;
            
            return $loginTime->diffForHumans();
        } catch (\Exception $e) {
            return $this->last_login_at;
        }
    }

    /**
     * Check if last login was today
     */
    public function isLastLoginToday(): bool
    {
        if (!$this->last_login_at) {
            return false;
        }

        try {
            $loginTime = is_string($this->last_login_at) 
                ? \Carbon\Carbon::parse($this->last_login_at) 
                : $this->last_login_at;
            
            return $loginTime->isToday();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get session duration
     */
    public function getSessionDuration(): string
    {
        if (!$this->last_login_at) {
            return '';
        }

        try {
            $loginTime = is_string($this->last_login_at) 
                ? \Carbon\Carbon::parse($this->last_login_at) 
                : $this->last_login_at;
            
            return $loginTime->diffForHumans(null, true);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get lessons created by this user
     */
    public function lessons()
    {
        return $this->hasMany(\App\Models\Lesson::class, 'created_by');
    }

    /**
     * Get blog posts created by this user
     */
    public function blogs()
    {
        return $this->hasMany(\App\Models\Blog::class, 'created_by');
    }

    /**
     * Get resources created by this user
     */
    public function resources()
    {
        return $this->hasMany(\App\Models\Resource::class, 'created_by');
    }

    /**
     * Get teaching tips created by this user
     */
    public function teachingTips()
    {
        return $this->hasMany(\App\Models\TeachingTip::class, 'created_by');
    }

    /**
     * Create a new admin user with hashed password
     */
    public static function createAdmin(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $data['is_active'] ?? true;
        $data['role'] = $data['role'] ?? 'admin';

        return static::create($data);
    }
}