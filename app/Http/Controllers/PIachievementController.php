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

class PIachievementController extends Controller
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

    public function achievement() {
        $achievement = PIrejection::all();
        return view('report.achievement');
    }

    public function getAchievement(Request $request) {
        $filter = $request->filter;
        if ($filter == 'monthly') {
          $tahun = $request->tahun;
          $bulan = $request->bulan;
          if ($tahun == '' || $bulan == '') {
            $data = array(
              'data' => ''
            );
            return $data;
          }

            $achievementRatioDay = $this->getAchievementRatioDay($tahun, $bulan);
            $achievementVsPlanningDay = $this->getAchievementVsPlanningDay($tahun, $bulan);
            $data = array(
              'data_chart_3' => $achievementRatioDay,
              'data_chart_3a' => $achievementVsPlanningDay,
              'tahun' => $tahun,
              'bulan' => $bulan
            );
            return $data;

        }
        elseif ($filter == 'annual') {
            $tahun = $request->tahun;
            if ($tahun == '' ) {
              $data = array(
                'data' => ''
              );
              return $data;
            }

            // $rejectionPerMonthData = $this->getRejectionPerMonthData($tahun);
            $achievementRatioMonth = $this->getAchievementRatioMonth($tahun);
            $achievementVsPlanningMonth = $this->getAchievementVsPlanningMonth($tahun);
            $data = array(
              'data_chart_2' => $achievementRatioMonth,
              'data_chart_2a' => $achievementVsPlanningMonth,
              'tahun' => $tahun
            );
            return $data;
          }
    }

            // START DAILY DATA
            // START DAILY DATA
            // START DAILY DATA
            // START DAILY DATA

      public function getAchievementRatioDay($tahun, $bulan) {
        $query = DB::select('select sum(round(("oke"-coalesce(rejectbulantahun,0))/"oke"::numeric,4)*100) as okratio, "t1"."tgl", "t1"."bulan", "t1"."tahun", "oke", coalesce(rejectbulantahun,0) as rejectbulantahun
                            from (select count("PartName") as oke,
                            EXTRACT(DAY FROM "InjectTime" + INTERVAL '."'1 hours'".') as tgl, EXTRACT(MONTH FROM "InjectTime" + INTERVAL '."'1 hours'".') as bulan,
                            EXTRACT(YEAR FROM "InjectTime" + INTERVAL '."'1 hours'".') as tahun from "PI_Inject" where
                            EXTRACT(YEAR FROM "InjectTime" + INTERVAL '."'1 hours'".') = '.$tahun.' and
                            EXTRACT(MONTH FROM "InjectTime" + INTERVAL '."'1 hours'".') = '.$bulan.'
                            group by tgl, bulan, tahun) as t1
                            left join (select count("RejectionType") as rejectbulantahun,
                            EXTRACT(DAY FROM "InputDate" + INTERVAL '."'1 hours'".') as tgl, EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') as bulan,
                            EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') as tahun from "Pi_Rejection" where
                            EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$tahun.' and
                            EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$bulan.'
                            group by tgl, bulan, tahun) as t2
                            on "t1"."tgl" = "t2"."tgl" and
                            "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
                            group by "t1"."tgl", "t1"."bulan", "t1"."tahun", "oke", rejectbulantahun, oke');
        return $query;
      }

      public function getAchievementVsPlanningDay($tahun, $bulan) {
        $query = DB::select('select sum(round("achiev"/"target_total"::numeric,6)*100) as progres,"target_total", "ok","ng","achiev","t2"."tgl", "t2"."bulan", "t2"."tahun"
        from (select EXTRACT(YEAR FROM cast("tanggal" as date)) as tahun,
        EXTRACT(MONTH FROM cast("tanggal" as date)) as bulan,
        EXTRACT(DAY FROM cast("tanggal" as date)) as tgl, sum(cast("target_total" as integer)) as target_total from "PI_planing" where
        EXTRACT(YEAR FROM cast("tanggal" as date)) = '.$tahun.' and
        EXTRACT(MONTH FROM cast("tanggal" as date)) = '.$bulan.' and "status" = '."'ON'".'
        group by "tahun", "bulan", "tgl") as t2
        left join
        (select coalesce(count1,0) as ok, coalesce(rejectbulantahun,0) as ng, ((coalesce(count1,0))-(coalesce(rejectbulantahun,0))) as achiev, "t1"."tgl", "t1"."bulan", "t1"."tahun" from
        (select sum("QtyBox") as count1,
        EXTRACT(DAY FROM "ProdDate" + INTERVAL '."'1 hours'".') as tgl,
        EXTRACT(MONTH FROM "ProdDate" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "ProdDate" + INTERVAL '."'1 hours'".') as tahun
        from "PI_OkDataPart" where
        EXTRACT(YEAR FROM "ProdDate" + INTERVAL '."'1 hours'".') = '.$tahun.' and
        EXTRACT(MONTH FROM "ProdDate" + INTERVAL '."'1 hours'".') = '.$bulan.'
        group by tgl, bulan, tahun) as t1
        left join (select count("RejectionType") as rejectbulantahun,
        EXTRACT(DAY FROM "InputDate" + INTERVAL '."'1 hours'".') as tgl,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') as tahun
        from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$tahun.' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$bulan.'
        group by tgl, bulan, tahun) as t2
        on "t1"."tgl" = "t2"."tgl" and "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "t1"."tgl", "t1"."bulan", "t1"."tahun", "count1","rejectbulantahun") as t1
        on "t1"."tgl" = "t2"."tgl" and "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "target_total", "ok","ng","achiev", "t2"."tgl", "t2"."bulan", "t2"."tahun" ');
        return $query;
      }

    // END OF DATA DAILY
    // END OF DATA DAILY
    // END OF DATA DAILY
    // END OF DATA DAILY

    // START MONTHLY DATA
    // START MONTHLY DATA
    // START MONTHLY DATA
    // START MONTHLY DATA


    public function getAchievementRatioMonth($tahun) {
        $query = DB::select('select sum(round(("oke"-coalesce(rejectbulantahun,0))/"oke"::numeric,4)*100) as okratio, "t1"."bulan", "t1"."tahun", "oke", coalesce(rejectbulantahun,0) as rejectbulantahun
        from (select count("PartName") as oke,
        EXTRACT(MONTH FROM "InjectTime" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "InjectTime" + INTERVAL '."'1 hours'".') as tahun from "PI_Inject" where
        EXTRACT(YEAR FROM "InjectTime" + INTERVAL '."'1 hours'".') = '.$tahun.'
        group by bulan, tahun) as t1
        left join (select count("RejectionType") as rejectbulantahun,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$tahun.'
        group by bulan, tahun) as t2
        on "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "t1"."bulan", "t1"."tahun", "oke", rejectbulantahun, oke');
        return $query;
    }

    public function getAchievementVsPlanningMonth($tahun) {
        $query = DB::select('select sum(round("achiev"/"target_total"::numeric,6)*100) as progres,"target_total", "ok","ng","achiev", "t2"."bulan", "t2"."tahun"
        from (select EXTRACT(YEAR FROM cast("tanggal" as date)) as tahun,
        EXTRACT(MONTH FROM cast("tanggal" as date)) as bulan, sum(cast("target_total" as integer)) as target_total from "PI_planing"
        where EXTRACT(YEAR FROM cast("tanggal" as date)) = '.$tahun.' and "status" = '."'ON'".' group by "tahun", "bulan") as t2
        left join
        (select coalesce(count1,0) as ok, coalesce(rejectbulantahun,0) as ng, ((coalesce(count1,0))-(coalesce(rejectbulantahun,0))) as achiev, "t1"."bulan", "t1"."tahun" from
        (select sum("QtyBox") as count1,
        EXTRACT(MONTH FROM "ProdDate" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "ProdDate" + INTERVAL '."'1 hours'".') as tahun
        from "PI_OkDataPart" where
        EXTRACT(YEAR FROM "ProdDate" + INTERVAL '."'1 hours'".') = '.$tahun.'
        group by bulan, tahun) as t1
        left join (select count("RejectionType") as rejectbulantahun,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL '."'1 hours'".') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') as tahun
        from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL '."'1 hours'".') = '.$tahun.'
        group by bulan, tahun) as t2
        on "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "t1"."bulan", "t1"."tahun", "count1","rejectbulantahun") as t1
        on "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "target_total", "ok","ng","achiev", "t2"."bulan", "t2"."tahun" ');
        return $query;
      }

}
