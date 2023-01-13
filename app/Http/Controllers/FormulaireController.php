<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Formulaire;
use App\Models\Champ;
use App\Models\Item;
use App\Models\Formule;
class FormulaireController extends Controller
{
    //
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'label' => 'required',
            'champs' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $form = Formulaire::create([
            // 'created_by' => Auth::id(),
            'label' => $request->label,
        ]);
        foreach ($request->champs as $champ) {
            $x = Champ::create([
                'formulaire_id' => $form->id,
                'type_id' => $champ['type'],
                'label' => $champ['label'],
                'poids' => $champ['poids'],
                'required' => $champ['required'],
            ]);
            if ($champ['type'] == 3 || $champ['type'] == 4) {
                foreach ($champ['items'] as $item) {
                    DB::insert(
                        'insert into items (champ_id,label,poids) values (?, ?, ?)',
                        [$x->id, $item['label'], $item['poids']]
                    );
                }
            }
            if ($champ['type'] == 2) {
                foreach ($champ['formules'] as $formule) {
                    DB::insert(
                        'insert into formules (champ_id,type_id,reference,poids) values (?, ?, ?, ?)',
                        [
                            $x->id,
                            $formule['type'],
                            $formule['date'],
                            $formule['poids'],
                        ]
                    );
                }
            }
        }
        return response()->json($form);
    }
    public function get($id)
    {
        $form = Formulaire::where('id', (int) $id)
            ->with([
                'champs' => function ($query) {
                    $query->with('items');
                    $query->with('formules');
                },
            ])
            ->get();

        return response()->json($form);
    }
}
