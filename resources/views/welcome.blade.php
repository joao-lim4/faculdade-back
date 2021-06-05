<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
</head>
<body>
<div style="max-width: 600px;background-color: #fafafa;position: absolute;left: 50%;top:0%;transform: translate(-50%,0%);" class="container">
        <div class="main" style=" width: 100%;height: 100%;">
            <div class="img-box" style="width: 100%;">
                <img src="http://localhost:8000/assets/header.png" alt="background-img"/>
            </div>
            <div  class="text" style=" width: 100%;text-align: center;">
                <div style="width: 100%;height: 40px;">
                    <h1 style="color: #00AEEF;letter-spacing: 1px;font-size: 45px;">Fanfic Bienal</h1>                </div>
                </div>
                <div style="width: 60%;height: 1px;opacity: .6;background-color: #5a5a5a5c; margin-left: 20%;margin-top: 3%;"></div>
                <div style="width: 90%;margin-top: 5%;margin-left: 5% ;text-align: center;">
                    <p style="opacity: 0.7;;color:#2c2c2c;font-family:'Nunito',Arial,sans-serif;font-weight: bold;letter-spacing: 1px;">Olá {{$participante->nome}}, seja muito bem-vindo a plataforma da Fanfic, a Bienal Mineira do livro agradece a sua inscrição!</p>
                    <p style="opacity: 0.7;;color:#2c2c2c;font-family:'Nunito',Arial,sans-serif;font-weight: bold;letter-spacing: 1px;">Só mais um lembrete em {{$participante->nome}}, prometemos que é rapido kk<br>A primeira fase da fanfic ficara diponivel durante 15 dias então corra para se inscrever em varios autores!!<br>Uma dica, ja vai fazendo a sua fanfic pois em breve teremos novas informações!!</bn></p>
                </div>
                <div style="width: 60%;height: 1px;opacity: .6;background-color: #5a5a5a5c; margin-left: 20%;margin-top: 4%;"></div>
                <div>
                    <img style="height:185px" src="https://i.gifer.com/XwHw.gif">
                </div>
                <div style="display: flex; height: 200px; width: 75%; background-color: #ffffff;margin-left: 12.5%;border: .5px solid #00000014;border-radius: 25px;">
                    <div style="border-radius: 50%;overflow: hidden;;width: 100px;text-align: center;height: 100px;background-color: #00aeef1f;margin: 46px 0 0 20px;">
                        <img style="height: 95px;width: 95px;margin-top: 3px;border-radius: 50%;" src="{{$participante->path}}" alt="img perfil"/>
                    </div>
                    <div style="padding: 66px 0 0 15px;" >
                        <span style="font-family:'Nunito',Arial,sans-serif;color: #00AEEF;font-size: 15px;font-weight: bold;letter-spacing: 1px;">{{$participante->nome}}</span>
                        <br/>
                        <span style="font-family:'Nunito',Arial,sans-serif; font-weight: bold;font-size: 9px;letter-spacing: 1px;opacity: 0.6;">participante | FanFic Bienal</span>
                        <br/>
                        <span style="font-family:'Nunito',Arial,sans-serif;"><a href="https://fanficbienal.com.br/#!/" style="color: #000000;font-size: 12px;text-decoration: none;font-weight: bold;opacity: 0.5;letter-spacing: 1px;">Inscreva-se no seu primeiro autor aqui</a></span>
                    </div>
                    <div style="padding: 25px 0 0 0px;">
                        <div style="width: 51px;height: 51px;background: #00aeef57;border-radius: 50%;">
                            <div style="width: 50px;height: 50px;overflow: hidden;background: #ffffff;border-radius: 50%;">
                                <img style="width: 38px;height: 52px;margin-left: 10%;" src="./+voce+fanfic+voce+na+fanfic.svg" alt="fanfic">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 100%;height: 160px;position: absolute; background-color: #4F4B69;margin-top: 15%;">
                    <div style="padding-top: 45px;margin-left: 50px;" >
                        <span>
                            <a style="text-decoration: none;color: #fafafa;font-family:'Nunito',Arial,sans-serif;font-size: 15px;font-weight: bold;opacity: 0.8;" href="#" target="_blank">Termos de uso</a>
                        </span>
                        <br/>
                        <span>
                            <a style="text-decoration: none;color: #fafafa;font-family:'Nunito',Arial,sans-serif;font-size: 15px;font-weight: bold;opacity: 0.8;" href="#" target="_blank" >contato@asasproducoes.com.br</a>
                        </span>
                        <br/>
                        <span>
                            <a  style="text-decoration: none;color: #fafafa;font-family:'Nunito',Arial,sans-serif;font-size: 15px;font-weight: bold;opacity: 0.8;" href="https://www.instagram.com/bienalmineiradolivro/" target="_blank"><img style="    height: 13px;margin-right: 2px;" src="http://localhost:8000/assets/loginsta.png">@bienalmineiradolivro</a>
                        </span>
                         <img src="http://localhost:8000/assets/g(10).svg" alt="bienal" style="float: right;margin-right: 50px;margin-top: -75px;">

                    </div>
                </div>
            </div>
        </div>
</div>
</body>
</html>