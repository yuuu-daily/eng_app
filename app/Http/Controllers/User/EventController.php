<?php

namespace App\Http\Controllers\User;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use App\Services\UtilService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (Gate::allows('system-admin')) {
        //     return redirect()->route('admin.award.index');
        // }
//        UtilService::isAdmin();
        $auth = Auth::user();
        $users = User::all();
        $events = Event::where('user_id', $auth->id)->get()->toArray();
        return Inertia::render('User/Event/Index', [
            'users' => $users,
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Event/Create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $auth = Auth::user();
        $commonRules = [
            'title' => ['required', 'string'],
            'description' => ['nullable'],
            'color_id' => ['nullable'],
            'start_at' => ['required'],
            'end_at' => ['required'],
        ];

        $validator = Validator::make($request->all(), $commonRules);

        // カスタムバリデーション追加
        $validator->after(function ($validator) use ($request) {
            try {
                $start = Carbon::parse($request->input('start_at'));
                $end = Carbon::parse($request->input('end_at'));

                if ($start->gt($end)) {
                    $validator->errors()->add('end_at', '日付の指定が正しくありません。終了日時は開始日時より後にしてください。');
                }
            } catch (\Exception $e) {
                // 日付パースエラー等も catch しておくと安全
                $validator->errors()->add('end_at', '日付の形式が不正です。');
            }
        });

        // バリデーション実行
        $validated = $validator->validate();

        $validated['user_id'] = $auth->id;
        $validated['start_at'] = Carbon::parse($validated['start_at'])
            ->setTimezone('Asia/Tokyo') //VueDatePickerがUTCで送ってきやがる
            ->format('Y-m-d H:i:s');
        $validated['end_at'] = Carbon::parse($validated['end_at'])
            ->setTimezone('Asia/Tokyo') //VueDatePickerがUTCで送ってきやがる
            ->format('Y-m-d H:i:s');
        try {
//            if ($request->input('slide_changed')) {
//                // S3のパス取得してアップデートさせるために(新規追加時はidを取得できないため、url_slideに一瞬、-1を入れて対応)
//                $validated['url_slide'] = $request->input('lesson_id');
//            }
//            for ($i = 1; $i <= 4; $i++) {
//                $test_changed = 'test_changed_' . $i;
//                $test_url = 'url_check_test_' . $i;
//                if ($request->input($test_changed)) {
//                    // S3のパス取得してアップデートさせるために(新規追加時はidを取得できないため、url_check_testに一瞬、-1を入れて対応)
//                    $validated[$test_url] = $request->input('lesson_id');
//                }
//            }
//            for ($i = 1; $i <= 6; $i++) {
//                $material_url_field = 'material_url_' . $i;
//                if (!$validated[$material_url_field]) continue;
//                $import_material_url = LessonMaterialService::MaterialsImporter($request, $material_url_field);
//                if ($import_material_url['status'] !== 200) {
//                    return back()->withErrors("アップロードに失敗しました。（{$material_url_field}）");
//                }
//                $validated[$material_url_field] = $import_material_url['data'];
//            }
            Event::create($validated);
        } catch (\Exception $e) {
            Log::error("lesson createLesson:" . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $auth = Auth::user();
        $event = Event::find($id);
        return Inertia::render('User/Event/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auth = Auth::user();
        $commonRules = [
            'title' => ['required', 'string'],
            'description' => ['nullable'],
            'color_id' => ['nullable'],
            'start_at' => ['required'],
            'start_at_changed' => ['nullable'],
            'end_at_changed' => ['nullable'],
            'end_at' => ['required'],
        ];
        $validator = Validator::make($request->all(), $commonRules);

        // カスタムバリデーション追加
        $validator->after(function ($validator) use ($request) {
            try {
                $start = Carbon::parse($request->input('start_at'));
                $end = Carbon::parse($request->input('end_at'));

                if ($start->gt($end)) {
                    $validator->errors()->add('end_at', '日付の指定が正しくありません。終了日時は開始日時より後にしてください。');
                }
            } catch (\Exception $e) {
                // 日付パースエラー等も catch しておくと安全
                $validator->errors()->add('end_at', '日付の形式が不正です。');
            }
        });

        // バリデーション実行
        $validated = $validator->validate();

        $validated['user_id'] = $auth->id;

        if ($validated['start_at_changed']) {
            $validated['start_at'] = Carbon::parse($validated['start_at'])
                ->setTimezone('Asia/Tokyo') //VueDatePickerがUTCで送ってきやがる
                ->format('Y-m-d H:i:s');
        }

        if ($validated['end_at_changed']) {
            $validated['end_at'] = Carbon::parse($validated['end_at'])
                ->setTimezone('Asia/Tokyo') //VueDatePickerがUTCで送ってきやがる
                ->format('Y-m-d H:i:s');
        }

        try {
            $event = Event::find($id);
            $event->update($validated);
        } catch (\Exception $e) {
            Log::error("event update:" . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
