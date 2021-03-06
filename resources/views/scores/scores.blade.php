@if(sizeof($partidos))

    @foreach($partidos as $k => $row)

    <div class="score-block">
        <div class="scores">
            <div class="bd-highlight title game-status">
                @if($row->id_status == 'GFIN')
                    Final
                @elseif($row->id_status == 'GIPRG')
                    En progreso...
                @else
                    {{ $row->f_partido_ }}
                @endif
            </div>
            <div class="row">
                <div class="col-12">
                    @foreach($row->equipos as $k_ => $row_)
                    <div class="d-flex justify-content-start align-items-center bd-highlight">
                        <div class="p-1">
                            <img src="img/equipos/{{$row_->id}}/{{$row_->id}}.png" class="logo">
                        </div>
                        <div class="team-name p-1">
                            {{ $row_->equipo_corto }}
                        </div>
                        <div class="ml-auto p-1">
                            <div class="team-info">
                                @if($row->id_status == 'GFIN' || $row->id_status == 'GIPRG')
                                <div class="score text-center">
                                    {{ $row_->r }}
                                </div>
                                @else
                                <div class="team-record text-center">
                                    {{ $row_->record }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="text-center logo">
                            <img src="img/Los_Angeles_Dodgers_Logo.png" class="img-fluid">
                        </div>
                        <div class="team-name bg-primary">
                            <div>
                                {{ $row_->equipo_corto }}
                            </div>
                        </div>
                        <div class="team-info">
                            @if($row->id_status == 'GFIN')
                            <div class="score text-center">
                                {{ $row_->r }}
                            </div>
                            @else
                            <div class="team-record text-center">
                                {{ $row_->record }}
                            </div>
                            @endif
                        </div> -->
                    </div>
                    @endforeach
                    <!-- <div class="d-flex align-items-center justify-content-between bd-highlight">
                        <div class="text-center logo">
                            <img src="img/Los_Angeles_Dodgers_Logo.png" class="img-fluid">
                        </div>
                        <div class="team-name align-self-center">
                            Team 2
                        </div>
                        <div class="team-info">
                            <div class="team-record text-center">
                                5-5
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    @endforeach
@endif