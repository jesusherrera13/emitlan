<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Calendario;
use App\Models\Temporada;

use DataTables;
use Barryvdh\DomPDF\Facade as PDF;

class CalendarioController extends Controller
{
    public function index(Request $request) {
        
        $page_title = 'Calendario';
        $content_header = 'Calendario';
                
        $id_usuario = null;
        $id_usuario_sesion = null;
        $cmb_esquemas = [];
        $cmb_sistemas_competicion = [];
        $id_temporada_sesion = null;
        $temporadas = null;
        $equipos = [];

        if(Auth::user()) {
                        
            $id_usuario_sesion = Auth::user()->id;
            $id_usuario = Auth::user()->id;

            $rick = new Request();

            $rick->replace([
                'id_usuario' => $id_usuario,
                'mod_op' => 'get_temporadas',
                'dataType' => 'array',
            ]);
            
            // $temporadas = app(LibreriaController::class)->comboBoxView($rick);
            $temporadas = Temporada::where("id_usuario", Auth::user()->id);

            // $temporadas[''] = 'Temporadas';

            $f_inicio = date('Y-m-01');
            $f_fin = date('Y-m-d');
            
            // $rick['mod_op'] = 'get_equipos';

            // $equipos = app(LibreriaController::class)->comboBoxView($rick);

            $request['id_usuario'] = Auth::user()->id;

            $tmp = app(UserController::class)->usuarioTemporadas($request);

            if(sizeof($tmp)) {

                $id_usuario = $tmp['id_usuario'];
                $id_temporada_sesion = $tmp['id_temporada_sesion'];
                $temporadas = $tmp['TEMPORADAS'];
                
                $rick->replace([
                    'dataType' => 'array',
                    'id_usuario' => $id_usuario,
                    'id_temporada' => $id_temporada_sesion,
                    'mod_op' => 'get_temporada_equipos'
                ]);

                // $equipos = app(LibreriaController::class)->temporadasEquipos($rick);
                $equipos = app(TemporadaController::class)->equipos($rick);
            }
        }
        
        return view('calendario.inicio', 
            compact(
                'id_usuario',
                'id_usuario_sesion',
                'temporadas',
                'f_inicio',
                'f_fin',
                'id_temporada_sesion',
                'equipos',
                'page_title',
                'content_header'
            )
        );
        
    }

