<?php

namespace App\Http\Controllers;

use App\User;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Yajra\Datatables\Datatables;
use App\Order;
use App\DataTables\UserDataTable;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    public function getUsers(UserDataTable $dataTable)
    {
        return $dataTable->render('users.userslist');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (Auth::user()->id == $user->id) {
            return view('users.user', ['user' => $user]);
        }
        return view('errors.403');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->id == $user->id) {
            return view('users.edit', ['user' => $user]);
        }
        return view('errors.403');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        // Validate Form
        $user = User::where('id', $id)->update([
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'postcode' => $request->input('postcode'),
            'place' => $request->input('place'),
            'social_security_number' => $request->input('social_security_number'),
            'company' => $request->input('company'),
            'company_address' => $request->input('company_address'),
            'function_in_the_company' => $request->input('function_in_the_company'),
            'fix_phone_number' => $request->input('fix_phone_number'),
            'mobile_phone_number' => $request->input('mobile_phone_number'),
            'bank' => $request->input('bank'),
            'account_number' => $request->input('account_number'),
            'iban' => $request->input('iban'),
            'cardholder' => $request->input('cardholder'),
        ]);
        if($user){
            return redirect('/users/' . $id)->with('userUpdate', 'User updated successfully');
        }
        return redirect('/users/' . $id)->with('userUpdateFail', 'User was not updated. Please try again');
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

    public function details($id)
    {
        $user = User::findOrFail($id);
        $confirmed = Order::where('owner', $user->id)->whereNotNull('confirmed')->sum('amount');
        $notConfirmed = Order::where('owner', $user->id)->whereNull('confirmed')->sum('amount');
        return view('users.details', ['user' => $user, 'confirmed' => $confirmed, 'notConfirmed' => $notConfirmed]);
    }
    public function getUserOrders($id)
    {
        return Datatables::of(Order::where('owner',$id ))
            ->addColumn('confirmed_order','@if( $confirmed == null) Not Confirmed @else Confirmed @endif')
            ->editColumn('amount', '{{$amount}} CHF')
            ->addColumn('actions', '<a href="{{ route("editOrder", $id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pen"></i></a>')
            ->setRowAttr(['style' => '{{ $confirmed == null ? "color:red;" : "color:green;" }}', 'id' => ' {{ $id }}'])
            ->rawColumns(['actions'])
            ->toJson();
        // ->make(true);
    }

    public function editUser(User $user)
    {
        return view('users.editUser', ['user' => $user]);
    }
    public function updateUser(UserRequest $request, $id)
    {
        $user = User::where('id', $id)->update([
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'postcode' => $request->input('postcode'),
            'place' => $request->input('place'),
            'social_security_number' => $request->input('social_security_number'),
            'company' => $request->input('company'),
            'company_address' => $request->input('company_address'),
            'function_in_the_company' => $request->input('function_in_the_company'),
            'fix_phone_number' => $request->input('fix_phone_number'),
            'mobile_phone_number' => $request->input('mobile_phone_number'),
            'bank' => $request->input('bank'),
            'account_number' => $request->input('account_number'),
            'iban' => $request->input('iban'),
            'cardholder' => $request->input('cardholder'),
        ]);
        if($user){
            return redirect('admin/users/' . $id)->with('adminUserUpdate', 'User updated successfully');
        }
        return redirect('admin/users/' . $id)->with('adminUserUpdateFail', 'User was not updated. Please try again');
    }
}
