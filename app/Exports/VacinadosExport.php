<?php

namespace App\Exports;

use App\Vacinado;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VacinadosExport implements FromQuery, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    public function __construct(Request $request)
    {
        $this->where = function($q) use($request){
            $data = $request->all();

            if(isset($data["pais"]) && $data["pais"]){
                $q->where("pais", $data["pais"]);
            }

            if(isset($data["vacinado"]) && $data["vacinado"]){
                $q->where("vacinado", $data["vacinado"]);
            }

            if(isset($data["assintomatico"]) && $data["assintomatico"]){
                $q->where("assintomatico", $data["assintomatico"]);
            }

            if(isset($data["infectado"]) && $data["infectado"]){
                $q->where("infectado", $data["infectado"]);
            }

            if(isset($data["bebida"]) && $data["bebida"]){
                $q->where("bebida", $data["bebida"]);
            }

            if(isset($data['curso']) && $data['curso']){
                $q->where('curso', $data['curso']);
            }

            if(isset($data['turma']) && $data['turma']){
                $q->where('turma', $data['turma']);
            }

            if(isset($data['turno']) && $data['turno']){
                $q->where('turno', $data['turno']);
            }

            if(isset($data["user_id"]) && $data["user_id"]){
                $q->where("user_id", $data["user_id"]);
            }

            if(isset($data["sexo"]) && $data["sexo"]){
                $q->where("sexo", $data["sexo"]);
            }

            if (isset($data['data_in']) && $data['data_in']) {
                $dataInicio = Carbon::createFromFormat('d/m/Y', $data['data_in'])->setTime(0, 0, 0)->format('Y-m-d H:i:s');
                $q->where('created_at', '>=', $dataInicio);
            }

            if (isset($data['data_fim']) && $data['data_fim']) {
                $dataFim = Carbon::createFromFormat('d/m/Y', $data['data_fim'])->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                $q->where('created_at', '<=', $dataFim);
            }
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'NOME',
            'IDADE',
            'SEXO',
            'CPF',
            'VACINADO',
            'PAIS',
            'ASSINTOMATICO',
            'INFECTADO',
            'BEBIDA',
            'EMAIL',
            'CONTATO'
        ];
    }

    public function query()
    {
        return Vacinado::select(
            'id',
            'nome',
            'idade', 
            'sexo', 
            'cpf',
            'vacinado',
            'pais', 
            'assintomatico', 
            'infectado', 
            'bebida',
            'email',
            'contato',
        )->where($this->where);
    }
}