    public function getData(Request $request) {

        DB::statement("set @@lc_time_names = 'es_MX';");
        
        $tmp = DB::table("calendario as cal")
                    ->select(
                        DB::raw("date_add('".$request['fecha']."', interval -(dayofweek('".$request['fecha']."') - 2) day) as f_inicio"),
                        DB::raw("date_add(date_add('".$request['fecha']."', interval -(dayofweek('".$request['fecha']."') - 2) day),interval 6 day) as f_fin"),
                    )
                    ->limit(1)
                    ->get();

        $query = DB::table("calendario as cal")
                ->leftJoin("temporadas as temp", "temp.id", "cal.id_temporada")
                ->leftJoin("equipos as local", "local.id", "cal.id_local")
                ->leftJoin("equipos as visita", "visita.id", "cal.id_visita")
                ->leftJoin("temporadas_equipos as eqv", function($join) {

                    $join->on("eqv.id_equipo", "cal.id_visita");
                    $join->on("eqv.id_temporada", "cal.id_temporada");
                    $join->whereNull("eqv.deleted_at");
                })
                ->leftJoin("temporadas_equipos as eql", function($join) {

                    $join->on("eql.id_equipo", "cal.id_local");
                    $join->on("eql.id_temporada", "cal.id_temporada");
                    $join->whereNull("eql.deleted_at");
                })
                ->select(
                    "cal.id","cal.id_temporada","cal.no_vuelta","cal.no_programa","cal.no_jornada",
                    "cal.no_serie","cal.no_partido","cal.id_visita",
                    "cal.id_local","temp.descripcion as temporada",
                    "local.descripcion as local","local.descripcion_corta as local_corto","local.abreviacion as local_abreviacion",
                    "visita.descripcion as visita","visita.descripcion_corta as visita_corto","visita.abreviacion as visita_abreviacion",
                    "cal.id_status","cal.f_partido",
                    DB::raw("date_format(cal.f_partido, '%d/%m/%Y') as f_partido_"),
                    DB::raw("ifnull(cal.h_partido,'') as h_partido"),
                    DB::raw("concat(date_format(cal.h_partido, '%h:%i'),' ',if(cal.h_partido <='11:59','am','pm')) as h_partido_"),
                    "cal.no_entradas","cal.descanso","cal.interliga",
                    DB::raw("concat(dayname(cal.f_partido),', ',monthname(cal.f_partido),' ',date_format(cal.f_partido, '%d'),', ',date_format(cal.f_partido, '%Y')) as partido_fecha"),
                    DB::raw("null as division"),
                    DB::raw("if(eqv.id_division=eql.id_division,concat('División ',eql.id_division),null) as division"),
                    DB::raw("ELT(DATE_FORMAT(cal.f_partido,'%w') + 1,'Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') as dia"),
                )->whereNull("cal.deleted_at");

        if($request['id']) $query->where("cal.id", $request['id']);
        else if($request['id_partido']) $query->where("cal.id", $request['id_partido']);
        else if($request['mod_op'] == "get_serie_juegos" && $request['no_serie']) {

            $query->where("cal.id_temporada", $request['id_temporada']);
            $query->where("cal.no_serie", $request['no_serie']);
        }
        else {

            $query->where("cal.id_temporada", $request['id_temporada']);

            if($request['id_equipo']) {
                    
                $query->where(function($query) use ($request) {

                    $query->where('cal.id_visita', $request['id_equipo'])->orWhere('cal.id_local', $request['id_equipo']);
                });
            }

            if($request['mod_op'] == "get_serie_juegos") {

                $query->where("cal.no_programa", $request['no_programa']);
                $query->where("cal.no_serie", $request['no_serie']);
            }
            
            if($request['id_vista'] == "series") {

                $query->addSelect("cal.no_serie as numero");
                $query->addSelect(DB::raw("null as resultado"));
                $query->orderBy("cal.no_serie", "asc");
                $query->groupBy("cal.no_serie");

                if($request['no_serie']) $query->where("cal.no_serie", $request['no_serie']);

                if($request['f_inicio'] && $request['f_fin']) {

                    $query->whereBetween("cal.f_partido", [$request['f_inicio'], $request['f_fin']]);
                }
            }
            else {
                // dd('x');
                $query->addSelect('cal.no_partido as numero');
                $query->addSelect(
                    DB::raw("
                        if(
                            cal.id_status='GFIN',
                            concat(visita.abreviacion,' ',ifnull(scov.r,0),', ',local.abreviacion,' ',ifnull(scol.r,0)),
                            null
                        ) as resultado"
                    )
                );

                if($request['id_vista'] == "no_partido") {

                    $query->orderBy("cal.f_partido", 'asc');
                    $query->orderBy("cal.h_partido", 'asc');
                    $query->orderBy("cal.no_partido", 'asc');

                    if($request['f_inicio']) {
                    
                        if($request['f_fin']) $query->whereBetween("cal.f_partido", [$request['f_inicio'],$request['f_fin']]);
                        else $query->where("cal.f_partido", $request['f_inicio']);
                    }
                }
                else if($request['f_inicio'] && $request['f_fin']) {

                    $query->whereBetween("cal.f_partido", [$request['f_inicio'], $request['f_fin']]);
                }
                else $query->orderBy("cal.no_partido", 'asc');

                if($request['id_partido'])$query->where("cal.id", $request['id_partido']);
                if($request['no_serie'])$query->where("cal.no_serie", $request['no_serie']);

                $query->leftJoin('scores as scov', function($join) {

                    // $join->on('scov.id_temporada', 'cal.id_temporada');
                    $join->on('scov.id_partido', 'cal.id');
                    $join->on('scov.id_equipo', 'id_visita');
                });

                $query->leftJoin('scores as scol', function($join) {

                    // $join->on('scol.id_temporada', 'cal.id_temporada');
                    $join->on('scol.id_partido', 'cal.id');
                    $join->on('scol.id_equipo', 'id_local');
                });

                if($request['id_equipo']) {
                    
                    $query->where(function($query) use ($request) {

                        $query->where("cal.id_visita", $request['id_equipo'])->orWhere('cal.id_local', $request['id_equipo']);
                    });
                }

                if($request['fecha']) {

                    $query->whereBetween("cal.f_partido", [$tmp[0]->f_inicio, $tmp[0]->f_fin]);
                }
            }

            $query->addSelect(DB::raw("'".$request['id_vista']."' as tipo"));
        }
        
        // dd($query->toSql());

        $data = $query->get();

        // dd($data);

        return $data;
    }

