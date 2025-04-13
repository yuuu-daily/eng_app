<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AwsService;
use Illuminate\Http\Request;

class UtilController extends Controller
{

    /*
     * 引数は3つ。filename, dir, mime_type
     */
    public function getPreSignedUrl(Request $request): string
    {
        $test = "a";
        $filename = $request->get('filename');
        $dir = $request->get('dir'); //先頭のスラッシュは無し、最後はスラッシュで終わっていること！
        $mime_type = $request->get('type');
        
        list($pre_signed_url, $key) = AwsService::getS3PreSignedUrl($filename, $dir, $mime_type);
        $ret = ["preSignedUrl" => $pre_signed_url, "s3key" => env('AWS_CLOUDFRONT_URL') . $key];
        return json_encode($ret);
    }
}
