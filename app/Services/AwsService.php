<?php

namespace App\Services;

use Aws\Exception\AwsException;
use Aws\CloudFront\UrlSigner;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;


class AwsService extends BaseService
{

    public static function generateS3SignedUrl($s3key)
    {
        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        try {
            $bucket = env('AWS_BUCKET');
            // $s3key = str_replace(env('AWS_CLOUDFRONT_URL'), '', $s3key);
            $cmd = $s3Client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $s3key,
            ]);
            $request = $s3Client->createPresignedRequest($cmd, '+60 minutes'); //60分！
            return (string)$request->getUri(); // サイン付きURLを返す
        } catch (AwsException $e) {
            Log::error("generateS3SignedUrl: " . $e->getMessage() . ", s3key: " . $s3key);
            return null; // 失敗時はnullを返す
        }
    }


    public static function getCloudfrontSign($url_path, $expireSec)
    {
        $url = env('ASW_CLOUDFRONT_URL') . $url_path;
        $keyPairId = env('AWS_CLOUDFRONT_SIGN_KEY_ID');
        $privateKeyPath = base_path() . env('AWS_CLOUDFRONT_SIGN_PRIVATE_KEY');
        $expires = time() + $expireSec;
        try {
            $signer = new UrlSigner($keyPairId, $privateKeyPath);
            return $signer->getSignedUrl($url, $expires);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return null;
    }

    /*
     * こんな感じで使う
     * AwsService::putS3('/var/www/html/public/images/logo.png', 'test/aa/', 'image/png');
     * Content-Type一覧
     * https://qiita.com/AkihiroTakamura/items/b93fbe511465f52bffaa
     */
    public static function putS3($src_full_path, $dst_dir, $content_type = 'application/octet-stream')
    {
        if (!file_exists($src_full_path)) {
            Log::error('file not found:' . $src_full_path);
            return null;
        }
        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        try {
            $key = env('AWS_S3_DIR_ENV', 'd') . "/" . $dst_dir . basename($src_full_path);

            $result = $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $key,
                'SourceFile' => $src_full_path,
                'ContentType' => $content_type,
                'ACL' => 'private', // CloudFront でアクセスできるように公開設定
            ]);

            // **CloudFront の URL を返す**
            return env('AWS_CLOUDFRONT_URL') . $key;

        } catch (AwsException $e) {
            Log::error("putS3: " . $e->getMessage() . ", " . $src_full_path . " -> " . $dst_dir);
            return null;
        }
    }

    public static function getS3PreSignedUrl($filename, $dst_dir, $content_type, $bucket = null)
    {
        if ($bucket === null) {
            $bucket = env('AWS_BUCKET');
        }
        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
        $s3key = env('AWS_S3_DIR_ENV', 'd') . "/" . $dst_dir . basename($filename);
        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $s3key,
            'ContentType' => $content_type,
        ]);
        // $region = 'ap-northeast-1';
        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        // $request = $cmd;
        // $url = "https://{$bucket}.s3.{$region}.amazonaws.com/{$s3key}";
        return [(string)$request->getUri(), $s3key];
        // return [$url, $s3key];
    }

    public static function getSignedUrlFromKey($s3key, $bucket = null, $expiresInMinutes = 60)
    {
        if ($bucket === null) {
            $bucket = env('AWS_BUCKET_PRIVATE'); // 静的ファイルはPRIVATEバケットで管理されている想定
        }

        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $s3key,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, "+{$expiresInMinutes} minutes");

        return (string)$request->getUri();
    }


    public static function delS3($s3key)
    {
        $s3Client = new S3Client([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        try {
            // S3から対象ファイルを削除
            $result = $s3Client->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $s3key,
            ]);
            return $result; // 成功時のレスポンスを返す
        } catch (AwsException $e) {
            Log::error("delS3:" . $e->getMessage() . ", s3key: " . $s3key);
        }

        return false; // 削除失敗時はfalseを返す
    }

    public static function delOldImage($old_image_url, $new_image_url)
    {
        if ($new_image_url !== '' && $new_image_url !== $old_image_url) { //画像を更新したので、前の画像をs3から消す
            $s3key = str_replace(env('AWS_CLOUDFRONT_URL'), '', $old_image_url);
            self::delS3($s3key);
        }
    }

}