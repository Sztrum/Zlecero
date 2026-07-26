<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Core\Domain\Domain\Services\FrontendEndpointService;
use App\V1\Modules\User\Domain\Mail\VerifyEmailMail;
use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthEmailVerificationLinkContractTest extends TestCase
{
    public function test_verification_mail_points_to_react_verification_route_with_required_query_params(): void
    {
        config(['core::frontend.url' => 'http://localhost:5173']);

        $user = new User();
        $user->forceFill([
            'id' => (string) Str::uuid(),
            'name' => 'Konrad Nowicki',
            'email' => 'zlecero.verify.contract@gmail.com',
            'password' => Hash::make('secret-password'),
        ]);

        $mail = new VerifyEmailMail($user);
        $viewData = $mail->build(app(FrontendEndpointService::class))->viewData;

        self::assertIsString($viewData['frontendUrl']);
        self::assertStringStartsWith('http://localhost:5173/auth/verify-email?', $viewData['frontendUrl']);
        self::assertStringContainsString('user_id='.$user->id, $viewData['frontendUrl']);
        self::assertStringContainsString('hash='.$user->generateHashToEmailVerification(), $viewData['frontendUrl']);
    }
}
