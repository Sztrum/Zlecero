<?php

declare(strict_types=1);

namespace App\V1\Modules\User\UI\Http\Controllers;

use App\V1\Core\UI\Http\Controllers\ApiController;
use App\V1\Modules\Company\UI\Http\Resources\CollectionCompanyResource;
use App\V1\Modules\Company\UI\Http\Resources\Invitations\CollectionCompanyInvitationResource;
use App\V1\Modules\User\Application\Queries\IndexUserCompaniesQuery;
use App\V1\Modules\User\Application\Queries\IndexUserReceivedCompanyInvitationsQuery;
use Illuminate\Http\Request;
use Throwable;

class UserController extends ApiController
{
    /**
     * @throws Throwable
     */
    public function companies(
        Request $request,
        string $user_id,
        IndexUserCompaniesQuery $query,
    ): CollectionCompanyResource {
        return new CollectionCompanyResource(
            resource: $query->execute($user_id),
            relationships: 'children',
            asResponse: true,
        );
    }

    /**
     * @throws Throwable
     */
    public function invitations(
        Request $request,
        string $user_id,
        IndexUserReceivedCompanyInvitationsQuery $query,
    ): CollectionCompanyInvitationResource {
        return new CollectionCompanyInvitationResource(
            resource: $query->execute(
                user_id: $user_id,
            ),
            relationships: 'company,invited_user,inviting_user',
            asResponse: true,
        );
    }
}
