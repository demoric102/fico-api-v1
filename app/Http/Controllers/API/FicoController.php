<?php
namespace App\Http\Controllers\API;

ini_set('max_execution_time', 300);

use App\Fico;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class FicoController extends Controller
{
    private $connection;

    public function __construct()
    {
        $this->connection = \DB::connection('oracle');
   
    }
    public function postBatchShow(Request $request)
    {
        
        if (\App\User::where('email', $request->email)->exists()) {
            $user = \App\User::where('email', '=', $request->email)->first();
            if ($user->activate === "inactive"){
                return response()->json(array("errMsg"=>"User Inactive. Please contact the customer service"), 403);
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
                        $score[] = $this->connection->select("select * from fico_score where MERGED_RUID = '$bvn'");
                        $fico = new Fico;
                        $fico->email = $request->email;
                        $fico->fico_id = $request->email;
                        $fico->save();
                    }
                    elseif ($phone!=''){
                        $score[] = $this->connection->select("select * from fico_score where MERGED_RUID = '$phone'");
                        $fico = new Fico;
                        $fico->email = $email;
                        $fico->fico_id = $email;
                        $fico->save();
                    }
                    elseif ($acc_num !=''){
                        $score[] = $this->connection->select("select * from fico_score where MERGED_RUID = '$phone'");
                        $fico = new Fico;
                        $fico->email = $email;
                        $fico->fico_id = $email;
                        $fico->save();
                    }
                    elseif ($names != '' && $dob != '' && $gender != ''){
                        $score[] = $this->connection->select("select * from fico_score where MERGED_RUID = '$phone'");
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
                        $this->connection->getDatabaseName();
                        $data = $this->connection->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where bvn = '$bvn'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, 
                        code_reason(reasoncode1) reasoncode1,code_reason(reasoncode2) reasoncode2,
                        code_reason(reasoncode3) reasoncode3,code_reason(reasoncode4) reasoncode4, 
                        systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data) && $names == '' && $dob == '' && $gender == ''){
                            $dataArray[] = array(
                                "customer_name"=>"null",
                                "status"=>"This result returned null",
                                "email"=> $request->email,
                                "response"=>"This BVN $bvn returned NO HIT"
                            );
                            \DB::table('users')->where('email', $request->email)->increment('misses');
                            $fico = new Fico;
                            $fico->email = $request->email;
                            $fico->fico_id = 'Names: '.$names.', DOB: '.$dob.', Gender: '.$gender;
                            $fico->status = 'miss';
                            $fico->save();
                        }
                        elseif (empty($data) && $names != '' && $dob != '' && $gender != ''){
                            $name = explode(" ", $names);
                            if (count($name) == 3){
                                $name_clause = " UPPER(customer_name) LIKE upper('%$name[0]%') and UPPER(customer_name) LIKE upper('%$name[1]%') and UPPER(customer_name) LIKE upper('%$name[2]%') ";

                            }
                            elseif (count($name) == 2){
                                $name_clause = " UPPER(customer_name) LIKE upper('%$name[0]%') and UPPER(customer_name) LIKE upper('%$name[1]%') ";
                            }
                            $name_query = "select  a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, 
                            b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                            from
                            (
                            select ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                            where  " . $name_clause . "
                            and date_of_birth =  to_date('$dob', 'dd-MON-yyyy')
                            and gender = '$gender'
                            ) A,
                            (
                            select merged_ruid, score, 
                            code_reason(reasoncode1) reasoncode1,code_reason(reasoncode2) reasoncode2,
                            code_reason(reasoncode3) reasoncode3,code_reason(reasoncode4) reasoncode4, 
                            systemdate from fico_score
                            )b
                            WHERE A.RUID = B.MERGED_RUID";
                            $data = $this->connection->select($name_query);

                            if (empty($data)){
                                $dataArray[] = array(
                                    "customer_name"=>"null",
                                    "status"=>"This result returned null",
                                    "email"=> $request->email,
                                    "response"=>"These  '.$names.', DOB: '.$dob.', Gender: '.$gender.' returned NO HIT"
                                );
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
                        $data = $this->connection->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where phone = '$phone'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, 
                        code_reason(reasoncode1) reasoncode1,code_reason(reasoncode2) reasoncode2,
                        code_reason(reasoncode3) reasoncode3,code_reason(reasoncode4) reasoncode4, 
                        systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array(
                                "customer_name"=>"null",
                                "status"=>"This result returned null",
                                "email"=> $request->email,
                                "response"=>"This '.$phone.' returned NO HIT"
                            );
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
                        $data = $this->connection->select("select distinct a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                            (
                                select distinct ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                                where structure_id = '$acc_num'
                            ) a
                        inner join 
                            (
                                select merged_ruid, score, 
                        code_reason(reasoncode1) reasoncode1,code_reason(reasoncode2) reasoncode2,
                        code_reason(reasoncode3) reasoncode3,code_reason(reasoncode4) reasoncode4, 
                        systemdate from fico_score
                            )b
                        on a.ruid = b.merged_ruid
                        order by b.systemdate desc");
                        if (empty($data)){
                            $dataArray[] = array(
                                "customer_name"=>"null",
                                "status"=>"This result returned null",
                                "email"=> $request->email,
                                "response"=>"This '.$acc_num.' returned NO HIT"
                            );
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
                        $name = explode(" ", $names);
                        if (count($name) == 3){
                            $name_clause = " UPPER(customer_name) LIKE upper('%$name[0]%') and UPPER(customer_name) LIKE upper('%$name[1]%') and UPPER(customer_name) LIKE upper('%$name[2]%') ";

                        }
                        elseif (count($name) == 2){
                            $name_clause = " UPPER(customer_name) LIKE upper('%$name[0]%') and UPPER(customer_name) LIKE upper('%$name[1]%') ";
                        }
                        $name_query = "select  a.customer_name,a.bvn,a.date_of_birth,a.gender,a.phone, a.structure_id, 
                        b.score, b.reasoncode1,b.reasoncode2,b.reasoncode3,b.reasoncode4, b.systemdate
                        from
                        (
                        select ruid, customer_name,bvn,date_of_birth,gender,phone,structure_id from aa_fico_score_dtls
                        where  " . $name_clause . "
                        and date_of_birth =  to_date('$dob', 'dd-MON-yyyy')
                        and gender = '$gender'
                        ) A,
                        (
                        select merged_ruid, score, 
                        code_reason(reasoncode1) reasoncode1,code_reason(reasoncode2) reasoncode2,
                        code_reason(reasoncode3) reasoncode3,code_reason(reasoncode4) reasoncode4, 
                        systemdate from fico_score
                        )b
                        WHERE A.RUID = B.MERGED_RUID";
                        $data = $this->connection->select($name_query);

                        if (empty($data)){
                            $dataArray[] = array(
                                "customer_name"=>"null",
                                "status"=>"This result returned null",
                                "email"=> $request->email,
                                "response"=>"These  '.$names.', DOB: '.$dob.', Gender: '.$gender.' returned NO HIT"
                            );
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