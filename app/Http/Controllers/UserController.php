<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\OfficeRepository;
use App\Repositories\Contracts\RoleUserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\EditUserRequest;
use App\Eloquent\User;
use Session;

class UserController extends Controller
{
    protected $repository;

    protected $roleRepository;

    protected $officeRepository;

    protected $roleUserRepository;

    public function __construct(
        UserRepository $repository,
        RoleRepository $roleRepository,
        RoleUserRepository $roleUserRepository,
        OfficeRepository $officeRepository
    ) {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
        $this->officeRepository = $officeRepository;
        $this->roleUserRepository = $roleUserRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $with = [
            'roles',
            'office',
        ];
        $users = $this->repository->getData($with);

        return view('admin.user.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->roleRepository->getData();
        $offices = $this->officeRepository->getData()->pluck('name', 'id');

        return view('admin.user.add', compact('roles', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $fileName = md5(date('Y-m-d H:i:s') . $img->getClientOriginalName())
                . '.' . $img->getClientOriginalExtension();
                $img->move(config('view.image_paths.user'), $fileName);
                $request->merge(['avatar' => $fileName]);
            }

            $id = $this->repository->store($request->all())->id;
            if ($id) {
                $this->roleUserRepository->store($request->roles, $id);
            }
            Session::flash('success', trans('settings.success.store'));

            return redirect()->route('users.index');
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('admin.error.error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $with = [
            'roles',
            'office',
        ];
        $user = $this->repository->find($id, $with);
        $idRoles = $user->roles->pluck('id')->toArray();
        $roles = $this->roleRepository->getData();
        $offices = $this->officeRepository->getData()->pluck('name', 'id');

        return view('admin.user.edit', compact('user', 'roles', 'offices', 'idRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, $id)
    {
        try {
            $this->repository->update($id, $request->all());

            if ($request->changed) {
                $this->updateRole($request->role, $id);
            }
            Session::flash('success', trans('settings.success.store'));
            
            return redirect()->route('users.index');
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));
            
            return back();
        }
    }

    public function updateRole($roles, $userId)
    {
        try {
            $this->roleUserRepository->destroy($userId);
            $this->roleUserRepository->store($roles, $userId);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->destroy($id);
            $this->roleUserRepository->destroy($id);
            Session::flash('success', trans('settings.success.delete'));

            return redirect()->route('users.index');
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return redirect()->route('users.index');
        }
    }
}
