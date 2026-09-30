<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Same answer whether or not the e-mail belongs to an account (or was throttled),
 * so the form cannot be used to find out which addresses are registered.
 * Bound to both Fortify contracts in FortifyServiceProvider.
 */
class PasswordResetLinkRequestedResponse implements FailedPasswordResetLinkRequestResponse, SuccessfulPasswordResetLinkRequestResponse
{
    public const MESSAGE = 'Si un compte correspond à cette adresse, un lien de réinitialisation vient d\'être envoyé.';

    public function __construct(private readonly string $status)
    {
    }

    /**
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse(['message' => self::MESSAGE], 200);
        }

        return back()->with('status', self::MESSAGE);
    }
}
