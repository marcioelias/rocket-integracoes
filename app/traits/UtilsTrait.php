<?php

namespace App\traits;

use Illuminate\Database\Eloquent\Model;

trait UtilsTrait {
    public function destroyModel(Model $model) {
        try {
            return response()->json($model->delete());
        } catch (\Exception $e) {
            switch ($e->getCode()) {
                case 23000:
                    return response()
                        ->json(['message' => 'Exclusão falhou. Registro possui relacionamentos.'])
                        ->setStatusCode(500);
                    break;
                default:
                    return response()
                        ->json(['message' => 'Não foi possível excluir o registro.'])
                        ->setStatusCode(500);
                    break;
            }
        }
    }
}
