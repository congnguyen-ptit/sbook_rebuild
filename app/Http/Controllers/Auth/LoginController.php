<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserLoginRequest;
use App\Repositories\Contracts\OfficeRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Fauth;
use Illuminate\Http\Request;
use App\Eloquent\Office;
use App\Eloquent\User;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $office;

    /**
     * Create a new controller instance.
     *
     * @param OfficeRepository $office
     */
    public function __construct(OfficeRepository $office)
    {
        $this->middleware('guest')->except('logout');
        $this->office = $office;
    }

    public function redirectToProvider()
    {
        return Fauth::driver('framgia')->redirect();
    }

    /**
     * Obtain the user information from Auth-Framgia.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Fauth::driver('framgia')->user();
        $token = $user->token;
        $userSocial = Fauth::driver('framgia')->userFromToken($token);
        if (isset($userSocial->user['workspaces'][0])){
            $workspaceFirst = $userSocial->user['workspaces'][0];
        }else{
            return redirect()->route('home')->with(['unccess' => trans('auth.noWorkspace')]);
        }
        $user = User::where('email', $userSocial->user['email'])->first();
        if ($user) {
            if (Auth::loginUsingId($user->id)) {
                return redirect()->back();
            }
        }
        $office = null;
        // Find workspace if user no have workspace default
        if(empty($userSocial->user['workspace_default'])){
            $office = $this->office->byName($workspaceFirst['name']);
        }else{
            // Get workspace default;
            $workspace_default_id = $userSocial->user['workspace_default_id'];
            foreach($userSocial['workspaces'] as $item) {
                if ($workspace_default_id == $item['id']) {
                    $office =  $this->office->byName($item['name']);
                    break;
                }
            }
        }
        // if not exists office then create new office
        if(is_null($office)){
            $dataWorkspace = [
                'name' => $workspaceFirst['name'],
                'address' => empty($workspaceFirst['address']) ? $workspaceFirst['name'] : $workspaceFirst['address'],
                'description' => $workspaceFirst['description'] ?? 'N/A',
            ];
            $office = $this->office->store($dataWorkspace);
        }
        $userSignUp = User::create([
            'name' => $userSocial->user['name'],
            'email' => $userSocial->user['email'],
            'password' => bcrypt('123456'),
            'employee_code' => $userSocial->user['employee_code'],
            'position' => $userSocial->user['role'],
            'avatar' => $userSocial->user['avatar'],
            'office_id' => $office->id,
        ]);

        if ($userSignUp) {
            if (Auth::loginUsingId($userSignUp->id)) {
                return redirect()->back();
            }
        }
    }

    public function getLoginAdmin()
    {
        return view('admin.admin_login');
    }

    public function postLoginAdmin(AdminUserLoginRequest $request)
    {
        $data = $request->only(['email', 'password']);
        if ($request->email == config('settings.user.admin.email') && auth()->attempt($data)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->withErrors([trans('admin.validation.login_admin_error')]);
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('home');
    }
}
