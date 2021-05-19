<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Calendario;
use App\Models\Temporada;

use DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class EstadisticaController extends Controller
{
    public function getData(Request $request) {

        $query = DB::table("estadisticas as stat")
                ->select(
                    "stat.id","stat.id_estadistica","stat.descripcion","stat.descripcion as estadistica",
                    "stat.definicion","stat.status"
                );

        $query->orderBy("stat.descripcion");

        if($request['id_estadistica']) $query->where("stat.id_estadistica", $request['id_estadistica']);

        $data = $query->get();
        
        return $data;
    }

    public function estadisticas(Request $request) {

        if($request['id_tipo_estadistica'] == 'bateo') {
            
            // dd($request);

            $query = DB::table("scores_boxscore as box")
                ->leftJoin("jugadores as jug", "jug.id", "box.id_jugador")
                ->leftJoin("equipos as equi", "equi.id", "box.id_equipo")
                ->select("box.id_jugador","jug.nombre","jug.apellido1","jug.apellido2",
                    DB::raw("concat(jug.nombre,' ',jug.apellido1) as jugador"),"box.id_equipo",
                    "equi.descripcion as equipo","equi.descripcion_corta as equipo_corto","equi.abreviacion as equipo_abreviacion",
                    DB::raw("ifnull(sum(box.ab),0) as ab"),
                    DB::raw("ifnull(sum(box.h),0) as h"),
                    DB::raw("ifnull(sum(box.h1),0) as h1"),
                    DB::raw("ifnull(sum(box.h2),0) as h2"),
                    DB::raw("ifnull(sum(box.h3),0) as h3"),
                    DB::raw("ifnull(sum(box.hr),0) as hr"),
                    DB::raw("ifnull(sum(box.rbi),0) as rbi"),
                    DB::raw("ifnull(sum(box.r),0) as r"),
                    DB::raw("ifnull(sum(box.bb),0) as bb"),
                    DB::raw("ifnull(sum(box.k),0) as k"),
                    DB::raw("ifnull(sum(box.lob),0) as lob"),
                    DB::raw("ifnull(sum(box.e),0) as e"),
                    DB::raw("ifnull(sum(box.sb),0) as sb"),
                    DB::raw("round(ifnull(sum(box.h) / sum(box.ab),0),3) as avg"),
                    DB::raw("if(
                        round(ifnull(sum(box.h) / sum(box.ab),0),3) < 1,
                            substring(round(ifnull(sum(box.h) / sum(box.ab),0),3),2,4),
                            round(ifnull(sum(box.h) / sum(box.ab),0),3)
                    ) as avg_"
                    ),
                    DB::raw("
                        (
                            select count(box.id_jugador) 
                            from scores_boxscore as box2 
                            where box2.id_temporada=box.id_temporada and box2.id_jugador=box.id_jugador
                            group by box2.id_jugador
                        ) as no_juegos
                        "
                    ),
                    "box.id_equipo","equi.descripcion as equipo",
                    DB::raw("
                        (
                            select count(sco.id_partido) * 2
                            from scores as sco
                            left join calendario as cal on cal.id=sco.id_partido
                            where cal.id_temporada=box.id_temporada and sco.id_equipo=box.id_equipo
                        ) as turnos_oficiales
                        "
                    ),
                )
                ->where("box.id_temporada", $request['id_temporada'])
                ->groupBy("box.id_jugador");
                // ->havingRaw("h > ?",[0]);

                if($request['id_equipo']) {

                    $query->where("box.id_equipo", $request['id_equipo']);
                }
                
                if($request['turnos_oficiales']) {
    
                    $query->havingRaw("ab >= turnos_oficiales");
                }

            if($request['order_by']) {

                $order_type = $request['order_type'] ? $request['order_type'] : 'desc';

                $query->orderBy($request['order_by'], $order_type);
                // $query->where($request['order_by'], '>', 0);
                $query->havingRaw($request['order_by']." > ?",[0]);
            }
            else $query->orderBy("avg","desc");

            if($request['mod_op'] == "lideres") {
                $query->limit(10);
            }
            // if($request['order_by']) dd($query->toSql());

            

            $data = $query->get();
        }
        else if($request['id_tipo_estadistica'] == 'picheo') {

            $query = DB::table("scores_pitchers as box")
                ->leftJoin("jugadores as jug", "jug.id", "box.id_jugador")
                ->leftJoin("equipos as equi", "equi.id", "box.id_equipo")
                ->select("box.id_jugador",
                    DB::raw("concat(jug.nombre,' ',jug.apellido1) as jugador"),
                    DB::raw("sum(box.h) as h"),
                    DB::raw("ifnull(sum(box.h1),0) as h1"),
                    DB::raw("ifnull(sum(box.h2),0) as h2"),
                    DB::raw("ifnull(sum(box.h3),0) as h3"),
                    DB::raw("sum(box.hr) as hr"),
                    DB::raw("count(box.id_partido) as g"),
                    DB::raw("ifnull(sum(box.r),0) as r"),
                    DB::raw("ifnull(sum(box.bb),0) as bb"),
                    DB::raw("ifnull(sum(box.k),0) as k"),
                    DB::raw("if(box.id_decision='W',count(box.id_decision),0) as w"),DB::raw("if(box.id_decision='L',count(box.id_decision),0) as l"),
                    // DB::raw("cast(substring_index(sum(box.ip), '.', 1) as unsigned) as ip_"),
                    // DB::raw("cast(substring_index(sum(box.ip), '.', -1) as unsigned) as ip_outs"),
                    // DB::raw("cast(substring_index(sum(box.ip), '.', -1) as unsigned) as ip_total_outs"),
                    // DB::raw("mod(cast(substring_index(sum(box.ip), '.', -1) as unsigned), 3) as ip_outs"),
                    DB::raw("round(sum(round(box.ip)) +  (cast(substring_index(sum(box.ip), '.', -1) as unsigned) div 3)  + (mod(cast(substring_index(sum(box.ip), '.', -1) as unsigned), 3) / 10),1) as ip"),
                    DB::raw("round((sum(round(box.ip)) +  (cast(substring_index(sum(box.ip), '.', -1) as unsigned) div 3)  + (mod(cast(substring_index(sum(box.ip), '.', -1) as unsigned), 3) / 10)) / 9, 2) as era"),
                    "equi.descripcion as equipo"
                )
                ->where("box.id_temporada", $request['id_temporada'])
                ->groupBy("box.id_jugador");
                // ->havingRaw("h > ?",[0]);

            $query->orderBy("h","desc");

            // dd($query->toSql());

            $data = $query->get();

            if(sizeof($data)) {

                foreach($data as $k => $row) {


                }
            }
            
        }

        return $data;
    }
}
