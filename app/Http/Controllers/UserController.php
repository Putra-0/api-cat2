<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::where('role_id', 2)->with('role')->get();
            if ($users->count() > 0) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data users berhasil diambil',
                    'data' => $users,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data users tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data users gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data users gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 2,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil ditambahkan',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal ditambahkan',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal ditambahkan',
                'data' => $e->getMessage(),
            ], 500);
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
        try {
            $user = User::where('role_id', 2)->with('role')->find($id);
            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diambil',
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::where('role_id', 2)->find($id);
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'alamat' => 'required|string',
                    'no_telp' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                    ], 400);
                }

                $user->name = $request->name;
                $user->email = $request->email;
                $user->alamat = $request->alamat;
                $user->no_telp = $request->no_telp;
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diupdate',
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
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
            $user = User::where('role_id', 2)->find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil dihapus',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal dihapus',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal dihapus',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Profile
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diambil',
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diambil',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'alamat' => 'required|string',
                    'no_telp' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                    ], 400);
                }
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'alamat' => $request->alamat,
                    'no_telp' => $request->no_telp,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diupdate',
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data user gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update Password
     */

    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|string|min:8|confirmed',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                    ], 400);
                }

                $user->update([
                    'password' => bcrypt($request->password),
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Password berhasil diupdate',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data user tidak ditemukan',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Password gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Password gagal diupdate',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

}
