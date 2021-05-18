<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Calendario;
use App\Models\Temporada;

use DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class ScoreController extends Controller
{
    public function getData(Request $request) {

        // dd($request);

        $query = DB::table("scores as sco")
                ->leftJoin("calendario as cal", "cal.id", "sco.id_partido")
                ->leftJoin("temporadas as temp", "temp.id", "cal.id_temporada")
                ->leftJoin("equipos as equi", "equi.id", "sco.id_equipo")
                ->select(
                    "sco.id","cal.id_temporada","temp.descripcion as temporada",
                    "sco.id_partido","sco.id_equipo",
                    "equi.descripcion as equipo",
                    "equi.descripcion_corta as equipo_corto",
                    "equi.abreviacion as equipo_abreviacion",
                    "cal.no_programa","cal.no_jornada","cal.no_serie","cal.no_partido",
                    DB::raw("ifnull(sco.r, 0) as r"),
                    DB::raw("ifnull(sco.h, 0) as h"),
                    DB::raw("ifnull(sco.e, 0) as e")
                );

        // $query->orderBy('sco.id_score_posicion');

        // if($request['id_temporada']) $query->where('sco.id_temporada', $request['id_temporada']);

        if($request['id_partido']) $query->where("sco.id_partido", $request['id_partido']);

        if($request['id_equipo']) $query->where("sco.id_equipo", $request['id_equipo']);


        // dd($query->toSql());

        $data = $query->get();

        // dd($data);

        return $data;
    }
}
