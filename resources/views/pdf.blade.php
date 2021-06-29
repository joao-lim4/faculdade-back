<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width">
    <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">
    <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title>
    <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700|Lato:300,400,700" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style>
    

/*//////////////////////////////////////////////////////////////////
[ FONT ]*/

            * {
                margin: 0px; 
                padding: 0px; 
                box-sizing: border-box;
            }

            body, html {
                height: 100%;
                font-family: sans-serif;
            }



            /*//////////////////////////////////////////////////////////////////
            [ Table ]*/

            .table {
                width: 100%;
                display: table;
                margin: 0;
            }

            @media screen and (max-width: 768px) {
                .table {
                    display: block;
                }
            }

            .row {
                display: table-row;
                background: #fff;
            }

            .row.header {
                color: #ffffff;
                background-color: #000000;
            }

            @media screen and (max-width: 768px) {
            .row {
                display: block;
            }

            .row.header {
                padding: 0;
                height: 0px;
            }

            .row.header .cell {
                display: none;
            }

            .row .cell:before {
                font-family: 'Josefin Sans', sans-serif;
                font-size: 12px;
                color: #808080;
                line-height: 1.2;
                text-transform: uppercase;
                font-weight: unset !important;
                margin-bottom: 13px;
                min-width: 98px;
                display: block;
            }
            }

            .cell {
                display: table-cell;
            }

            @media screen and (max-width: 768px) {
                .cell {
                    display: block;
                }
            }

            .row .cell {
                font-family: 'Josefin Sans', sans-serif;
                font-size: 15px;
                color: #666666;
                line-height: 1.2;
                font-weight: unset !important;

                padding-top: 20px;
                padding-bottom: 20px;
                border-bottom: 1px solid #f2f2f2;
            }

            .row.header .cell {
                font-family: 'Josefin Sans', sans-serif;
                font-size: 18px;
                color: #fff;
                line-height: 1.2;
                font-weight: unset !important;

                padding-top: 19px;
                padding-bottom: 19px;
            }

            .row .cell:nth-child(1) {
                width: 360px;
                padding-left: 40px;
            }

            .row .cell:nth-child(2) {
                width: 160px;
            }

            .row .cell:nth-child(3) {
                width: 250px;
            }

            .row .cell:nth-child(4) {
                width: 190px;
            }


            .table, .row {
                width: 100% !important;
            }

            .row:hover {
                background-color: #ececff;
                cursor: pointer;
            }

            @media (max-width: 768px) {
            .row {
                border-bottom: 1px solid #f2f2f2;
                padding-bottom: 18px;
                padding-top: 30px;
                padding-right: 15px;
                margin: 0;
            }
            
            .row .cell {
                border: none;
                padding-left: 30px;
                padding-top: 16px;
                padding-bottom: 16px;
            }
            .row .cell:nth-child(1) {
                padding-left: 30px;
            }
            
            .row .cell {
                font-family: 'Josefin Sans', sans-serif;
                font-size: 18px;
                color: #555555;
                line-height: 1.2;
                font-weight: unset !important;
            }

            .table, .row, .cell {
                width: 100% !important;
            }
        }
    </style>
</head>

<body width="100%">
    <br />
    <br />
    <br />
    <br />
    <div class="heading-section" style="text-align: center; padding: 0 30px;">
        <h2 style="font-family: 'Josefin Sans', sans-serif;">LISTA DE USUARIOS</h2>
    </div>

    <div class="table">
        <div class="row header" style="background-color: #000000;">
            <div style="padding: 20px;" class="cell">
                NOME
            </div>
            <div class="cell">
                IDADE
            </div>
            <div class="cell">
                SEXO
            </div>
            <div class="cell">
                CPF
            </div>
            <div class="cell">
                VACINADO
            </div>
            <div class="cell">
                PAIS
            </div>
            <div class="cell">
                ASSINTOMATICO
            </div>
            <div class="cell">
                INFECTADO
            </div>
            <div class="cell">
                BEBIDA
            </div>
        </div>

        @foreach($vacinados_pdf as $vacinado)
            <div class="row">
                <div style="padding: 20px;" class="cell">
                    {{ $vacinado->nome }}
                </div>
                <div class="cell">
                    {{ $vacinado->idade }}
                </div>
                <div class="cell">
                    {{ $vacinado->sexo }}
                </div>
                <div class="cell">
                   {{ $vacinado->cpf }}                
                </div>
                <div class="cell">
                    {{ $vacinado->vacinado }}
                </div>
                <div class="cell">
                    {{ $vacinado->pais }}
                </div>
                <div class="cell">
                    {{ $vacinado->assintomatico }}
                </div>
                <div class="cell">
                    {{ $vacinado->infectado }}
                </div>
                <div class="cell">
                    {{ $vacinado->bebida }}
                </div>
            </div>
        @endforeach

    </div>
</body>

</html>