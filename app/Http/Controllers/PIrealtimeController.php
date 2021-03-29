<?php

namespace App\Http\Controllers;

use App\PIachievement;
use App\PIrejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use Auth;

use Illuminate\Support\Arr;

class PIrealtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     return view('index');
    // }

    public function realtime() {
        $realtime = PIrejection::all();
        $planachiev = $this->getPlanningVsAchievement();
        return view('report.realtime',compact('planachiev'));
    }

    public function getRealtime(Request $request) {

        // $rejectionRealtime = $this->getRejectionRealtime();
        // $rejectionTypeRealtime = $this->getRejectionTypeRealtime();
        // $okratioRealtime = $this->getOKRatioRealtime();
        // $data = array(
        //     // 'data_chart_1' => $rejectionRealtime
        //         'datachart1' => $rejectionRealtime,
        //         'datachart1c' => $rejectionTypeRealtime,
        //         'datachart1b' => $okratioRealtime
        //  );
        // return $data;
    }

    public function getRejectionRealtime() {
        $query = DB::select('select * from (select count("RejectionType") as rejectbulantahun, "NamaPart" from "Pi_Rejection" where
        to_char("InputDate"  + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".')
        group by "NamaPart") a INNER JOIN "PI_Machine" b ON "a"."NamaPart" = "b"."NamaPart1"
        OR "a"."NamaPart" = "b"."NamaPart2" OR "a"."NamaPart" = "b"."NamaPart3"
        OR "a"."NamaPart" = "b"."NamaPart4" where "Area" = '."'A+'".' order by rejectbulantahun DESC');
        $part_name = array_column($query, 'NamaPart');
        $sum = array_column($query, 'rejectbulantahun');
        for ($i=0; $i < count($part_name) ; $i++) {
          $x_data[$i] = $part_name[$i];
        }
        $result = [
          'x_data' => $x_data,
          'y_data' => $sum
        ];
        return $result;
      }

      public function getRejectionTypeRealtime() {
        $query = DB::select('select sum(rejectbulantahun) as total, "RejectionType" from (select count("RejectionType") as rejectbulantahun, "NamaPart","RejectionType" from "Pi_Rejection" where
        to_char("InputDate" + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".')
        group by "NamaPart","RejectionType") a INNER JOIN "PI_Machine" b ON "a"."NamaPart" = "b"."NamaPart1"
        OR "a"."NamaPart" = "b"."NamaPart2" OR "a"."NamaPart" = "b"."NamaPart3"
        OR "a"."NamaPart" = "b"."NamaPart4" where "Area" = '."'A+'".' group by "RejectionType","rejectbulantahun" order by rejectbulantahun DESC');
        $part_name = array_column($query, 'RejectionType');
        $sum = array_column($query, 'total');
        for ($i=0; $i < count($part_name) ; $i++) {
          $x_data[$i] = $part_name[$i];
        }
        $result = [
          'x_data' => $x_data,
          'y_data' => $sum
        ];
        return $result;
      }

      public function getOKRatioRealtime() {
        $query = DB::select('select sum(round(("oke"-coalesce(rejectbulantahun,0))/"oke"::numeric,6)*100) as okratio, "oke", coalesce(rejectbulantahun,0) as rejectbulantahun, "PartName" from (select count("PartName") as oke,"PartName", to_char("InjectTime" + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') as tanggal1 from "PI_Inject" where
        to_char("InjectTime" + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".')
        group by "PartName","tanggal1") as t1
        left join (select count("RejectionType") as rejectbulantahun,"NamaPart", to_char("InputDate" + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') as tanggal2 from "Pi_Rejection" where
        to_char("InputDate" + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE + INTERVAL '."'1 hours'".', '."'YYYY-MM-DD'".')
        group by "NamaPart","tanggal2") as t2
        on "t1"."PartName" = "t2"."NamaPart"
        group by "PartName", "oke", rejectbulantahun, oke');
        $part_name = array_column($query, 'PartName');
        $sum = array_column($query, 'okratio');
        for ($i=0; $i < count($part_name) ; $i++) {
          $x_data[$i] = $part_name[$i];
        }
        $result = [
          'x_data' => $x_data,
          'y_data' => $sum
        ];
        return $result;
      }

      public function getPlanningVsAchievement() {
        $planachiev = DB::select('select sum(round("achiev"/"target_total"::numeric,6)*100) as progres, "no_mc", "nama_part", "sph", "target_shift1","target_shift2","target_shift3","target_total", "ok","ng","achiev" from (select * from "PI_planing" where tanggal = to_char(CURRENT_DATE, '."'YYYY-MM-DD'".') and "status" = '."'ON'".') as t2
        left join
        (select coalesce(count1,0) as ok, coalesce(rejectbulantahun,0) as ng, ((coalesce(count1,0))-(coalesce(rejectbulantahun,0))) as achiev, tanggal, "t1"."NamaPart" from (select sum("QtyBox") as count1, "NamaPart",to_char("ProdDate", '."'YYYY-MM-DD'".') as tanggal
        from "PI_OkDataPart" where to_char("ProdDate", '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE, '."'YYYY-MM-DD'".') group by "NamaPart", tanggal) as t1 left join (select count("RejectionType") as rejectbulantahun, "NamaPart", to_char("InputDate", '."'YYYY-MM-DD'".') as tanggal2
        from "Pi_Rejection" where to_char("InputDate", '."'YYYY-MM-DD'".') = to_char(CURRENT_DATE, '."'YYYY-MM-DD'".')
        group by "NamaPart","tanggal2") as t2 on "t1"."NamaPart" = "t2"."NamaPart" group by tanggal, "t1"."NamaPart", "count1","rejectbulantahun") as t1
        ON "t2"."nama_part" = "t1"."NamaPart" left join "PI_Machine" ON "t2"."no_mc" = "PI_Machine"."Machine_Number" where "ok" is not null group by "no_mc", "nama_part", "sph", "target_shift1","target_shift2","target_shift3","target_total", "ok","ng","achiev" order by "no_mc" ASC');

        return $planachiev;
      }


}
