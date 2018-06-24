<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Riwayat;
    use Illuminate\Support\Facades\DB;

    class RiwayatController extends Controller{
    	public function addRiwayat(Request $request){
            $id_user	= $request->input('id_user');
            $total 		= $request->input('total');

            $add = Riwayat::create([
                'id_user'=> $id_user,
                'total' => $total
            ]);

            if($add){
            	$res['success'] = true;
                $res['message'] = 'Pembayaran Sukses';
                return response($res);
            }
        }

        public function getUserRiwayat(Request $request){
        	$id_user = $request->input('id_user');
        	$riwayat = Riwayat::where('id_user', $id_user)->get();
        	if($riwayat){
        		$res['success'] = true;
                $res['message'] = 'request complete';
                $res['riwayat'] = $riwayat;
                return response($res);
        	}
        }
    }