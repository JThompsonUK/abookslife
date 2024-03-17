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
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request as HttpRequest;

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
        $user = Auth::user();
        return Inertia::render('Users/Edit', compact('user'));



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
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {

        // check if email exists

        Request::validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            // 'password' => 'required',
        ]);

       $user->update([
           'name' => Request::get('name'),
           'email' => Request::get('email'),
       ]);

        return Redirect::back()->with('success', 'user updated.');
    }


    /**
     * @throws \Exception
     */
    public function store()
    {
        // In case of failure (e.g., non-unique email)
        try {
            Request::validate([
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('users')],
                'password' => 'required',
            ]);

            $user = User::create([
                'name' => Request::get('name'),
                'email' => Request::get('email'),
                'password' => Request::get('password'),
            ]);

            return Redirect::route('login')->with('success', 'User created. Now login to confirm details are correct');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::route('register')
                ->withErrors($e->validator->getMessageBag())
                ->withInput()
                ->with('failure', 'This user already exists. Please check your details.');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    


    public function uploadImage(HttpRequest $request)
{
    
    // $request->validate([
    //     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    // ]);

    $image = $request->file('image');
    $imageName = time().'.'.$image->getClientOriginalExtension();

    // Store the image in the storage disk (public/uploads)
    $image->storeAs('uploads', $imageName, 'public');
    
    // dd($image);
    return redirect()->back();
}
}
