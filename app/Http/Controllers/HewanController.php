<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\HewansResource;
use App\Models\Hewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HewanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $hewans = Hewan::all();
            if (count($hewans) > 0) {
                return response([
                    'status' => true,
                    'message' => 'Retrieve All Success',
                    'data' => HewansResource::collection($hewans),
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
            'nama_hewan' => 'required',
            'description' => 'required',
            'jenis_kelamin' => 'required',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'umur' => 'required',
            'berat' => 'required',
            'harga' => 'required',
            'status_vaksin' => 'required',
            'type_id' => 'required|exists:types,id',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            $hewan = Hewan::create([
                'nama_hewan' => $request->nama_hewan,
                'description' => $request->description,
                'jenis_kelamin' => $request->jenis_kelamin,
                'umur' => $request->umur,
                'berat' => $request->berat,
                'harga' => $request->harga,
                'status_vaksin' => $request->status_vaksin,
                'status' => 'Tersedia',
                'type_id' => $request->type_id,
            ]);

            if ($images = $request->images) {
                foreach ($images as $image) {
                    $hewan->addMedia($image)->toMediaCollection('images', 'images');
                }
            }

            return response([
                'status' => true,
                'message' => 'Hewan Created',
                'data' => new HewansResource($hewan),
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Created Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Created Failed',
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
            $hewan = Hewan::find($id);
            if (!empty($hewan)) {
                return response([
                    'status' => true,
                    'message' => 'Retrieve Hewan Success',
                    'data' => new HewansResource($hewan),
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Hewan Not Found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve Hewan Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response([
                'status' => false,
                'message' => 'Retrieve Hewan Failed',
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
            $hewan = Hewan::find($id);
            $validator = Validator::make($request->all(), [
                'nama_hewan' => 'required',
                'description' => 'required',
                'jenis_kelamin' => 'required',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'umur' => 'required',
                'berat' => 'required',
                'harga' => 'required',
                'status_vaksin' => 'required',
                'status' => 'nullable',
                'type_id' => 'required|exists:types,id',
            ]);
            if ($validator->fails()) {
                return response([
                    'status' => false,
                    'message' => 'Validation Error',
                    'data' => $validator->errors(),
                ], 400);
            }

            $hewan->update([
                'user_id' => $request->user_id,
                'nama_hewan' => $request->nama_hewan,
                'description' => $request->description,
                'jenis_kelamin' => $request->jenis_kelamin,
                'umur' => $request->umur,
                'berat' => $request->berat,
                'harga' => $request->harga,
                'status_vaksin' => $request->status_vaksin,
                'status' => $request->status,
                'type_id' => $request->type_id,
            ]);

            if ($images = $request->images) {
                $hewan->clearMediaCollection('images');
                foreach ($images as $image) {
                    $hewan->addMedia($image)->toMediaCollection('images', 'images');
                }
            }

            return response([
                'status' => true,
                'message' => 'Hewan Updated',
                'data' => new HewansResource($hewan),
            ], 200);

        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Updated Failed',
                'data' => $e->getMessage(),
            ], 500);
        } catch (\Error $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Updated Failed',
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
            $hewan = Hewan::find($id);
            if (!empty($hewan)) {
                $hewan->delete();
                return response([
                    'status' => true,
                    'message' => 'Hewan Deleted',
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Hewan Not Found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Deleted Failed',
            ], 500);
        } catch (\Error $e) {
            return response([
                'status' => false,
                'message' => 'Hewan Deleted Failed',
            ], 500);
        }
    }

    /**
     * Search by all column with keyword.
     */

    public function search($keyword)
    {
        try {
            $hewan = Hewan::where('nama_hewan', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%')
                ->orWhere('jenis_kelamin', 'like', '%' . $keyword . '%')
                ->orWhere('umur', 'like', '%' . $keyword . '%')
                ->orWhere('berat', 'like', '%' . $keyword . '%')
                ->orWhere('harga', 'like', '%' . $keyword . '%')
                ->orWhere('status_vaksin', 'like', '%' . $keyword . '%')
                ->orWhere('status', 'like', '%' . $keyword . '%')
                ->orWhere('type_id', 'like', '%' . $keyword . '%')
                ->get();
            if (count($hewan) > 0) {
                return response([
                    'status' => true,
                    'message' => 'Search Hewan Success',
                    'data' => HewansResource::collection($hewan),
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Hewan Not Found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Search Hewan Failed',
            ], 500);
        } catch (\Error $e) {
            return response([
                'status' => false,
                'message' => 'Search Hewan Failed',
            ], 500);
        }
    }
}