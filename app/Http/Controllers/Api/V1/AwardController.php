<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Api\V1\Award\GetRequest;
use App\Http\Requests\Api\V1\Award\PostRequest;
use App\Http\Requests\Api\V1\Award\PatchRequest;
use App\Models\Award;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetRequest $request) : JsonResponse
    {
        try {
            $code = 200;

            $type = empty($request->type) ? null : explode("&", $request->type);
    
            $awards = Award::whereNotNull('id');
    
            if ($type) {
                $awards->whereIn('type', $type);
            }
    
            if ($request->point_start) {
                $awards->where('exchanges_point', '>=', $request->point_start);
            }
    
            if ($request->point_end) {
                $awards->where('exchanges_point', '<=', $request->point_end);
            }
    
            $awards = $awards->paginate(10);

            $this->response_body['status'] = True;
            $this->response_body['result'] = $awards;
        } catch (\Throwable $t) {
            $this->response_body['status'] = False;
            $this->response_body['message'] = $t->getMessage();

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request) : JsonResponse
    {
        try {
            $code = 200;

            do {
                $filename = "{$this->generateRandomString()}.{$request->file('image')->extension()}";
            } while (Award::where('image', $filename)->get()->count() > 0);

            $this->upload_files($request->file('image'), $filename);

            $award = Award::create(array_merge($request->all(), [
                'image' => $filename
            ]));

            $this->response_body['status'] = True;
            $this->response_body['message'] = "Award created successfully";
            $this->response_body['result'] = $award;
        } catch (\Throwable $t) {
            $this->response_body['status'] = False;
            $this->response_body['message'] = $t->getMessage();

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    /**
     * Display the specified resource.
     */
    public function show(Award $award)
    {
        try {
            $code = 200;

            $this->response_body['status'] = True;
            $this->response_body['result'] = $award;
        } catch (\Throwable $t) {
            $this->response_body['status'] = False;
            $this->response_body['message'] = $t->getMessage();

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatchRequest $request, Award $award)
    {
        try {
            $code = 200;

            if (!empty($request->file('image'))) {
                $this->upload_files($request->file('image'), $award->image);
            }

            $award->update($request->except('image'));

            $this->response_body['status'] = True;
            $this->response_body['message'] = "Award updated successfully";
            $this->response_body['result'] = $award;
        } catch (\Throwable $t) {
            $this->response_body['status'] = False;
            $this->response_body['message'] = $t->getMessage();

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Award $award)
    {
        try {
            $code = 200;
            $temp = $award;
            $award->delete();

            $this->response_body['status'] = True;
            $this->response_body['message'] = "Award - {$temp->name} Deleted";

        } catch (\Throwable $t) {
            $this->response_body['status'] = False;
            $this->response_body['message'] = $t->getMessage();

            $code = 404;
        }

        return $this->_generate_response($code);
    }

    private function upload_files($file, $filename)
    {
        try {
            Storage::disk('public')->putFileAs(
                "awards",
                $file,
                $filename
            );

            return True;
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
