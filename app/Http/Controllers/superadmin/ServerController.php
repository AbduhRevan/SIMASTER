<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\RakServer;
use App\Models\Bidang;
use App\Models\Satker;
use App\Models\Website;

class ServerController extends Controller
{
    public function index() {
        $servers = Server::with(['rak','bidang','satker','website'])->get();
        $raks = RakServer::all();
        $bidangs = Bidang::all();
        $satkers = Satker::all();
        $websites = Website::all();

        $total = $servers->count();
        $aktif = $servers->where('power_status','ON')->count();
        $maintenance = $servers->where('power_status','STANDBY')->count();
        $tidakAktif = $servers->where('power_status','OFF')->count();

        return view('superadmin.server',compact('servers','total','aktif','maintenance','tidakAktif','raks','bidangs','satkers','websites'));
    }

    public function show($id){
        $server = Server::with(['rak','bidang','satker','website'])->findOrFail($id);
        return view('superadmin.server_detail',compact('server'));
    }

    public function store(Request $request){
        $request->validate([
            'nama_server'=>'required|unique:server,nama_server',
            'rak_id'=>'nullable|exists:rak_server,rak_id',
            'bidang_id'=>'nullable|exists:bidang,bidang_id',
            'satker_id'=>'nullable|exists:satuan_kerja,satker_id',
            'website_id'=>'nullable|exists:website,website_id',
        ]);

        Server::create($request->all());
        return redirect()->route('superadmin.server.index')->with('success','Server berhasil ditambahkan!');
    }
}
