<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\RoleLayout;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Displays the profile page for every role. Saving is delegated to Fortify's own
 * routes (PUT /user/profile-information and PUT /user/password), which reuse
 * App\Actions\Fortify\UpdateUserProfileInformation and UpdateUserPassword.
 */
class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $layout = RoleLayout::for($user);
        $pageTitle = 'Mon Profil';
        $breadcrumb = ['Mon Profil'];

        return view('profile.show', compact('user', 'layout', 'pageTitle', 'breadcrumb'));
    }
}
