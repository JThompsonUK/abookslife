<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\Traits\ConditionallyHasAreaOfInterest;
//use App\Http\Requests\UpdateUser;
//use App\Models\Country;
//use App\Models\HcpRole;
//use Carbon\Carbon;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
//    use ConditionallyHasAreaOfInterest;

    /**
     * Show the form for editing the currently authenticated User's Profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
//        $user = Auth::user();
//        $user->cert_privacy_opt_in = $user->approvedCertDataPrivacy();
//        $countries = Country::select(['id', 'name'])->orderBy('name')->get();
//        $hcpRoles = HcpRole::select(['id', 'name'])->get();
//
//        return view('users.edit', $this->interestData(compact('user', 'countries', 'hcpRoles')));
    }

    /**
     * Update the specified User.
     *
     * @param  \App\Requests\UpdateUser  $request
     * @return \Illuminate\Http\Response
     */
//    public function update(UpdateUser $request)
    public function update(User $user)
    {
        dd($user);
//        $user = Auth::user();
//
//        if (request()->exists('password')) {
//            event(new PasswordReset($user));
//        }
//
//        if ($request->get('cert_privacy')){
//            $user->cert_privacy_opt_in = Carbon::now();
//        } else {
//            $user->cert_privacy_opt_out = Carbon::now();
//        }
//
//        $user->update($request->validated());
//
//        if ($request->wantsJson()) {
//            return $user;
//        }
//
//        return redirect(route('user.edit'))->with('success', trans('profile.updated'));
    }
}
