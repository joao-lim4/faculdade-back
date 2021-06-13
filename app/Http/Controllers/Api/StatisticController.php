<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Vacinado;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class StatisticController extends Controller
{   
    private function getDate(){
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d/m/y h:i:s', time());
        $ex = explode(" ", $date);
        return [
            "day" =>  $ex[0],
            "hora" =>  $ex[1]
        ];
    }

    private function variacaoPercentual(int $v1, int $v2){
        if($v1 > $v2){
            if($v1 != 0 && $v2 == 0){
                return 100;
            }else if($v1 == 0){
                return 0;
            }else{
                return (($v1 - $v2) / $v1) * 100;
            }
            
            
        }else{
            if($v2 != 0 && $v1 == 0){
                return 100;
            }else if($v2 == 0){
                return 0;
            }else{
                return (($v2 - $v1) / $v1) * 100;
            }
        }
    }

    public function smallTasks(){

        $total_vacinados = Vacinado::where('id', '!=', 0)->count();
        $total_users = User::where("id", "!=", 0)->count();
        
        
        $generate_data = explode("/", $this->getDate()["day"]);
        $date = [
            "dia" => strVal($generate_data[0] == 1 ? 1 : $generate_data[0] - 1),
            "mes" => $generate_data[1],
            "ano" => $generate_data[2]  
        ];
        
        $total_vacinados_hoje = Vacinado::where('created_at', 'like', '%' . $generate_data[2] . '-' . $generate_data[1] . '-' . $generate_data[0] . '%')->count();
        
        $total_vacinados_otem = Vacinado::where('created_at', 'like', '%' . $date["ano"] . '-' . $date["mes"] . '-' . $date["dia"] . '%')->count();

        $values_label_vacinados = [];

        for($i = 1; $i <= 6; $i++){
            $newDate = [
                "dia" => strVal($generate_data[0] == 1 ? 1 : $generate_data[0] - $i),
                "mes" => $generate_data[1],
                "ano" => $generate_data[2]  
            ];
        
            $date_autal = Carbon::createFromFormat('d/m/Y', "" . $newDate["dia"] . "/" . $newDate["mes"] . "/" . $newDate["ano"] . "")->setTime(0, 0, 0)->format('Y-m-d H:i:s');
            $total_vacinados_dia = Vacinado::where('created_at', $date_autal)->count();

            $values_label_vacinados[] = $total_vacinados_dia;
        }


        return Response::json([
            "success" => true,
            "log" => false,
            "message" => "SmallTask Pesquisada com sucesso!",
            "data" => [
                "total_vacinados" =>  [
                    "label" => "Vacinados",
                    "value" => "" . $total_vacinados . "",
                    "percentage" => "100%",
                    "increase" => true,
                    "chartLabels" => [null, null, null, null, null, null, null],
                    "attrs" => [ "md" => "6", "sm" => "6" ],
                    "datasets" => [
                        [
                            "label" => "Vacinados",
                            "fill" => "start",
                            "borderWidth" => 1.5,
                            "backgroundColor" => "rgba(23,198,113,0.1)",
                            "borderColor" => "rgb(23,198,113)",
                            "data" => [1, 2, 3, 3, 3, 4, 4]
                        ]
                    ]
                ],
                "total_vacinados_hoje" => [
                    "label" =>  "Dia atual",
                    "value" =>  "" . $total_vacinados_hoje . "",
                    "percentage" =>  $this->variacaoPercentual($total_vacinados_hoje, $total_vacinados_otem) . "%",
                    "increase" =>  $total_vacinados_hoje > $total_vacinados_otem ? true : false,
                    "chartLabels" =>  [null, null],
                    "attrs" =>  [ "md" =>  "6", "sm" =>  "6" ],
                    "datasets" =>  [
                        [
                            "label" =>  "Dia atual",
                            "fill" =>  "start",
                            "borderWidth" =>  1.5,
                            "backgroundColor" =>  $total_vacinados_otem < $total_vacinados_hoje ? "rgba(23,198,113,0.1)" : "rgba(255,65,105,0.1)",
                            "borderColor" =>  $total_vacinados_otem < $total_vacinados_hoje ? "rgb(23,198,113)" : "rgb(255,65,105)",
                            "data" => [$total_vacinados_otem, $total_vacinados_hoje]
                        ]
                    ]
                ],
                "total_users_otem" => [
                    "label" =>  "Dia anterior",
                    "value" =>  "" . $total_vacinados_otem . "",
                    "percentage" => $this->variacaoPercentual($total_vacinados_otem,$total_vacinados_hoje) . "%",
                    "increase" =>  $total_vacinados_otem <= $total_vacinados_hoje ? false : true,
                    "chartLabels" =>  [null, null,],
                    "attrs" =>  [ "md" =>  "6", "sm" =>  "6" ],
                    "datasets" =>  [
                        [
                            "label" =>  "Dia anterior",
                            "fill" =>  "start",
                            "borderWidth" =>  1.5,
                            "backgroundColor" =>   $total_vacinados_otem > $total_vacinados_hoje ? "rgba(23,198,113,0.1)" : "rgba(255,65,105,0.1)",
                            "borderColor" =>  $total_vacinados_otem > $total_vacinados_hoje ? "rgb(23,198,113)" : "rgb(255,65,105)",
                            "data" =>  [$total_vacinados_hoje, $total_vacinados_otem,]
                        ]
                    ]
                ],
                "total_users" =>  [
                    "label" => "Usuarios",
                    "value" => "" . $total_users . "",
                    "percentage" => "100%",
                    "increase" => true,
                    "chartLabels" => [null, null, null, null, null, null, null],
                    "attrs" => [ "md" => "6", "sm" => "6" ],
                    "datasets" => [
                        [
                            "label" => "Usuarios",
                            "fill" => "start",
                            "borderWidth" => 1.5,
                            "backgroundColor" => "rgba(23,198,113,0.1)",
                            "borderColor" => "rgb(23,198,113)",
                            "data" => [1, 2, 3, 3, 3, 4, 4]
                        ]
                    ]
                ],
                "total_docs" =>  [
                    "label" => "Documentos",
                    "value" => "0",
                    "percentage" => "0%",
                    "increase" => false,
                    "chartLabels" => [null, null, null, null, null, null, null],
                    "attrs" => [ "md" => "6", "sm" => "6" ],
                    "datasets" => [
                        [
                            "label" => "Docuemntos",
                            "fill" => "start",
                            "borderWidth" => 1.5,
                            "backgroundColor" => "rgba(23,198,113,0.1)",
                            "borderColor" => "rgb(23,198,113)",
                            "data" => [0, 0, 0, 0, 0, 0, 0]
                        ]
                    ]
                ],
            ]
        ], 200);
    }


    public function getMouthVacinados(){

        
        $vacinados_br = Vacinado::where("pais", "Brasil")->count();
        $vacinados_outros = Vacinado::where("pais", "!=", "Brasil")->count();  
     
        return Response::json([
            "success" => true,
            "log" => false,
            "message" => "Sucesso!",
            "data" => [
                "chartData" => [    
                    "labels" => [""],
                    "datasets" => [
                        [
                            "label" => "Basil",
                            "fill" => "start",
                            "data" => [
                                $vacinados_br,
                            ],
                            "backgroundColor" => "rgba(0,123,255,0.1)",
                            "borderColor" => "rgba(0,123,255,1)",
                            "pointBackgroundColor" => "#ffffff",
                            "pointHoverBackgroundColor" => "rgb(0,123,255)",
                            "borderWidth" => 1.5,
                            "pointRadius" => 0,
                            "pointHoverRadius" => 3
                        ],
                        [
                            "label" => "Outros",
                            "fill" => "start",
                            "data" => [
                                $vacinados_outros
                            ],
                            "backgroundColor" => "rgba(255,65,105,0.1)",
                            "borderColor" => "rgba(255,65,105,1)",
                            "pointBackgroundColor" => "#ffffff",
                            "pointHoverBackgroundColor" => "rgba(255,65,105,1)",
                            "borderDash" => [3, 3],
                            "borderWidth" => 1,
                            "pointRadius" => 0,
                            "pointHoverRadius" => 2,
                            "pointBorderColor" => "rgba(255,65,105,1)"
                        ]
                    ],
                ]
            ]
        ], 200);
    }


    public function getUltimosVacinados(){

        $users = Vacinado::where("id", "!=", 0)->orderBy("id", "desc")->limit(3)->get();

        return Response::json([
            "success" => true,
            "log" => false,
            "message" => "Vacinados pesquisados!",
            "data" => [
                "vacinados" => $users
            ]
        ], 200);

    }
}
