<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index(Request $request) {

        $rick = new Request();

        $fecha = date('Y-m-d');

        $id_temporada = 5;

        $rick->replace([
            'id_temporada' => $id_temporada,
            'mod_op' => 'partidos_semana',
            'fecha' => $fecha,
        ]);

        $partidos = app(CalendarioController::class)->scores($rick);
        
        $rick['mod_op'] = 'lideres';
        $rick['id_tipo_estadistica'] = 'bateo';
        $rick['turnos_oficiales'] = 1;

        $lideres_bateo = app(LiderController::class)->lideres($rick);

        // dd($partidos);

        if(sizeof($partidos)) {

            foreach($partidos as $k => $row) {

                $partidos[$k]->equipos = collect([]);

                // dd($row->score[0]->r);
                $rv = isset($row->score[0]->r) ? $row->score[0]->r : null;
                $hv = isset($row->score[0]->h) ? $row->score[0]->h : null;
                $ev = isset($row->score[0]->e) ? $row->score[0]->e : null;

                $rl = isset($row->score[1]->r) ? $row->score[1]->r : null;
                $hl = isset($row->score[1]->h) ? $row->score[1]->h : null;
                $el = isset($row->score[1]->e) ? $row->score[1]->e : null;

                $partidos[$k]->equipos->push( 
                    (Object) [
                        'id' => $row->id_visita,
                        'equipo' => $row->visita,
                        'equipo_corto' => $row->visita_corto,
                        'equipo_abreviacion' => $row->visita_abreviacion,
                        'r' => $rv,
                        'h' => $hv,
                        'e' => $ev,
                    ],
                    (Object) [
                        'id' => $row->id_local,
                        'equipo' => $row->local,
                        'equipo_corto' => $row->local_corto,
                        'equipo_abreviacion' => $row->local_abreviacion,
                        'r' => $rl,
                        'h' => $hl,
                        'e' => $el,
                    ]
                );

                // dd($partidos[$k]->equipos);

                foreach($partidos[$k]->equipos as $k_ => $row_) {

                    $rick->replace([
                        'id_temporada' => $row->id_temporada,
                        'id_equipo' => $row_->id,
                        'f_partido' => $row->f_partido,
                    ]);
                    
                    $tmp = app(StandingController::class)->getData($rick);
    
                    if(sizeof($tmp)) {
    
                        $partidos[$k]->equipos[$k_]->win = $tmp[0]->win;
                        $partidos[$k]->equipos[$k_]->lose = $tmp[0]->lose;
                        $partidos[$k]->equipos[$k_]->record = $tmp[0]->win."-".$tmp[0]->lose;
                    }
                }
            }
        }

        // dd($partidos);

        return view('inicio.principal', compact('partidos','fecha','lideres_bateo'));
    }
}
