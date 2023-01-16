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
        // $somme_poids = 0;
        // foreach ($request->champs as $champ) {
        //     $somme_poids = $somme_poids + $champ['poids'];
        // }
        // if ($somme_poids != 100) {
        //     return response()->json(
        //         'la somme des poids doit être egale à 100',
        //         400
        //     );
        // }

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
    public function calculResultat(Request $request)
    {
        $resultat = 0;
        for ($i = 0; $i < sizeof($request->all()); $i++) {
            if (
                ($request[$i]['champ_type'] == 0 ||
                    $request[$i]['champ_type'] == 1 ||
                    $request[$i]['champ_type'] == 5 ||
                    $request[$i]['champ_type'] == 6 ||
                    $request[$i]['champ_type'] == 7 ||
                    $request[$i]['champ_type'] == 8) &&
                $request[$i]['value']
            ) {
                $resultat = $resultat + $request[$i]['champ_poids'];
            } elseif (
                $request[$i]['champ_type'] == 3 &&
                $request[$i]['value']
            ) {
                $max = DB::select(
                    'select max(poids) x from items where champ_id = ?',
                    [$request[$i]['champ_id']]
                )[0]->x;
                $resultat =
                    $resultat +
                    (DB::select('select poids from items where id = ?', [
                        $request[$i]['value'],
                    ])[0]->poids /
                        $max) *
                        $request[$i]['champ_poids'];
            } elseif (
                $request[$i]['champ_type'] == 2 &&
                $request[$i]['value']
            ) {
                $max = DB::select(
                    'select max(poids) x from formules where champ_id = ?',
                    [$request[$i]['champ_id']]
                )[0]->x;
                $formules = Formule::where(
                    'champ_id',
                    $request[$i]['champ_id']
                )->get();
                $ds = (int) substr($request[$i]['value'], 6, 4);
                foreach ($formules as $formule) {
                    if (
                        $formule['type_id'] == 0 &&
                        $ds < (int) $formule['reference']
                    ) {
                        $resultat =
                            $resultat +
                            ($formule['poids'] / $max) *
                                $request[$i]['champ_poids'];
                    } elseif (
                        $formule['type_id'] == 1 &&
                        $ds > (int) $formule['reference']
                    ) {
                        $resultat =
                            $resultat +
                            ($formule['poids'] / $max) *
                                $request[$i]['champ_poids'];
                    } elseif (
                        $formule['type_id'] == 2 &&
                        $ds >= (int) substr($formule['reference'], 0, 4) &&
                        $ds <= (int) substr($formule['reference'], 5, 4)
                    ) {
                        $resultat =
                            $resultat +
                            ($formule['poids'] / $max) *
                                $request[$i]['champ_poids'];
                    }
                }
            } elseif (
                $request[$i]['champ_type'] == 4 &&
                sizeof($request[$i]['value']) != 0 &&
                $request[$i]['value']
            ) {
                $som = 0;
                $max = DB::select(
                    'select sum(poids) x from items where champ_id = ?',
                    [$request[$i]['champ_id']]
                )[0]->x;
                foreach ($request[$i]['value'] as $item) {
                    $som =
                        $som +
                        DB::select('select poids x from items where id = ?', [
                            $item,
                        ])[0]->x;
                }
                $resultat =
                    $resultat + ($som / $max) * $request[$i]['champ_poids'];
            }
        }
        return response()->json($resultat);
    }
}
