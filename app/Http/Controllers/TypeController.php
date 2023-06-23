<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $types = Type::all();
            if (count($types) > 0) {
                return response([
                    'status' => true,
                    'message' => 'Retrieve All Success',
                    'data' => $types,
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Empty',
                ], 404);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve All Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Errror $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve All Failed',
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
            'nama_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $type = Type::create([
                'nama_type' => $request->nama_type,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Type Created',
                'data' => $type,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Save',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Save',
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
            $type = Type::find($id);
            if (!empty($type)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Retrieve Type Success',
                    'data' => $type,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Type Not Found',
                    'data' => null,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Retrieve Type',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to Retrieve Type',
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
            $type = Type::find($id);
            $validator = Validator::make($request->all(), [
                'nama_type' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $type->update([
                'nama_type' => $request->nama_type,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Type Updated',
                'data' => $type,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Update',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Update',
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
            $type = Type::find($id);
            $type->delete();
            return response()->json([
                'status' => true,
                'message' => 'Type Deleted',
                'data' => $type,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Delete',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'Type Failed to Delete',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}