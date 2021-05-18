@extends('inicio.plantilla')

@section('contenido')
<div class="row">

    <!-- COLUMNA 1  -->
    <div class="col-sm-2 col-md-4 col-lg-2 d-none d-lg-block">

        <div class="score-block round-block">
            <div class="d-flex flex-row scores-dates">
                <div class="flex-fill text-center">
                    <div class="dayname">THU</div>
                    <div class="date">APR 1</div>
                </div>
                <div class="flex-fill text-center">
                    <div class="dayname">FRI</div>
                    <div class="date">APR 2</div>
                </div>
            </div>
        </div>

        <div class="score-block">
            <div class="d-flex flex-row scores-dates">
                <div class="flex-fill text-center">
                    <div class="dayname">THU</div>
                    <div class="date">APR 1</div>
                </div>
                <div class="flex-fill text-center">
                    <div class="dayname">FRI</div>
                    <div class="date">APR 2</div>
                </div>
            </div>
        </div>

   <!--  <div class="score-block">
        <div class="scores">
            <div class="bd-highlight title">
                Final
            </div>
            <div class="row">
                <div class="col-7">
                    <div class="d-flex align-items-center justify-content-between bd-highlight">
                        <div class="text-center logo">
                            <img src="img/31d.png" class="img-fluid">
                        </div>
                        <div class="team-name">
                            Team 1
                        </div>
                        <div class="team-info">
                            <div class="score text-center">
                                3
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between bd-highlight">
                        <div class="text-center logo">
                            <img src="img/Los_Angeles_Dodgers_Logo.png" class="img-fluid">
                        </div>
                        <div class="team-name align-self-center">
                            Team 2
                        </div>
                        <div class="team-info">
                            <div class="score text-center">
                                9
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 d-flex info-container">
                    <div class="align-self-center score-info">
                        1:05 PM
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- <div class="score-block">
        <div class="scores">
            <div class="bd-highlight title">
                Final
            </div>
            <div class="row">
                <div class="col-7">
                <div class="d-flex align-items-center justify-content-between bd-highlight">
                    <div class="text-center logo">
                    <img src="img/Los_Angeles_Dodgers_Logo.png" class="img-fluid">
                    </div>
                    <div class="team-name">
                    Team 1
                    </div>
                    <div class="team-info">
                    <div class="team-record text-center">
                        5-5
                    </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between bd-highlight">
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
                </div>
                </div>
                <div class="col-5 d-flex info-container">
                    <div class="align-self-center score-info">
                        1:05 PM
                    </div>
                </div>
            </div>
        </div>
    </div> -->


        @include('scores.scores')
        
    </div>
    <!-- COLUMNA 1  -->

    <!-- COLUMNA 2  -->
    <div class="col-sm-12 col-md-12 col-lg-7 pl-1 pr-1">
    
        <div class="row">
            <div class="col-12">
            <img src="img/img_3.jpg" class="img-fluid"/>
            </div>
        </div>

        <div class="container contenedor">
            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-sm-7">
                <div class="articulo">
                <div class="titulo">
                    Título del artículo
                </div>
                <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, animi?
                </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="nota">
                <div class="nota-titulo">RELACIONADO</div>
                <div class="nota-contenido">
                    <ul>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    </ul>
                </div>
                </div>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-12">
                <div class="contenedor-articulo">
                <div class="articulo">
                    <img src="img/31d.jpg" class="img-fluid"/>
                    <div class="titulo">
                    Título del artículo
                    </div>
                    <div class="contenido articulo-contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam reiciendis accusantium vitae neque praesentium numquam temporibus porro similique, aliquid dolores?
                    </div>
                </div>
                </div>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-12">
                <div class="contenedor-articulo">
                <div class="articulo">
                    <div class="embed-responsive embed-responsive-16by9">
                    <iframe width="765" height="430" src="https://www.youtube.com/embed/u4l4VwAf5I8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="titulo">
                    Título del artículo
                    </div>
                    <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam reiciendis accusantium vitae neque praesentium numquam temporibus porro similique, aliquid dolores?
                    </div>
                </div>
                </div>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-sm-7">
                <div class="embed-responsive embed-responsive-16by9">
                <iframe width="765" height="430" src="https://www.youtube.com/embed/u4l4VwAf5I8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            
            <div class="col-sm-5">
                <div class="contenedor-articulo">
                <div class="articulo">
                    <div class="titulo">
                    Título del artículo
                    </div>
                    <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam reiciendis accusantium vitae neque praesentium numquam temporibus porro similique, aliquid dolores?
                    </div>
                </div>
                </div>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-7">
                <div class="articulo">
                <div class="titulo">
                    Título del artículo
                </div>
                <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, animi?
                </div>
                </div>
            </div>
            <div class="col-5">
                <img src="img/cobras.jpg" class="img-fluid rounded"/>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-7">
                <div class="articulo">
                <div class="titulo">
                    Título del artículo
                </div>
                <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, animi?
                </div>
                </div>
            </div>
            <div class="col-5">
                <img src="img/31d.jpg" class="img-fluid rounded"/>
            </div>
            </div>

            <div class="row pb-3 mb-3 border-bottom">
            <div class="col-7">
                <div class="articulo">
                <div class="titulo">
                    Título del artículo
                </div>
                <div class="contenido">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, animi?
                </div>
                </div>
            </div>
            <div class="col-5">
                <img src="img/picudos.jpg" class="img-fluid rounded"/>
            </div>
            </div>
        </div>
    </div>
    <!-- COLUMNA 2  -->


    <!-- COLUMNA 3  -->
    <div class="col-sm-3 col-md-12 col-lg-3">
        <div class="row">
            <div class="col-12">
                <div class="container round-block contenedor-noticia">
                    <div class="info">
                        <div class="titulo">
                            ÚLTIMAS NOTICIAS
                        </div>
                        <div class="contenido">
                            <ul>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="info">
                        <div class="titulo">
                            TEMPORADA 2021
                        </div>
                        <div class="contenido">
                            <ul>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            <li>Lorem ipsum dolor sit amet.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
            <div class="container round-block contenedor-noticia">
                <div class="info">
                <div class="titulo">
                    LÍDERES DE LIGA
                </div>
                <div class="contenido">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="batting-tab" data-toggle="tab" href="#batting" role="tab" aria-controls="batting" aria-selected="true">BATEO</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="pitching-tab" data-toggle="tab" href="#pitching" role="tab" aria-controls="pitching" aria-selected="false">PICHEO</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="batting" role="tabpanel" aria-labelledby="batting-tab">
                        <div class="tab-pane fade show active" id="batting" role="tabpanel" aria-labelledby="batting-tab">
                            <div class="container-fluid p-1">

                                @if(sizeof($lideres_bateo))

                                    @foreach($lideres_bateo as $k => $row)

                                    <div class="row mb-2">
                                        <div class="col-3">
                                            <img src="img/kershaw.png" class="img-fluid" />
                                        </div>
                                        <div class="col-9 border-bottom pb-1">
                                            <div class="row">
                                                <div class="col-8 p-1">
                                                    <div class="lider-estadistica">{{$row['estadistica']}}</div>
                                                    <div class="lider-nombre">{{$row['jugador']['jugador']}}</div>
                                                    <div class="lider-equipo">{{$row['jugador']['equipo']}}</div>
                                                    <!-- <div class="lider-equipo">LA &#8226; CF</div> -->
                                                </div>
                                                <div class="col-4 d-flex align-items-end flex-column p-1">
                                                    <div class="lider-estadistica-valor">{{$row['jugador']['valor']}}</div>
                                                    <div class="lider-estadistica text-uppercase">{{$k}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endforeach

                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pitching" role="tabpanel" aria-labelledby="pitching-tab">
                    <div class="container-fluid p-1">
                        <div class="row mb-2">
                        <div class="col-3">
                            <img src="img/kershaw.png" class="img-fluid" />
                        </div>
                        <div class="col-9 border-bottom pb-1">
                            <div class="row">
                            <div class="col-8 p-1">
                                <div class="lider-estadistica">Carreras Permitidas</div>
                                <div class="lider-nombre">LA &#8226; CF</div>
                            </div>
                            <div class="col-4 d-flex align-items-end flex-column p-1">
                                <div class="lider-estadistica-valor">0.00</div>
                                <div class="lider-estadistica">AVG</div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <div class="row mb-2">
                        <div class="col-3">
                            <img src="img/kershaw.png" class="img-fluid rounded-circle" />
                        </div>
                        <div class="col-9 border-bottom pb-1">
                            <div class="row">
                            <div class="col-8 p-1">
                                <div class="lider-estadistica">Salvados</div>
                                <div class="lider-info">25 Empatados</div>
                            </div>
                            <div class="col-4 d-flex align-items-end flex-column p-1">
                                <div class="lider-estadistica-valor">1</div>
                                <div class="lider-estadistica">SV</div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
            <div class="container round-block contenedor-noticia">
                <div class="info">
                <div class="titulo">
                    TEMPORADA 2021
                </div>
                <div class="contenido">
                    <ul>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    <li>Lorem ipsum dolor sit amet.</li>
                    </ul>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
            <div class="container round-block contenedor-noticia">
                <div class="info">
                <div class="titulo">
                    FOTO DEL DÍA
                </div>
                <img src="img/suterm.jpg" class="img-fluid rounded"/>
                <div class="contenido">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit, dolor.
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- COLUMNA 3  -->
    </div>
@stop