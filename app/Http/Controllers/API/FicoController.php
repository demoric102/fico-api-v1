<?php
namespace App\Http\Controllers\API;

ini_set('max_execution_time', 300);

use App\Fico;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class FicoController extends Controller
{
    public function postBatchShow(Request $request)
    {
        if (\App\User::where('email', $request->email)->exists()) {
            $user = \App\User::where('email', '=', $request->email)->first();
            if ($user->activate === "inactive"){
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
                        $fico->email = $request->email;
                        $fico->fico_id = $request->email;
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
            elseif ($user->activate === "active"){
                $payload = $request->json_array;
                $json_data = json_decode($payload, true);
                $score = array();
                $data = array();
                $dataArray = array();
                foreach ($json_data as $json) {
                    $bvn = $json['bvn'];
                    $phone = $json['phone'];
                    $acc_num = $json['acc_num'];
                    $names = $json['names'];
                    $dob = $json['dob'];
                    $gender = $json['gender'];

                    if ($bvn != ''){
                        $data = \DB::connection('oracle')->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where bvn = '$bvn'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, reasoncode1,reasoncode2,reasoncode3,reasoncode4, systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array("errMsg"=>"This result returned null");
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'BVN '.$bvn;
                            $fico->status = 'miss';
                            $fico->save();
                        }
                        elseif (!empty($data)){
                            $dataArray[] = json_decode(json_encode(reset($data)), true);
                            \DB::table('users')->where('email', $request->email)->increment('hits');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'BVN '.$bvn;
                            $fico->status = 'hit';
                            $fico->save();
                        }
                    }
                    elseif ($phone!=''){
                        $data = \DB::connection('oracle')->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where phone = '$phone'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, reasoncode1,reasoncode2,reasoncode3,reasoncode4, systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array("errMsg"=>"This result returned null");
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Phone Number '.$phone;
                            $fico->status = 'miss';
                            $fico->save();
                        }
                        elseif (!empty($data)){
                            $dataArray[] = json_decode(json_encode(reset($data)), true);
                            \DB::table('users')->where('email', $request->email)->increment('hits');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Phone Number '.$phone;
                            $fico->status = 'hit';
                            $fico->save();
                        }
                    }
                    elseif ($acc_num !=''){
                        $data = \DB::connection('oracle')->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where structure_id = '$acc_num'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, reasoncode1,reasoncode2,reasoncode3,reasoncode4, systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array("errMsg"=>"This result returned null");
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Account Number '.$acc_num;
                            $fico->status = 'miss';
                            $fico->save();
                        }
                        elseif (!empty($data)){
                            $dataArray[] = json_decode(json_encode(reset($data)), true);
                            \DB::table('users')->where('email', $request->email)->increment('hits');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Account Number '.$acc_num;
                            $fico->status = 'hit';
                            $fico->save();
                        }
                    }
                    elseif ($names != '' && $dob != '' && $gender != ''){
                        $data = \DB::connection('oracle')->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where customer_name = '$names' && date_of_birth = '$dob' && gender = '$gender'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, reasoncode1,reasoncode2,reasoncode3,reasoncode4, systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array("errMsg"=>"This result returned null");
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Names: '.$names.', DOB: '.$dob.', Gender: '.$gender;
                            $fico->status = 'miss';
                            $fico->save();
                        }
                        elseif (!empty($data)){
                            $dataArray[] = json_decode(json_encode(reset($data)), true);
                            \DB::table('users')->where('email', $request->email)->increment('hits');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Names: '.$names.', DOB: '.$dob.', Gender: '.$gender;
                            $fico->status = 'hit';
                            $fico->save();
                        }
                    }
                    else{
                        $dataArray[] = response()->json(array("errMsg"=>"null or empty fields"), 200);
                    }
                }
                return response()->json($dataArray, 200);
            }
            else{
                return response()->json(array("errMsg"=>"There is an error with validating your activation status. Please contact the customer service"), 200);
            }
        }
        else{
            return response()->json(array("errMsg"=>"This Email is not registered with CRC Credit Bureau. Use the Email address you used during registration"), 200);
        } 
        
    }
   
}