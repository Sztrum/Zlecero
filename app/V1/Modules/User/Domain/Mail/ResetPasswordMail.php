<?php

declare(strict_types=1);

namespace App\V1\Modules\User\Domain\Mail;

use App\V1\Core\Domain\Domain\Services\FrontendEndpointService;
use App\V1\Core\Domain\Enums\FrontEndRouteEnum;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
        private readonly string $token,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function build(FrontendEndpointService $frontendEndpointService): ResetPasswordMail
    {
        return $this->subject(
            __('user::emails.reset-password.subject')
        )
            ->view('user::emails.reset-password')
            ->with([
                'user' => $this->user,
                'frontendUrl' => $frontendEndpointService->route(FrontEndRouteEnum::AUTH_RESET_PASSWORD, [
                    'token' => $this->token,
                    'remember_token' => $this->user->getRememberToken(),
                ]),
            ]);
    }
}
