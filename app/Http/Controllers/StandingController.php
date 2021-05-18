<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Temporada;

use DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class StandingController extends Controller
{
    public function getData(Request $request) {

        $tmp = Temporada::where("id", $request['id_temporada'])->get();

        // dd($tmp);

        $data = [
            'temporada' => $tmp[0]->descripcion
        ];

        DB::statement(DB::raw("set @row_number:=0"));

        $query = DB::table("scores as sco")
                    ->leftJoin("equipos as equi", "equi.id", "sco.id_equipo")
                    ->leftJoin("calendario as cal", "cal.id", "sco.id_partido")
                    ->leftJoin("temporadas_equipos as temeq", function($join) {

                        $join->on("temeq.id_temporada", "cal.id_temporada");
                        $join->on("temeq.id_equipo", "sco.id_equipo");
                        $join->whereNull("temeq.deleted_at");
                    })
                    ->leftJoin("zonas as zona", "zona.id", "temeq.id_zona")
                    ->leftJoin("grupos as grupo", "grupo.id", "temeq.id_grupo")
                    ->select(
                        "temeq.id_zona","zona.descripcion as zona","zona.abreviacion as zona_abreviacion",
                        "temeq.id_grupo","grupo.descripcion as grupo",
                        "sco.id_equipo","equi.descripcion as equipo",
                        DB::raw("ifnull(sum(sco.r),0) as r"),"cal.no_partido",
                        DB::raw("
                        ifnull(
                        	(
                        		(
		                            select sum(sco2.r)
		                            from beisbol.scores as sco2
		                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
		                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                        		)
                        	),0
                        ) as cr
                        "),
                        DB::raw("
                        ifnull(
                        	(
                        		sco.r-
                        		(
		                            select sum(sco2.r)
		                            from beisbol.scores as sco2
		                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
		                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                        		)
                        	),0
                        ) as cdif
                        "),
                        DB::raw("if(cal.id_visita=sco.id_equipo,'visitante','local') as condicion"),
                        DB::raw('count(sco.id_equipo) as total_juegos'),
                        DB::raw("
                        ifnull(sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                            and ifnull(sco.r,0) > ifnull(sco2.r,0)
                        )),0) as win"),
                        DB::raw("
                        ifnull(sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                            and ifnull(sco.r,0) < ifnull(sco2.r,0)
                        )),0) as lose"
                        ),
                        DB::raw("
                        ifnull(((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                            and ifnull(sco2.r,0)=ifnull(sco.r,0)
                        )),'0') as draw
                        "),
                        DB::raw("
                        ifnull(round((sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo and cal2.f_partido<=cal.f_partido
                            and ifnull(sco2.r,0) < ifnull(sco.r,0)
                        )))/count(sco.id_equipo),3),0) as pct
                        "),
                        DB::raw("(@row_number:=@row_number + 1) as ranking"),
                    )
                    ->where("cal.id_temporada", $request['id_temporada'])
                    // ->where("cal.f_partido", '<=', $request['fecha'])
                    ->groupBy("sco.id_equipo")
                    // ->orderBy('sco.no_partido')
                    ->orderBy("zona.descripcion")
                    ->orderBy("grupo.descripcion")
                    ->orderBy("win","desc")
                    ->orderBy("cdif","desc")
                    ->orderBy("pct","desc");

        if($request['f_partido']) $query->where("cal.f_partido", '<=', $request['f_partido']);
        if($request['id_equipo']) $query->where("sco.id_equipo", $request['id_equipo']);

       	/*$sub = DB::table("scores as sco")
                    ->leftJoin("equipos as equi", "equi.id", "sco.id_equipo")
                    ->leftJoin("calendario as cal", "cal.id", "sco.id_partido")
                    
                    ->leftJoin("temporadas_equipos as temeq", function($join) {

                        $join->on("temeq.id_temporada", "cal.id_temporada");
                        $join->on("temeq.id_equipo", "sco.id_equipo");
                        $join->whereNull("temeq.deleted_at");
                    })
                    ->leftJoin("zonas as zona", "zona.id", "temeq.id_zona")
                    ->leftJoin("grupos as grupo", "grupo.id", "temeq.id_grupo")
                    ->selectRaw("
                        temeq.id_zona,zona.descripcion as zona,zona.abreviacion as zona_abreviacion,
                        temeq.id_grupo,grupo.descripcion as grupo,
                        sco.id_equipo,equi.descripcion as equipo,ifnull(sum(sco.r),0) as r,cal.no_partido,
                        ifnull(
                        	(
                        		(
		                            select sum(sco2.r)
		                            from beisbol.scores as sco2
		                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
		                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                        		)
                        	),0
                        ) as cr,
                        ifnull(
                        	(
                        		sco.r-
                        		(
		                            select sum(sco2.r)
		                            from beisbol.scores as sco2
		                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
		                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                        		)
                        	),0
                        ) as cdif,if(cal.id_visita=sco.id_equipo,'visitante','local') as condicion,
                        count(sco.id_equipo) as total_juegos,
                        ifnull(sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                            and ifnull(sco.r,0) > ifnull(sco2.r,0)
                        )),0) as win,
                        ifnull(sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                            and ifnull(sco.r,0) < ifnull(sco2.r,0)
                        )),0) as lose,
                        ifnull(((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                            and ifnull(sco2.r,0)=ifnull(sco.r,0)
                        )),'0') as draw,
                        ifnull(round((sum((
                            select 1
                            from beisbol.scores as sco2
                            left join beisbol.calendario as cal2 on cal2.id=sco2.id_partido
                            where sco2.id_partido=sco.id_partido and sco2.id_equipo!=sco.id_equipo
                            and ifnull(sco2.r,0) < ifnull(sco.r,0)
                        )))/count(sco.id_equipo),3),0) as pct
                    ")
                    ->where("cal.id_temporada", $request['id_temporada'])
                    // ->where("cal.f_partido", '<=', $request['fecha'])
                    ->groupBy("sco.id_equipo")
                    // ->orderBy('sco.no_partido')
                    ->orderBy("zona.descripcion")
                    ->orderBy("grupo.descripcion")
                    ->orderBy("win","desc")
                    ->orderBy("cdif","desc")
                    ->orderBy("pct","desc");

        $query = DB::table(DB::raw('('.$sub ->toSql().') as A'))
            ->select(
                "A.equipo","A.win", "A.lose","A.r","A.cr","A.cdif","A.pct",
                DB::raw("(@row_number:=@row_number + 1) as ranking"),
            )
            ->orderBy("win","desc")
            ->orderBy("cdif","desc")
            ->orderBy("pct","desc");*/

        // dd($query->toSql());

        $data = $query->get();

        if(sizeof($data)) {

        	foreach ($data as $k => $row) {

        		$data[$k]->ranking = $k + 1;
        	}
        }

        return $data;
    }
}
