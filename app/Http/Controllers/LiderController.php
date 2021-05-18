<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Calendario;
use App\Models\Temporada;

use DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LiderController extends Controller
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

    public function lideres(Request $request) {

        $data =  [
            'avg' =>  [
                'estadistica' => 'Promedio de Bateo',
                'jugador' =>  null
            ],
            'h' =>  [
                'estadistica' => 'Hits',
                'jugador' =>  null
            ],
            'h2' =>  [
                'estadistica' => 'Dobles',
                'jugador' =>  null
            ],
            'h3' =>  [

                'estadistica' => 'Triples',
                'jugador' =>  null
            ],
            'hr' =>  [
                'estadistica' => 'Home Runs',
                'jugador' =>  null
            ],
            'rbi' =>  [
                'estadistica' => 'Carreras Impulsadas',
                'jugador' =>  null
            ],
            'r' =>  [
                'estadistica' => 'Carreras Anotadas',
                'jugador' =>  null
            ],
        ];

        // dd($data);

        DB::table('tmp_estadisticas')->truncate();

        foreach($data as $k => $row) {

            $request['order_by'] = $k;
            $request['order_type'] = 'desc';

            // print_r($request);

            $tmp = app(EstadisticaController::class)->estadisticas($request);
                
            foreach ($tmp as $k_ => $row_) {

                $tmp_ = explode(' ', $row_->nombre);

                $jugador = $tmp_[0]." ".$row_->apellido1;

                DB::insert('insert into tmp_estadisticas 
                    (
                        id_jugador,jugador,id_equipo,equipo,id_estadistica,valor
                    ) 
                    values 
                    (
                        ?,?,?,?,?,?
                    )', 
                    [$row_->id_jugador,$jugador,$row_->id_equipo,$row_->equipo_corto,$k,$row_->$k]
                );
            }

            $query = DB::table("tmp_estadisticas")
                            ->select(
                                "jugador","id_estadistica","equipo",
                                DB::raw("if(
                                    id_estadistica='avg',format(valor, 3),
                                    format(valor, 0)
                                ) as valor"),
                                DB::raw("count(id_jugador) as empatados")
                            )
                            ->where("id_estadistica", $k)
                            ->orderBy("valor", 'desc')
                            ->groupBy("valor");
            
            $tmp = $query->get();
                    
            if(sizeof($tmp)) {

                if($tmp[0]->empatados == 1) {

                    $data[$k]['jugador'] = [
                        'jugador' => $tmp[0]->jugador,
                        'valor' => $tmp[0]->valor,
                        'equipo' => $tmp[0]->equipo,
                    ];
                }
                if($tmp[0]->empatados == 2) {

                    $query = DB::table("tmp_estadisticas")
                            ->select(
                                "jugador","id_estadistica","equipo",
                                DB::raw("if(
                                    id_estadistica='avg',format(valor, 3),
                                    format(valor, 0)
                                ) as valor"),
                                DB::raw("count(id_jugador) as empatados")
                            )
                            ->where("id_estadistica", $k)
                            ->orderBy("valor", 'desc')
                            ->limit(2);
            
                    $t1 = $query->get();

                    foreach($t1 as $k1 => $r1) {

                        $data[$k]['jugador'] = [
                            'jugador' => $r1->jugador,
                            'valor' => $r1->valor,
                            'equipo' => $r1->equipo,
                        ];
                    }
                }
                else if($tmp[0]->empatados > 2) {
                    $data[$k]['jugador'] = [
                        'jugador' => $tmp[0]->empatados.' Empatados',
                        'valor' => $tmp[0]->valor,
                        'empatados' => $tmp[0]->empatados,
                        'equipo' => null,
                    ];
                }
                // echo $k." | ".$tmp[0]->valor." | ".$tmp[0]->empatados."\n<br>";
            }
            // dd($tmp);
        }

        
        /* Schema::create('tmp_estadisticas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_jugador');
            $table->string('jugador');
            $table->unsignedBigInteger('id_equipo');
            $table->string('equipo');
            $table->string('id_estadistica');
            $table->double('valor', 6, 3);
            
        }); */


        // Schema::drop('tmp_estadisticas');
        

        // dd($data);
        return $data;
    }
}
