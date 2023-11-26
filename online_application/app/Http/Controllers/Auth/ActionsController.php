<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Response;
use Str;

class ActionsController extends Controller
{
    protected $redirectTo = 'home';

    public function destroy(User $user, Request $request)
    {
        try {
            $user->delete();

            if ($request->ajax()) {
                return Response::json([
                      'status'    => 200,
                      'response'  => 'success',
                  ]);
            }
        } catch (\Exception $exception) {
            throw $exception;
        }

        return redirect(route($this->redirectTo));
    }

    public function activate(User $user, Request $request)
    {
        try {
            $user->is_active = true;
            $user->activation_token= null;
            $user->save();

            if ($request->ajax()) {
                return Response::json([
                      'status'    => 200,
                      'response'  => 'success',
                  ]);
            }
        } catch (\Exception $exception) {
            throw $exception;
        }

        return redirect(route($this->redirectTo));
    }

    public function deactivate(User $user, Request $request)
    {
        try {
            $user->is_active = false;
            $user->activation_token = Str::random(177);
            $user->save();

            if ($request->ajax()) {
                return Response::json([
                      'status'    => 200,
                      'response'  => 'success',
                  ]);
            }
        } catch (\Exception $exception) {
            throw $exception;
        }

        return redirect(route($this->redirectTo));
    }
}
