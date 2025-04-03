<?php

namespace App\Services;

use App\Consts\AwardSurveyConsts;
use App\Models\AwardSeries;
use App\Models\AwardUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Client\Request;

class UtilService extends BaseService {

    /**
     * adminのみ
     */
    static function isAdmin()
    {
        $flg = true;
        if (Gate::allows('system-admin')) {
            //ok
        } else {
            $flg = false;
        }
        if (!$flg) abort(403, '権限がありません');
    }
}