    public function scores(Request $request) {

        // dd($request);

        $data = $this->getData($request);

        if(sizeof($data)) {

            foreach($data as $k => $row) {

                $rick = new Request();

                $rick->replace([
                    'id_partido' => $row->id,
                ]);

                $data[$k]->score = app(ScoreController::class)->getData($rick);
            }
        }

        // dd($data);
        if($request['data_mode'] == "view") return view('posiciones.posiciones', compact('data'));
        else return $data;
    }
    
    public function serverSideProcessing(Request $request) {

        if ($request->ajax()) {

            $data = $this->getData($request);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                            
                           $btn = '<i class="fas fa-baseball-ball btn-pin btn-gameday mr-1" iddb="'.$row->id.'"></i>';
                           $btn.= '<i class="fas fa-edit btn-editar btn-pin" iddb="'.$row->id.'"></i>';
       
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function impresion(Request $request) {

        // dd($request);

        /* 
        $tmp = app(LibreriaController::class)->usuarioTemporadas($request);

        if(sizeof($tmp)) {

            $id_usuario = $tmp['id_usuario'];
            // $id_temporada = $tmp['id_temporada_sesion'];
            $temporadas = $tmp['TEMPORADAS'];
        }
       
        $request['id_usuario'] = $id_usuario;
        $request['id_temporada'] = $id_temporada;  
        */

        // dd($request);

        // $row = Temporada::where("id", $request['id_temporada'])->get();

        $row = Temporada::where('id', $request['id_temporada'])->get();

        // dd($row);

        $tmp = $this->algoritmo($request);
        // $tmp = app(LibreriaController::class)->calendarioAlgoritmo($request);

        // dd($tmp);
        
        if(sizeof($tmp)) {

            $agrupado = 1;
            $vista = null;

            if($agrupado) $vista = 'calendario.impresion_jornada';
            else $vista = 'calendario.impresion';

            $tmp_ = $tmp;
            $tmp = [];

            foreach($tmp_ as $k_ => $row_) {

                if($agrupado) {

                    if(!isset($tmp[$row_->no_programa])) {

                        $tmp[$row_->no_programa] = (Object) [
                            'jornada' => 'Jornada '.$row_->no_programa,
                            'partidos' => []
                        ];
                    }

                    $tmp[$row_->no_programa]->partidos[] = (Object) $row_;

                    // dd($tmp);
                }
                else $tmp[] = (Object) $row_;
            }

            // dd($tmp);
        }

        $data = [
            'title' => 'Calendario ',
            'temporada' => $row[0]->descripcion,
            'fecha' => date('d/m/Y'),
            'hora' => date('h:i:s'),
            'data' => $tmp
        ];

        // dd($data);
          
        // $dompdf = PDF::loadView('usuarios.impresion', $data)->setPaper('a4', 'landscape');
        $dompdf = PDF::loadView($vista, $data);

        return $dompdf->stream('beisbol_calendario.pdf');
    }
}
