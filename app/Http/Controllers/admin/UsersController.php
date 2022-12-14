<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Flasher\SweetAlert\Prime\SweetAlertFactory;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->division) {
            $users = User::where('division_id', $request->division)->fastPaginate(50);
        } else {
            $users = User::fastPaginate(50);
        }
        $divisions = Division::all();

        $data = [
            'users' => $users,
            'divisions' => $divisions,
            'type_menu' => 'Data Users'
        ];
        // $datas =  ['data' => $data];
        // return response()->json($data);

        return view('admin.users.index', $data);
    }

    public function search(Request $request)
    {
        $output = '';
        if ($request->ajax()) {
            $users = User::where('name', 'LIKE', '%' . $request->search . '%')->orWhere('username', 'LIKE', '%' . $request->search . '%')->get();
            if ($users) {
                foreach ($users as $user) {
                    $output .=
                        '<td>' .  '1' . '</td>
                        <td>' . $user->name . '</td>
                        <td>' . $user->username . '</td>
                        <td>' . @$user->division->name . '</td>
                        <td>' . $user->getRoleNames()[0] . '</td>
                        <td>
                        <button class="btn fas fa-pen-square text-success" onclick="editUser(' . $user->name . ',' . $user->username . ',' . @$user->division->name . ')"></button>
                         <a class="btn  fas fa-key text-primary"></a>
                        <button class="btn fas fa-trash text-danger" onclick="deleteUser(' . $user->name . ',' . $user->username . ',' . @$user->division_id . ',' . route('users.destroy', $user->id) . ')" data-toggle="modal" data-target="#deleteUser"></button>
                        </td>';
                }
                return response()->json($output);
            }
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return response()->json('Create');
        $divisions = Division::all();
        $data = [
            'url' => route('users.store'),
            'divisions' => $divisions
        ];
        return view('admin.users.modal.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request, SweetAlertFactory $flasher)
    {
        // return response()->json($request->name);
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'division_id' => $request->division
        ];
        // return response()->json($data);
        $user = User::create($data);
        if ($user) {
            $asignRole = $user->assignRole('user');
            if ($asignRole) {
                $flasher->addSuccess('Data User has been add successfully!');
                return redirect()->route('users.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'url' => route('users.update', $id),
            'user' => $user,
            'divisions' => Division::all()
        ];


        // return response()->json($user);
        return view('admin.users.modal.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, SweetAlertFactory $flasher)
    {
        // return response()->json($request->name);
        $user = User::findOrFail($id);
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'division_id' => $request->division
        ];
        // return response()->json($data);
        // $data = ([
        //     'name' => 'required|string',
        //     'username' => 'readonly|string',
        //     'division_id' => 'required|string'
        // ]);

        $user->update($data);
        // if ($user) {
        $flasher->addSuccess('Data has been update successfully!');
        // $flasher->iconColor('#ff000');
        return redirect()->route('users.index');
        // }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showDestroy($id)
    {
        // return response()->json(User::findOrFail($id));
        $data = [
            'url' => route('users.destroy', $id)
        ];
        return view('admin.users.modal.delete', $data);
    }


    public function destroy($id, SweetAlertFactory $flasher)
    {
        // return response()->json($id);
        $delete = User::destroy($id);
        if ($delete) {
            $flasher->addSuccess('Data has been Delete successfully!');
            // $flasher->iconColor('#ff0000');
            return redirect()->route('users.index');
        }
    }

    public function showReset($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'user' => $user,
            'url' => route('users.resetpassword', $id)
        ];
        return view('admin.users.modal.reset-password', $data);
    }

    public function resetPassword($id, SweetAlertFactory $flasher)
    {
        // return response()->json(User::findOrFail($id)->password);

        $doneReset = User::whereId($id)->update([
            'password' => Hash::make('password')
        ]);
        if ($doneReset) {
            $flasher->addSuccess('Password has been reset successfully!');
            return redirect()->route('users.index');
        }
    }
}
