<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Cyvelnet\Laravel5Fractal\Facades\Fractal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    /**
     * set response success
     * @param  array  $data            
     * @param  string $response_message
     * @param  $statusCode      
     * @return json response                 
     */
	public function respondSuccess($data = [], $response_message = 'Success', $statusCode)
    {
        $message = [
            'code' => $statusCode,
            'message' => $response_message,
            'data' => $data,
        ];
        
        return response()->json($message);
    }

    /**
     * set exception error
     * @param  $e exception
     * @return json response
     */
    public function getExceptionErrors($e)
    {
        $message = $e->getMessage();
        $status = ($e->getCode() == 0) ? Response::HTTP_UNPROCESSABLE_ENTITY : $e->getCode();
        $error = [
            'message' => $message,
            'status'    => $e->getCode(),
            'line'    => $e->getLine(),
        ];

        return response()->json($error, $status);
    }

    /**
     * set respond error
     * @param  $message  
     * @param  $errorCode
     * @return json response          
     */
    public function respondError($message, $errorCode=Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        $response = [
            'error' => true,
            'message' => $message,
        ];

        return response()->json($response, $errorCode, []);
    }

    
    /**
     * respond item in json schema format
     * @param  Object $item
     * @param  Transformer $transformer
     * @return ResponseJson
     */
    public function respondItem($item, $transformer, $type, $message = null)
    {
        $data = Fractal::item($item, $transformer, $type)->getArray();

        if($message) {
            $data['message'] = $message;
        }

        return response()->json($data, Response::HTTP_OK, []);
    }

    /**
     * respond collection in json schema format
     * @param  Collection $collection
     * @param  Transformer $transformer
     * @param  String $type      
     * @return Response json
     */
    public function respondCollection($collection, $transformer, $type, $limit = null)
    {
        if(!$limit) {
            $limit = (int) 10;
        }

        $paginator = paginateCollection($collection, $limit);
        $data = Fractal::collection($paginator, $transformer, $type)->getArray();
        return response()->json($data, Response::HTTP_OK, []);
    }
    
    /**
     * set token   
     * @param  request
     * @return json response
     */
    public function getToken($request)
    {
        $header = $request->headers->all();
        return $header['authorization']['0'];
    }

    /**
     * set current user id
     * @return integer id
     */
    public function getUserId()
    {
        $userId = \Auth::user()->id;
        return $userId;
    }

    public function getUserStatus()
    {
        return '1';
    }
}
