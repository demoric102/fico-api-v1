<?php

namespace App\Http\Controllers\API;

use App\Fico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function singleShow($email, $array)
    {
        if (\App\User::where('email', $email)->exists()) {
            $path = storage_path() . "/data.json";
            $json_data = json_decode(file_get_contents($path), true); 
    
            $score = array();
            foreach ($json_data as $json) {
                $bvn = $json['bvn'];
                $phone = $json['phone'];
                $acc_num = $json['acc_num'];
                $names = $json['names'];
                $dob = $json['dob'];
                $gender = $json['gender'];
                
                if ($bvn != ''){
                    $score[] = \DB::connection('oracle')->select("select * from fico_score where MERGED_RUID = '$bvn'");
                    $fico = new Fico;
                    $fico->email = $email;
                    $fico->fico_id = $email;
                    $fico->save();
                }
                elseif ($phone!=''){
                    $score[] = \DB::connection('oracle')->select("select * from fico_score where MERGED_RUID = '$phone'");
                    $fico = new Fico;
                    $fico->email = $email;
                    $fico->fico_id = $email;
                    $fico->save();
                }
                elseif ($acc_num !=''){
                    $score[] = \DB::connection('oracle')->select("select * from fico_score where MERGED_RUID = '$phone'");
                    $fico = new Fico;
                    $fico->email = $email;
                    $fico->fico_id = $email;
                    $fico->save();
                }
                elseif ($names != '' && $dob != '' && $gender != ''){
                    $score[] = \DB::connection('oracle')->select("select * from fico_score where MERGED_RUID = '$phone'");
                    $fico = new Fico;
                    $fico->email = $email;
                    $fico->fico_id = $email;
                    $fico->save();
                }
                else{
                    $score[] = 'null fields';
                }
    
            }    
            return json_encode(($score),true);
        }
        else{
            return ['This Email is not registered with CRC Credit Bureau. Use the Email address you used during registration'];
        }
    }

   
}