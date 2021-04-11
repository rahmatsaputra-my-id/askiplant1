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
use Illuminate\Support\Facades\Log;

class PIrejectionController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      //$henkaten = Henkaten::orderBy("Machine_Number","ASC")->get();
      // $rejectiondd = DB::select('select count("RejectionType"), "RejectionType", "NamaPart", to_char("InputDate","yyyy-mm-dd") from "Pi_Rejection" group by "NamaPart", "RejectionType", to_char("InputDate","yyyy-mm-dd")');

      return view('index');
   }

   public function rejection()
   {
      $rejection = PIrejection::all();
      return view('report.rejection');
   }

   public function getRejection(Request $request)
   {
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

         $rejectionPerDayData = $this->getRejectionPerDayData($tahun, $bulan);
         $rejectionPercentPerDayData = $this->getRejectionPercentPerDayData($tahun, $bulan);
         $rejectionPerPartData = $this->getRejectionPerPartData($tahun, $bulan);
         $rejectionPerTypePartData = $this->getRejectionPerTypePartData($tahun, $bulan);
         $chart3fData = $this->getChart3fData($tahun, $bulan);
         $chart3gData = $this->getChart3gData($tahun, $bulan);
         $part_name = $this->getPartName($tahun, $bulan);
         $rejection_type = $this->getTypeName($tahun, $bulan);
         $data = array(
            'data_chart_3' => $rejectionPerDayData,
            'data_chart_3a' => $rejectionPerPartData,
            'data_chart_3c' => $rejectionPercentPerDayData,
            'data_chart_3e' => $rejectionPerTypePartData,
            'data_chart_3f' => $chart3fData,
            'data_chart_3g' => $chart3gData,
            'part_name' => $part_name,
            'rejection_type' => $rejection_type,
            'tahun' => $tahun,
            'bulan' => $bulan
         );
         return $data;
      } elseif ($filter == 'annual') {
         $tahun = $request->tahun;
         if ($tahun == '') {
            $data = array(
               'data' => ''
            );
            return $data;
         }

         $rejectionPerMonthData = $this->getRejectionPerMonthData($tahun);
         $rejectionPercentPerMonthData = $this->getRejectionPercentPerMonthData($tahun);
         $rejectionPerPartDataMonth = $this->getRejectionPerPartDataMonth($tahun);
         $rejectionPerTypePartDataMonth = $this->getRejectionPerTypePartDataMonth($tahun);
         $chart2dDataMonth = $this->getChart2dDataMonth($tahun);
         $chart2eDataMonth = $this->getChart2eDataMonth($tahun);
         $part_name = $this->getPartNameMonth($tahun);
         $rejection_type = $this->getTypeNameMonth($tahun);
         $data = array(
            'data_chart_2' => $rejectionPerMonthData,
            'data_chart_2b' => $rejectionPerPartDataMonth,
            'data_chart_2a' => $rejectionPercentPerMonthData,
            'data_chart_2c' => $rejectionPerTypePartDataMonth,
            'data_chart_2d' => $chart2dDataMonth,
            'data_chart_2e' => $chart2eDataMonth,
            'part_name' => $part_name,
            'rejection_type' => $rejection_type,
            'tahun' => $tahun
         );
         return $data;
      } elseif ($filter == 'daily') {
         $tahun = $request->tahun;
         $bulan = $request->bulan;
         $tanggal = $request->tanggal;
         // $jam = $request->jam;
         if ($tahun == '' || $bulan == '' || $tanggal == '') {
            $data = array(
               'data' => ''
            );
            return $data;
         }

         $shiftName = ["Shift 1", "Shift 2", "Shift 3"];
         $chart1DataShift = $this->getChart1DataShift($tahun, $bulan, $tanggal);
         $chart1aDataShift = $this->getChart1aDataShift($tahun, $bulan, $tanggal);
         $chart1bDataShift = $this->getChart1bDataShift($tahun, $bulan, $tanggal);
         $rejection_per_type_part_data = $this->getRejectionPerTypePartData($tahun, $bulan);
         // $rejection_per_shift_data = $this->rejectionPerShiftData($tahun, $bulan);
         $part_name = $this->getPartName($tahun, $bulan);
         $rejection_type = $this->getTypeName($tahun, $bulan);
         $data = array(
            'data_chart_1' => $chart1DataShift,
            'data_chart_1a' => $chart1aDataShift,
            'data_chart_1b' => $chart1bDataShift,
            'rejection_per_type_part_data' => $rejection_per_type_part_data,
            // 'rejectionPerShiftData' => $rejection_per_shift_data,
            'part_name' => $part_name,
            'shift_name' => $shiftName,
            'rejection_type' => $rejection_type,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tanggal' => $tanggal
         );
         return $data;
      }
   }

   // START DAILY DATA
   // START DAILY DATA
   // START DAILY DATA
   // START DAILY DATA

   public function getRejectionPerDayData($tahun, $bulan)
   {
      $query = DB::select('select sum(rejectbulantahun), tgl, "NamaPart" as r1
        from (select count("RejectionType") as rejectbulantahun, "RejectionType", "NamaPart",
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl, EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", tgl, bulan, tahun) as t1 group by tgl, r1');

      $part_name = array_unique(Arr::pluck($query, 'r1'));

      foreach ($query as $data) {
         $rejection_data[$data->r1][$data->tgl] = $data->sum;
      }
      $result = $this->generateRejectionPerDayArray($rejection_data);
      return $result;
   }

   public function getRejectionPercentPerDayData($tahun, $bulan)
   {
      $query = DB::select('select sum(round(coalesce(rejectbulantahun,0)/"oke"::numeric,6)*100) as rejectpersen, "t1"."tgl", "t1"."bulan", "t1"."tahun", "oke", coalesce(rejectbulantahun,0) as rejectbulantahun
                            from (select count("PartName") as oke,
                            EXTRACT(DAY FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') as tgl, EXTRACT(MONTH FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') as bulan,
                            EXTRACT(YEAR FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') as tahun from "PI_Inject" where
                            EXTRACT(YEAR FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
                            EXTRACT(MONTH FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
                            group by tgl, bulan, tahun) as t1
                            left join (select count("RejectionType") as rejectbulantahun,
                            EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl, EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
                            EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
                            EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
                            EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
                            group by tgl, bulan, tahun) as t2
                            on "t1"."tgl" = "t2"."tgl" and
                            "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
                            group by "t1"."tgl", "t1"."bulan", "t1"."tahun", "oke", rejectbulantahun, oke');
      return $query;
   }
   //untuk rahmat kerjakan pecah 3 shift
   public function getRejectionPerPartData($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));

      foreach ($query as $data) {
         $rejection_data[$data->RejectionType][$data->NamaPart] = $data->sum;
      }
      $result = $this->generateRejectionPerPartArray($rejection_data, $part_name);
      return $result;
   }

   public function getChart3fData($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", bulan, tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      return $query;
   }

   public function getChart3gData($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "RejectionType", bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "RejectionType", bulan, tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      return $query;
   }

   public function getRejectionPerTypePartData($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "RejectionType", bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));

      foreach ($query as $data) {
         $rejection_data[$data->NamaPart][$data->RejectionType] = $data->sum;
      }
      $result = $this->generateRejectionPerTypePartArray($rejection_data, $rejection_type);
      return $result;
   }

   public function getPartName($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", bulan, tahun) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      return $part_name;
   }

   public function getTypeName($tahun, $bulan)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "RejectionType", bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));
      return $rejection_type;
   }

   public function generateRejectionPerPartArray($rejection_data, $part_name)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         foreach ($part_name as $part) {
            if (array_key_exists($part, $data)) {
               array_push($rejectionByParts, (int)$data[$part]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }
         $result[$key] = $rejectionByParts;
      }
      return $result;
   }

   public function generateRejectionPerTypePartArray($rejection_data, $rejection_type)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         foreach ($rejection_type as $part) {
            if (array_key_exists($part, $data)) {
               array_push($rejectionByParts, (int)$data[$part]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }
         $result[$key] = $rejectionByParts;
      }
      return $result;
   }

   public function generateRejectionPerDayArray($rejection_data)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         for ($i = 1; $i <= 31; $i++) {
            if (array_key_exists($i, $data)) {
               array_push($rejectionByParts, (int)$data[$i]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }

         $result[$key] = $rejectionByParts;
      }
      return $result;
   }

   public function generateRejectionPerShiftDayArray($query)
   {
      $result = [];
      $rejection_data_arr = [
         "Shift1" => [
            "Trial Istirahat" => 13,
            "Flow Mark" => 29,
            "Gating" => 3,
            "Short Shot" => 10,
            "Trial Produksi" => 30,
            "Trial Engineering" => 3,
            "Sink Mark" => 27,
            "Silver Streaks" => 31,
            "Cracking" => 63,
         ],
         "Shift2" => [
            "Trial Istirahat" => 22,
            "Flow Mark" => 9,
            "Gating" => 7,
            "Short Shot" => 15,
            "Trial Produksi" => 15,
            "Trial Engineering" => 7,
            "Sink Mark" => 4,
            "Silver Streaks" => 34,
            "Cracking" => 20,
         ],
         "Shift3" => [
            "Trial Istirahat" => 1,
            "Flow Mark" => 6,
            "Gating" => 35,
            "Short Shot" => 12,
            "Trial Produksi" => 9,
            "Trial Engineering" => 7,
            "Sink Mark" => 2,
            "Silver Streaks" => 4,
            "Cracking" => 5,
         ]
      ];

      // $RejectionType = array_column($query, 'RejectionType');
      // $jam = array_column($query, 'jam');
      // $combine = array_combine($RejectionType, $jam);

      // $multiple_column = $this->colsFromArray($query, array("RejectionType", "jam"));

      // $rejection_data_arr = [
      //    "Shift1" => [
      //       "Trial Istirahat" => 13,
      //    ],
      //    "Shift2" => [
      //       "Trial Istirahat" => 22,
      //    ],
      //    "Shift3" => [
      //       "Trial Istirahat" => 1,
      //    ]
      // ];

      // $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      // $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));
      // $jam = array_unique(Arr::pluck($query, 'jam'));


      // $count_duplicate_jam = array_count_values($jam);


      // foreach ($query as $data) {
      //    $rejection_data[$data->NamaPart][$data->RejectionType] = $data->sum;
      // }

      // foreach ($query as $data) {
      //    $rejection_data[$data->RejectionType] = $data->jam;
      // }

      // $rejectionType = [$data->RejectionType];
      // $rejectionNamaPart = [$data->NamaPart];




      // foreach ($rejection_data as $key => $data) {
      //    $rejectionByParts = [];
      //    $rejectionByShift = [];
      //    $rejectionShift1 = [];
      //    $rejectionShift2 = [];
      //    $rejectionShift3 = [];
      //    foreach ($rejection_type as $part) {
      //       // if (array_key_exists($part, $data)) {
      //       //    array_push($rejectionByParts, (int)$data[$part]);
      //       //    continue;
      //       // } else {
      //       //    array_push($rejectionByParts, 0);
      //       //    continue;
      //       // }

      //       if (array_key_exists($part, $data)) {
      //          // array_push($rejectionByParts, (int)$data[$part]);
      //          // continue;
      //          if($rejectionByShift >= 0 && $rejectionByShift <=12){
      //             array_push($rejectionByParts, (int)$data[$part]);
      //             continue;
      //          }
      //          if( $rejectionByShift >= 13 && $rejectionByShift <= 17){
      //             array_push($rejectionByParts, (int)$data[$part]);
      //             continue;
      //          }
      //          if($rejectionByShift >= 18 && $rejectionByShift <= 23){
      //             array_push($rejectionByParts, (int)$data[$part]);
      //             continue;
      //          } 
      //       } else {
      //          array_push($rejectionByParts, 0);
      //          continue;
      //       }

      //    }
      //    $result[$key] = $rejectionByParts;
      // }
      // return $test_combine;

      // foreach ($rejection_data as $key => $data) {
      //    $rejectionByParts = [];
      //    $rejectionByShift = [];
      //    $rejectionShift1 = [];
      //    $rejectionShift2 = [];
      //    $rejectionShift3 = [];
      //    foreach ($rejection_type as $part) {
      //       // if (array_key_exists($part, $data)) {
      //       //    array_push($rejectionByParts, (int)$data[$part]);
      //       //    continue;
      //       // } else {
      //       //    array_push($rejectionByParts, 0);
      //       //    continue;
      //       // }
      //    }
      //    $result[$key] = $rejectionByParts;
      // }
      // return $result;
      return $rejection_data_arr;
   }

   public function getTableData(Request $request)
   {
      $tahun = $request->tahun;
      $bulan = $request->bulan;
      $filtermonthyeartable = DB::select('select sum(rejectbulantahun) as sum, "NamaPart", "RejectionType"
        from (select count("RejectionType") as rejectbulantahun, "RejectionType", "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun) as t1 group by "NamaPart", "RejectionType" order by sum DESC limit 10');
      $part_name = array_column($filtermonthyeartable, 'NamaPart');
      $rejection_type = array_column($filtermonthyeartable, 'RejectionType');
      $sum = array_column($filtermonthyeartable, 'sum');
      for ($i = 0; $i < count($part_name); $i++) {
         $x_data[$i] = $part_name[$i] . '-' . $rejection_type[$i];
      }
      $result = [
         'x_data' => $x_data,
         'y_data' => $sum
      ];
      return $result;
   }

   public function getTableData2(Request $request)
   {
      $tahun = $request->tahun;
      $bulan = $request->bulan;
      $filtermonthyeartable2 = DB::select('select sum(rejectbulantahun) as sum, "NamaPart", "RejectionType", tanggal
        from (select count("RejectionType") as rejectbulantahun, "RejectionType", "NamaPart",substring(cast("InputDate" as varchar),1,10) as tanggal,
		  EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . '
        group by "NamaPart", "RejectionType", bulan, tahun, tgl,tanggal) as t1 group by "NamaPart", "RejectionType",tanggal order by sum DESC');

      return $filtermonthyeartable2;
   }

   // END OF DATA DAILY
   // END OF DATA DAILY
   // END OF DATA DAILY
   // END OF DATA DAILY

   // START MONTHLY DATA
   // START MONTHLY DATA
   // START MONTHLY DATA
   // START MONTHLY DATA

   public function getRejectionPerMonthData($tahun)
   {
      $query = DB::select('select sum(rejectbulantahun), bulan, "NamaPart" as r1
        from (select count("RejectionType") as rejectbulantahun, "RejectionType", "NamaPart",
		  EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", "RejectionType",bulan, tahun) as t1 group by bulan, r1');

      $part_name = array_unique(Arr::pluck($query, 'r1'));

      foreach ($query as $data) {
         $rejection_data[$data->r1][$data->bulan] = $data->sum;
      }
      $result = $this->generateRejectionPerMonthArray($rejection_data);
      return $result;
   }
   public function generateRejectionPerMonthArray($rejection_data)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         for ($i = 1; $i <= 12; $i++) {
            if (array_key_exists($i, $data)) {
               array_push($rejectionByParts, (int)$data[$i]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }

         $result[$key] = $rejectionByParts;
      }
      return $result;
   }
   public function getPartNameMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", tahun) as t1
		left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", "RejectionType", tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      return $part_name;
   }
   public function getRejectionPerPartDataMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", tahun order by rejectbulantahun1 desc limit 10) as t1
		left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", "RejectionType", tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));

      foreach ($query as $data) {
         $rejection_data[$data->RejectionType][$data->NamaPart] = $data->sum;
      }
      $result = $this->generateRejectionPerPartMonthArray($rejection_data, $part_name);
      return $result;
   }
   public function generateRejectionPerPartMonthArray($rejection_data, $part_name)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         foreach ($part_name as $part) {
            if (array_key_exists($part, $data)) {
               array_push($rejectionByParts, (int)$data[$part]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }
         $result[$key] = $rejectionByParts;
      }
      return $result;
   }
   public function getTypeNameMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "RejectionType", tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", "RejectionType", tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));
      return $rejection_type;
   }
   public function getRejectionPerTypePartDataMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "RejectionType", tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType", "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", "RejectionType", tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      $part_name = array_unique(Arr::pluck($query, 'NamaPart'));
      $rejection_type = array_unique(Arr::pluck($query, 'RejectionType'));

      foreach ($query as $data) {
         $rejection_data[$data->NamaPart][$data->RejectionType] = $data->sum;
      }
      $result = $this->generateRejectionPerTypePartMonthArray($rejection_data, $rejection_type);
      return $result;
   }
   public function generateRejectionPerTypePartMonthArray($rejection_data, $rejection_type)
   {
      $result = [];
      foreach ($rejection_data as $key => $data) {
         $rejectionByParts = [];
         foreach ($rejection_type as $part) {
            if (array_key_exists($part, $data)) {
               array_push($rejectionByParts, (int)$data[$part]);
               continue;
            } else {
               array_push($rejectionByParts, 0);
               continue;
            }
         }
         $result[$key] = $rejectionByParts;
      }
      return $result;
   }
   public function getRejectionPercentPerMonthData($tahun)
   {
      $query = DB::select('select sum(round(coalesce(rejectbulantahun,0)/"oke"::numeric,6)*100) as rejectpersen, "t1"."bulan", "t1"."tahun", "oke", coalesce(rejectbulantahun,0) as rejectbulantahun
        from (select count("PartName") as oke,
        EXTRACT(MONTH FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') as tahun from "PI_Inject" where
        EXTRACT(YEAR FROM "InjectTime" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by bulan, tahun) as t1
        inner join (select count("RejectionType") as rejectbulantahun,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by bulan, tahun) as t2
        on "t1"."bulan" = "t2"."bulan" and "t1"."tahun" = "t2"."tahun"
        group by "t1"."bulan", "t1"."tahun", "oke", rejectbulantahun, oke');
      return $query;
   }
   public function getChart2dDataMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "NamaPart",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "NamaPart", tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      return $query;
   }
   public function getChart2eDataMonth($tahun)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "RejectionType", tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType",
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . '
        group by "RejectionType", tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      return $query;
   }

   // END OF MONTHLY DATA
   // END OF MONTHLY DATA
   // END OF MONTHLY DATA
   // END OF MONTHLY DATA

   // START SHIFTLY DATA
   // START SHIFTLY DATA
   // START SHIFTLY DATA
   // START SHIFTLY DATA

   public function getChart1DataShift($tahun, $bulan, $tanggal)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "NamaPart",
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
        EXTRACT(DAY FROM "InputDate") = ' . $tanggal . '
        group by "NamaPart",tgl, bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "NamaPart",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tanggal . '
        group by "NamaPart",tgl, bulan, tahun) as t2 on "t1"."NamaPart" = "t2"."NamaPart" order by rejectbulantahun1 desc');

      return $query;
   }

   public function getChart1aDataShift($tahun, $bulan, $tanggal)
   {
      $query = DB::select('select *
        from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tanggal . '
        group by "RejectionType",tgl, bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
		  left join (select count("RejectionType") as sum, "RejectionType",
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
        EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
        EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
        EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tanggal . '
        group by "RejectionType",tgl, bulan, tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      return $query;
   }

   public function getChart1bDataShift($tahun, $bulan, $tanggal)
   {
      // $query = DB::select('select *
      //   from (select count("RejectionType") as rejectbulantahun1, "RejectionType",
      //   EXTRACT(HOUR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as jam,
      //   EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
      //   EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
      //   EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
      //   EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
      //   EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
      //   EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tanggal . '
      //   group by "RejectionType", jam, tgl, bulan, tahun order by rejectbulantahun1 desc limit 10) as t1
      //   left join (select count("RejectionType") as sum, "RejectionType",
      //   EXTRACT(HOUR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as jam,
      //   EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tgl,
      //   EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as bulan,
      //   EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') as tahun from "Pi_Rejection" where
      //   EXTRACT(YEAR FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tahun . ' and
      //   EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $bulan . ' and
      //   EXTRACT(DAY FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') = ' . $tanggal . '
      //   group by "RejectionType", jam, tgl, bulan, tahun) as t2 on "t1"."RejectionType" = "t2"."RejectionType" order by rejectbulantahun1 desc');

      $query3 = 
         DB::select('
            SELECT "NamaPart", "RejectionType", "tgl", "bulan", "tahun", "shift", COUNT(*) jumlah_shift_duplikat FROM 
            ( 
               SELECT "NamaPart", "RejectionType", 
               EXTRACT(DAY   FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') AS tgl,
               EXTRACT(MONTH FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') AS bulan,
               EXTRACT(YEAR  FROM "InputDate" + INTERVAL ' . "'1 hours'" . ') AS tahun, 
               "InputDate"::TIME waktu, 
               (
                  CASE 
                     WHEN "InputDate"::TIME > ' . "'00:00:00'" . ' AND "InputDate"::TIME <= ' . "'12:00:00'" . ' THEN 1
                     WHEN "InputDate"::TIME > ' . "'12:00:00'" . ' AND "InputDate"::TIME <= ' . "'17:00:00'" . ' THEN 2
                     WHEN "InputDate"::TIME > ' . "'17:00:00'" . ' AND "InputDate"::TIME <= ' . "'24:00:00'" . ' THEN 3 
                  END
               ) shift FROM "Pi_Rejection"
            ) a 
            GROUP BY "NamaPart", "RejectionType", "tgl",  "bulan", "tahun", "shift" 
            ORDER BY "NamaPart", "RejectionType", "tgl",  "bulan", "tahun", "shift"
            DESC
            LIMIT 50
         ');

      // $result = $this->generateRejectionPerShiftDayArray($query);

      return $query3;
   }
}
