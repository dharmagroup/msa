<?php

namespace App\Http\Controllers\Modules\Manufacture;

use App\Http\Controllers\Controller;
use App\Library\Authorization;
use App\Library\Event;
use App\Models\AndonLine;
use App\Models\AndonLog;
use App\Models\AndonStation;
use App\Models\AndonTimer;
use App\Models\AndonType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Andons extends Controller
{
    public function _get_layout_data(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);

        if (!$auth->isLoggedIn) {
            return response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }

        // Ambil semua AndonType dengan Eager Loading untuk AndonLines dan aktifkan logs dan timer
        $andonTypes = AndonType::with([
            'andonLines.logs' => function ($query) {
                $query->where(['status' => 'active'])->with('timer');
            }
        ])->get();

        foreach ($andonTypes as $andonType) {
            foreach ($andonType->andonLines as $andonLine) {
                $andonLine->logs = $andonLine->logs->filter(function ($log) use ($andonType) {
                    return $log->andon_type == $andonType->id; // Memastikan log sesuai dengan andon_type
                });
            }
        }

        $andon = $andonTypes->map(function ($type) {
            $data = $type->andonLines->map(function ($line) {
                $log = $line->logs->first();

                if ($log) {
                    // Ambil data dari log dan timer
                    $line->status = true;
                    $line->andon_log_id = $log->id;
                    $line->andon_station = $log->andon_station;
                    $line->reason = $log->reason;
                    $line->repairTime = $log->repairTime;
                    $line->created_at = Carbon::parse($log->created_at, 'Asia/Jakarta')->format('d-m-Y H:i:s');

                    $line->checked = isset($log->timer) && $log->timer->start && !$log->timer->end;
                    $line->start = $log->timer->start ?? null;
                    $line->end = $log->timer->end ?? null;
                } else {
                    // Default values jika tidak ada log
                    $line->status = false;
                    $line->andon_log_id = null;
                    $line->andon_station = '';
                    $line->reason = '';
                    $line->repairTime = '';
                    $line->created_at = '';
                    $line->checked = false;
                    $line->start = null;
                    $line->end = null;
                }

                return $line;
            });

            return [
                'item' => $type->andon_name,
                'data' => $data,
            ];
        });

        // Ambil issue dengan join dan select yang diperlukan
        $issues = AndonLog::where('status', 'active')
            ->join('andon_types', 'andon_types.id', '=', 'andon_logs.andon_type')
            ->leftJoin('andon_timers', 'andon_timers.andon_log_id', '=', 'andon_logs.id')
            ->select('andon_types.andon_name', 'andon_logs.andon_line', 'andon_logs.andon_station', 'andon_logs.reason', 'andon_logs.id', 'andon_timers.start', 'andon_timers.end', 'andon_logs.created_at')
            ->get();

        // Ambil semua stasiun
        $stations = AndonStation::all();

        return response()->json(['andon' => $andon, 'station' => $stations, 'issue' => $issues], 200, [], JSON_PRETTY_PRINT);
    }

    public function _get_layout_data_trigger(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);

        if (!$auth->isLoggedIn) {
            return response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }

        // Ambil semua AndonType dengan Eager Loading untuk AndonLines dan aktifkan logs dan timer
        $andonTypes = AndonType::with([
            'andonLines.logs' => function ($query) {
                $query->where(['status' => 'active'])->with('timer');
            }
        ])->get();

        foreach ($andonTypes as $andonType) {
            foreach ($andonType->andonLines as $andonLine) {
                $andonLine->logs = $andonLine->logs->filter(function ($log) use ($andonType) {
                    return $log->andon_type == $andonType->id; // Memastikan log sesuai dengan andon_type
                });
            }
        }

        $andon = $andonTypes->map(function ($type) {
            $data = $type->andonLines->map(function ($line) {
                $log = $line->logs->first();

                if ($log) {
                    // Ambil data dari log dan timer
                    $line->status = true;
                    $line->andon_log_id = $log->id;
                    $line->andon_station = $log->andon_station;
                    $line->reason = $log->reason;
                    $line->repairTime = $log->repairTime;
                    $line->created_at = Carbon::parse($log->created_at, 'Asia/Jakarta')->format('d-m-Y H:i:s');

                    $line->checked = isset($log->timer) && $log->timer->start && !$log->timer->end;
                    $line->start = $log->timer->start ?? null;
                    $line->end = $log->timer->end ?? null;
                } else {
                    // Default values jika tidak ada log
                    $line->status = false;
                    $line->andon_log_id = null;
                    $line->andon_station = '';
                    $line->reason = '';
                    $line->repairTime = '';
                    $line->created_at = '';
                    $line->checked = false;
                    $line->start = null;
                    $line->end = null;
                }

                return $line;
            });

            return [
                'item' => $type->andon_name,
                'data' => $data,
            ];
        });

        // Ambil semua stasiun
        $stations = AndonStation::all();

        return response()->json(['andon' => $andon, 'station' => $stations], 200, [], JSON_PRETTY_PRINT);
    }


    public function _create_alarm(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);

        if (!$auth->isLoggedIn) {
            return response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }
        $event = new Event();
        $log = $request->all();
        $log['status'] = $request->input('status') ? 'active' : 'inactive';

        $type = $request->input('andon_type');
        $line = $request->input('andon_line');

        if ($log['status'] === 'active') {
            $exists = AndonLog::where(['andon_type' => $type, 'andon_line' => $line, 'status' => 'active'])->exists();

            if (!$exists) {
                $return = AndonLog::create($log);
                $issue = AndonLog::with('andonType')->where('id', $return->id)->first();

                if ($issue) {
                    $audio = AndonLine::where(['andon_line_name' => $issue->andon_line, 'andon_type_id' => $issue->andon_type])->first();
                    $andon_name = AndonType::where(['id' => $issue->andon_type])->first();
                    $team = $andon_name->andon_name === 'mesin' ? 'Maintenance' : $andon_name->andon_name;

                    $event->send([
                        'm' => $andon_name->andon_name,
                        'l' => $issue->andon_line,
                        's' => $issue->andon_station,
                        't' => $team,
                        'a' => $audio->andon_audio_path ?? null,
                    ], 'andonvoice');
                }
            } else {
                $return = null;
            }
        } else {
            $logEntry = AndonLog::where(['andon_type' => $type, 'andon_line' => $line, 'status' => 'active'])->first();

            if ($logEntry) {
                AndonTimer::where('andon_log_id', $logEntry->id)->update(['end' => Carbon::now('Asia/Jakarta')]);
                $return = $logEntry->update(['status' => 'inactive']);
            } else {
                $return = null;
            }
        }

        if ($return) {
            $event->send([], 'andonalarm');
            return response()->json(['message' => config('messages.andon.created_success')], 200, [], JSON_PRETTY_PRINT);
        }

        return response()->json(['message' => config('messages.andon.create_failed')], 401, [], JSON_PRETTY_PRINT);
    }


    public function _create_making_repair(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if (!$auth->isLoggedIn) {
            return Response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }
        $event = new Event();
        $startmaking = $request->input('startmaking');
        $andon_log_id = $request->input('andon_log_id');
        if ($startmaking) {
            $model = AndonTimer::where(['andon_log_id' => $andon_log_id]);
            if (!$model->count()) {
                $andonTimer = AndonTimer::create([
                    'andon_log_id' => $andon_log_id,
                    'start' => Carbon::now('Asia/Jakarta')->subMinutes(2),
                ]);

            } else {
                $model->delete();
                $andonTimer = AndonTimer::create([
                    'andon_log_id' => $andon_log_id,
                    'start' => Carbon::now('Asia/Jakarta')->subMinutes(2),
                ]);

            }
        } else {
            $model = AndonTimer::where(['andon_log_id' => $andon_log_id]);
            if ($model->count() > 0) {
                $andonTimer = $model->update(['end' => Carbon::now('Asia/Jakarta')->subMinutes(2)]);

            }
        }
        $model = AndonTimer::where(['andon_log_id' => $andon_log_id]);
        if ($model->count() > 0) {
            $model = $model->first();
            $event->send([
                'id' => $model->andon_log_id,
                's' => $model->start ? carbon::parse($model->start, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
                'e' => $model->end ? carbon::parse($model->end, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null,
            ], 'andonstartrepair');
            return Response()->json(['message' => 'OK'], 200, [], JSON_PRETTY_PRINT);
        }
        return Response()->json(['message' => 'NOT OK'], 401, [], JSON_PRETTY_PRINT);
    }

    public function _create_making_repair_automatic(Request $request)
    {
        $event = new Event();
        $andonLog = AndonLog::where(['status' => 'active'])->get();

        foreach ($andonLog as $item) {
            // Mendapatkan waktu sekarang dan waktu created_at
            $now = Carbon::now('Asia/Jakarta');
            $createdAt = Carbon::parse($item->created_at, 'Asia/Jakarta');

            // Mengecek apakah sudah lebih dari 2 menit sejak created_at
            if ($now->diffInMinutes($createdAt) >= 2) {
                $model = AndonTimer::where(['andon_log_id' => $item->id]);

                if (!$model->exists()) {
                    // Membuat timer baru
                    $andonTimer = AndonTimer::create([
                        'andon_log_id' => $item->id,
                        'start' => $now,
                    ]);
                } else {
                    // Menghapus timer yang ada dan membuat yang baru
                    $model->delete();
                    $andonTimer = AndonTimer::create([
                        'andon_log_id' => $item->id,
                        'start' => $now,
                    ]);
                }

                // Mengambil timer terbaru
                $model = AndonTimer::where(['andon_log_id' => $item->id])->first();
                if ($model) {
                    $event->send([
                        'id' => $model->andon_log_id,
                        's' => $model->start ? $model->start->format('Y-m-d H:i:s') : null,
                        'e' => $model->end ? $model->end->format('Y-m-d H:i:s') : null,
                    ], 'andonstartrepair');
                    return $model;
                }
            }
        }
        $event->send([
            'id' => 1,
            's' => 'test',
            'e' => 'test',
        ], 'andonstartrepair');
        return "ok"; // Jika tidak ada eksekusi
    }


    public function _get_reports_(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if (!$auth->isLoggedIn) {
            return response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search'); // Ambil input pencarian

        $query = AndonLog::select(
            'andon_types.andon_name',
            'andon_logs.andon_line',
            'andon_logs.andon_station',
            'andon_logs.reason',
            'andon_logs.created_at',
            'andon_timers.start',
            'andon_timers.end',
        )
            ->join('andon_types', 'andon_types.id', '=', 'andon_logs.andon_type')
            ->leftJoin('andon_timers', 'andon_timers.andon_log_id', '=', 'andon_logs.id');

        // Tambahkan kondisi untuk tanggal
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate, 'Asia/Jakarta')->format('Y-m-d');
            $endDate = Carbon::parse($endDate, 'Asia/Jakarta')->format('Y-m-d');
            $query->whereBetween('andon_logs.created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        // Tambahkan kondisi pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('andon_logs.reason', 'like', '%' . $search . '%')
                    ->orWhere('andon_logs.andon_line', 'like', '%' . $search . '%')
                    ->orWhere('andon_types.andon_name', 'like', '%' . $search . '%')
                    ->orWhere('andon_logs.andon_station', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate(10);

        return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
    }


    public function _get_reports_excel_(Request $request)
    {
        $token = $request->header('token');
        $authorization = new Authorization();
        $auth = $authorization->fromToken($token);
        if (!$auth->isLoggedIn) {
            return response()->json(['message' => config('messages.signin.token_error')], 401, [], JSON_PRETTY_PRINT);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search'); // Ambil input pencarian

        $query = AndonLog::select(
            \DB::raw('andon_logs.reason as "PROBLEM SPECIFIC"'),
            \DB::raw('andon_types.andon_name as "TROUBLE AREA"'),
            \DB::raw('andon_logs.andon_line as "LINE AREA"'),
            \DB::raw('andon_logs.andon_station "STATION"'),
            \DB::raw('andon_timers.start as "START REPAIR"'),
            \DB::raw('andon_timers.end as "END REPAIR"'),
            \DB::raw('andon_logs.created_at as "CREATED AT"'),

        )
            ->join('andon_types', 'andon_types.id', '=', 'andon_logs.andon_type')
            ->leftJoin('andon_timers', 'andon_timers.andon_log_id', '=', 'andon_logs.id');

        // Tambahkan kondisi untuk tanggal
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate, 'Asia/Jakarta')->format('Y-m-d');
            $endDate = Carbon::parse($endDate, 'Asia/Jakarta')->format('Y-m-d');
            $query->whereBetween('andon_logs.created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }

        // Tambahkan kondisi pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('andon_logs.reason', 'like', '%' . $search . '%')
                    ->orWhere('andon_logs.andon_line', 'like', '%' . $search . '%')
                    ->orWhere('andon_types.andon_name', 'like', '%' . $search . '%')
                    ->orWhere('andon_logs.andon_station', 'like', '%' . $search . '%');
            });
        }

        $data = $query->get();

        return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
    }

}
