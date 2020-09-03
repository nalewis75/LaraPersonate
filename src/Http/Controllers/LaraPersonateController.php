<?php

namespace Octopy\LaraPersonate\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Octopy\LaraPersonate\LaraPersonate;

/**
 * Class LaraPersonateController
 *
 * @package Octopy\LaraPersonate\Http\Controllers
 */
class LaraPersonateController extends Controller
{
    /**
     * @var LaraPersonate
     */
    protected $personate;

    /**
     * LaraPersonateController constructor.
     *
     * @param  LaraPersonate  $personate
     */
    public function __construct(LaraPersonate $personate)
    {
        // Check for authorization
        $this->personate = $personate;

        if(!$this->isAdmin()) {
            return response('',403);
        }
    }

    private function isAdmin()
    {
        $user = $this->personate->getOriginalUser();

        $emails = array_map("strtolower", config('impersonate.authorized_emails', []));

        if($user == null) {
            \Log::debug("User is null??");
            return false;
        }

        \Log::debug($user->email . " - " . json_encode($emails));

        return in_array(strtolower($user->email), $emails);
    }

    /**
     * @return array|mixed
     */
    public function getUsers(Request $request)
    {
        if(!$this->isAdmin()) {
            return response('',403);
        }

        try {
            return $this->personate->getUsers($request);
        } catch (Exception $exception) {
            \Log::debug($exception->getMessage());
            return [];
        }
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function trySignin(Request $request) : RedirectResponse
    {
        if(!$this->isAdmin()) {
            return response('',403);
        }

        try {
            $this->personate->signin($request->userId, $request->originalId);
        } catch (Exception $exception) {
        }

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function trySignout() : RedirectResponse
    {
        if(!$this->isAdmin()) {
            return response('',403);
        }

        try {
            $this->personate->signout();
        } catch (Exception $exception) {
        }

        return redirect()->back();
    }
}
