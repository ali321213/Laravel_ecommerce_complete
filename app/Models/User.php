<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasConnectedAccounts, SetsProfilePhotoFromUrl;
    use HasProfilePhoto { HasProfilePhoto::profilePhotoUrl as getPhotoUrl; }
    use TwoFactorAuthenticatable, HasTeams;

    /* Mass assignable attributes */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'provider',
        'provider_id',
        'current_team_id',
        'profile_photo_path',
    ];

    /* Hidden attributes for arrays */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /* Attributes appended to model */
    protected $appends = [
        'profile_photo_url',
    ];

    /* Attribute casting */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Override profile photo URL to support external links */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
                ? $this->profile_photo_path
                : $this->getPhotoUrl();
        });
    }

    /* Filament admin panel access */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin' && !$this->hasRole('admin')) {
            return false;
        }
        return true;
    }

    /* Filament general access */
    public function canAccessFilament(): bool
    {
        return true; // Or: return $this->hasVerifiedEmail();
    }

    /* ---------------- Relations ---------------- */

    // Wishlist items
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Browsing history
    public function browsingHistory()
    {
        return $this->hasMany(BrowsingHistory::class);
    }

    // Ratings / Reviews
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Membership (many-to-many with Team)
    public function membership()
    {
        return $this->belongsToMany(Team::class)->withPivot('role');
    }

    // Current team
    public function latestTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    // Get tenants (all teams)
    public function getTenants(Panel $panel)
    {
        return $this->allTeams();
    }

    // Check tenant access
    public function canAccessTenant($tenant): bool
    {
        return $this->teams->contains($tenant);
    }

    // Default tenant
    public function getDefaultTenant(Panel $panel)
    {
        return $this->currentTeam;
    }
}
