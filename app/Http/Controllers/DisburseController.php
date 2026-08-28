<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisburseController extends Controller
{
    // Render initial page with agent list for dropdown
    public function index()
    {
        $data['agent_list'] = DB::table('agents')
            ->select('id', 'name')
            ->where('status', 1)
            ->get();

        return view('admin.case.view', $data);
    }

    // Server-side AJAX handler for DataTables
    public function getAllDisburse(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        // Custom filter values from DataTables AJAX call
        $agent_id  = $request->input('agent_id');
        $bank_name = $request->input('bank_name');
        $services  = $request->input('services');
        $from_date = $request->input('from_date');
        $to_date   = $request->input('to_date');

        // Base Query
        $query = DB::table('disburse as dis')
            ->leftJoin('agents as agent', 'agent.id', '=', 'dis.agent_id')
            ->select('dis.*', 'agent.name as agent_name');

        // Apply filters dynamically using Laravel's when()
        $query->when($agent_id, function ($q) use ($agent_id) {
            return $q->where('dis.agent_id', $agent_id);
        });

        $query->when($bank_name, function ($q) use ($bank_name) {
            return $q->where('dis.bank_name', $bank_name);
        });

        $query->when($services, function ($q) use ($services) {
            return $q->where('dis.services', $services);
        });

        $query->when($from_date && $to_date, function ($q) use ($from_date, $to_date) {
            return $q->whereBetween('dis.disburse_date', [$from_date, $to_date]);
        });

        // Get total filtered records BEFORE pagination
        $recordsFiltered = $query->count();

        // Fetch paginated results
        $data = $query->orderBy('dis.case_id', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        // Get total un-filtered records count
        $recordsTotal = DB::table('disburse')->count();

        // Send JSON response structured for DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
}