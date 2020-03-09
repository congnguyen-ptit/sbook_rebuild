<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfficeRequest;
use App\Repositories\Contracts\OfficeRepository;
use App\Eloquent\Office;
use Yajra\Datatables\DataTables;

class OfficeController extends Controller
{
    protected $office;

    public function __construct(OfficeRepository $office)
    {
        $this->office = $office;
    }

    public function index()
    {
        $offices = $this->office->getData();

        return view('admin.offices.list', compact('offices'));
    }

    public function ajaxIndex()
    {
        try {
            $offices = $this->office->getData([], [], ['id', 'name', 'address', 'description']);

            return DataTables::of($offices)->make(true);
        } catch (Exception $e) {
            return view('admin.error.error');
        }
    }

    public function show($id)
    {
        try {
            $office = $this->office->findById($id);

            return response()->json($office);
        } catch (Exception $exception) {
            return response()->json(false);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offices.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfficeRequest $request)
    {
        try {
            $this->office->store($request->all());

            return redirect()->route('offices.index')->with('success', __('admin.success'));
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('admin.error.error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        try {
            return view('admin.offices.edit', compact('office'));
        } catch (Exception $e) {
            return view('admin.error.error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OfficeRequest $request, Office $office)
    {
        try {
            $office->update($request->all());
            \Cache::put('offices', $this->office->getData(), 1440);

            return redirect()->route('offices.index')->with('success', __('admin.success'));
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('admin.error.error');
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
        //
    }
}
