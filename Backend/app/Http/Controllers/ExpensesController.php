<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

Carbon::setLocale('es');

class ExpensesController extends Controller
{
    public function index()
    {
        $expense = Expense::all();

        if ($expense->isEmpty()) {
            return response()->json(['message'=>'No hay gastos registrados']);
        }else {
            return response()->json([$expense,200]);
        }
        
    }
    
    public function store(Request $request)
    {
        //** validar datos
        $validator = validator::make($request->all(),[
        'description'=>'required',
        'amount'=>'required|numeric',
        'date'=>'required',
        'notes'=>'nullable',
        ]);

        if ($validator->fails()) 
        {
            $data = [
                'message'=>'Error al validar los datos',
                'errors'=>$validator->errors(),
                'status'=>400
            ];
            return response()->json($data,400);
        }

        //** Crear el gasto
        $CarbonDate = Carbon::parse($request->date);
        $expense = Expense::create([
        'description'=>$request->description,
        'notes'=>$request->notes,
        'date'=>$CarbonDate,
        'month'=>$CarbonDate->translatedFormat('F'),
        'amount'=>$request->amount
        ]);

        if (!$expense) 
        {
            $data = [
                'message'=>'Error al crear gasto',
                'status'=>500
            ];

            return response()->json($data,500);
        }
        
        $data = [
            'expense'=> $expense,
            'status'=>201
        ];

        return response()->json($data,201);
    }

    public function show($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            $data = [
                'message' => 'No se encontro el registro',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $data = [
            'expense' => $expense,
            'status' => 200
        ];

        return response()->json($data,200);
    }
   
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense)
        {
            $data = [
                'message' => 'No se encontro el registro',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $expense->delete();

        $data = [
            'message' => "Se elimino el registro exitosamente",
            'status' => 200
        ];

        return response()->json($data,200);
    }
    
    public function update($id,Request $request)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            $data = [
                'message' => 'No se encontro el registro',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        //** validar datos
        $validator = validator::make($request->all(),[
            'description'=>'required',
            'amount'=>'required|numeric',
            'date'=>'required',
            'notes'=>'nullable',
        ]);
        if ($validator->fails()) 
        {
         $data = [
            'message'=>'Error al validar los datos',
            'errors'=>$validator->errors(),
            'status'=>400
        ];

        return response()->json($data,400);
        }

        $CarbonDate = Carbon::parse($request->date);

        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->notes = $request->notes;
        $expense->date = $CarbonDate;
        $expense->month = $CarbonDate->translatedFormat('F');


        $expense->save();

        $data = [
            'message' => 'Se actualizo el registro exitosamente',
            'expense' => $expense,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function updatePartial($id,Request $request)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            $data = [
                'message' => 'No se encontro el registro',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        //** validar datos
        $validator = validator::make($request->all(),[
            'description'=>'nullable',
            'amount'=>'nullable|numeric',
            'date'=>'nullable',
            'notes'=>'nullable',
        ]);
        
        if ($validator->fails()) 
        {
         $data = [
            'message'=>'Error al validar los datos',
            'errors'=>$validator->errors(),
            'status'=>400
        ];

        return response()->json($data,400);
        }

        if ($request->has('description')) {
            $expense->description = $request->description;
        }

        if ($request->has('amount')) {
            $expense->amount = $request->amount;
        }
        
        
        if ($request->has('notes')) {
            $expense->notes = $request->notes;
        }
        
        
        if ($request->has('date')) {
            $CarbonDate = Carbon::parse($request->date);
            $expense->date = $CarbonDate;
            $expense->month = $CarbonDate->translatedFormat('F');    
        }
        

        $expense->save();

        $data = [
            'message' => 'Se actualizo el registro exitosamente',
            'expense' => $expense,
            'status' => 200
        ];
        return response()->json($data,200);
    }

}
