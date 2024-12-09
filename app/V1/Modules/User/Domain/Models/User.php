<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Core\Infrastructure\Packages\Sanctum\Models\PersonalAccessToken;
use App\V1\Modules\Company\Domain\Models\Company;
use App\V1\Modules\Company\Domain\Models\CompanyInvitation;
use App\V1\Modules\User\Domain\Events\UserHasBeenCreatedEvent;
use DateTimeInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

/**
 *
 *
 * @property string                                                                                                        $id
 * @property string                                                                                                        $name
 * @property string                                                                                                        $email
 * @property string|null                                                                                                   $email_verified_at
 * @property string                                                                                                        $password
 * @property string|null                                                                                                   $remember_token
 * @property \Illuminate\Support\Carbon|null                                                                               $created_at
 * @property \Illuminate\Support\Carbon|null                                                                               $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, Company>                                                        $companies
 * @property int|null                                                                                                      $companies_count
 * @property \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property int|null                                                                                                      $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection<int, PersonalAccessToken>                                            $tokens
 * @property int|null                                                                                                      $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    query()
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User                                                                    whereUpdatedAt($value)
 * @property \Illuminate\Database\Eloquent\Collection<int, CompanyInvitation>                                              $receivedCompanyInvitations
 * @property int|null                                                                                                      $received_company_invitations_count
 * @property \Illuminate\Database\Eloquent\Collection<int, CompanyInvitation>                                              $sentCompanyInvitations
 * @property int|null                                                                                                      $sent_company_invitations_count
 * @mixin \Eloquent
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use Authenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [

    ];

    protected $dispatchesEvents = [
        'created' => UserHasBeenCreatedEvent::class,
    ];

    public function createToken(
        string $name,
        array $abilities = ['*'],
        DateTimeInterface $expiresAt = null
    ): NewAccessToken {
        /** @var PersonalAccessToken $token */
        $token = $this->tokens()
            ->create([
                'name' => $name,
                'token' => hash('sha256', $plainTextToken = Str::random(40)),
                'abilities' => $abilities,
                'expires_at' => $expiresAt,
            ]);

        $plainToken = $token->getKey() . '|' . $plainTextToken;

        return new NewAccessToken($token, encrypt($plainToken));
    }

    public function generateHashToEmailVerification(): string
    {
        return hash('sha256', $this->getEmailForVerification());
    }

    public function verifyEmailVerificationHash(string $hash): bool
    {
        return $hash === $this->generateHashToEmailVerification();
    }
}
