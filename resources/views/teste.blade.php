<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
</head>
<body>
<div style="max-width: 600px;background-color: #fafafa;position: absolute;left: 50%;top:0%;transform: translate(-50%,0%);" class="container">
        <div class="main" style=" width: 100%;height: 100%;">
            <div class="img-box" style=" width: 100%;">
                <img src="http://localhost:8000/assets/header.png" alt="background-img"/>
            </div>
            <div  class="text" style=" width: 100%;font-family:'Nunito',Arial,sans-serif, monospace;text-align: center;">
                <div style="width: 100%;height: 40px;">
                    <h1 style="color: #00AEEF;letter-spacing: 1px;font-size: 45px;">Fanfic Bienal</h1>                </div>
                </div>
                <div style="width: 60%;height: 1px;opacity: .6;background-color: #5a5a5a5c; margin-left: 20%;margin-top: 3%;"></div>
                <div style="width: 90%;margin-top: 5%;margin-left: 5% ;text-align: center;">
                    <p style="opacity: 0.7;;color:#2c2c2c;font-family:'Nunito',Arial,sans-serif;font-weight: bold;letter-spacing: 1px;">Olá {{ $nome }}, você solicitou esse email para efetuar a troca da sua senha, segue o link abaixo para continuar o processo!! <br> E não se esqueça a Fanfic Bienal te deseja uma boa sorte!!</p>
                </div>
                <div style="width: 60%;height: 1px;opacity: .6;background-color: #5a5a5a5c; margin-left: 20%;margin-top: 4%;"></div>
                <div style="width: 100%;margin-top: 10%;text-align: center;">
                    <img src="http://localhost:8000/assets/protecao.png" alt="proteção"/>
                    <br/>
                    <p style="color: #0fb600;font-family:'Nunito',Arial,sans-serif;font-weight: bold;opacity: 0.8; letter-spacing: .5px;">Não se esqueça, suas informações estão seguras com a gente!</p>
                    <span style="color: #2c2c2c;font-family:'Nunito',Arial,sans-serif;">Link: <a style="text-decoration: none;color: #00AEEF;" href="https://fanficbienal.com.br/#!/changePassword/{{ $key }}" target="_blank">FanFic Bienal</a></span>
                    <br/>
                    <h1 style="font-size: 12px;color: #2c2c2ca6;font-family:'Nunito',Arial,sans-serif;margin-left:7.5%; width: 85%;">Este link é valido somente em um prazo de 12 horas, então corra para trocar a sua senha para poder fazer as suas Fanfics!</h1>
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