<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{

    private $key = "sadsadasndbasmdbmwabkadkadbkasbdmasd2123131231232131232132321";
    private $TIME_EXPIRE = 3;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function validationJWT($request)
    {
        $jwt =$request->header('Authorization');
        $jwt = str_replace('Bearer ', '', $jwt);
 
        try {
            $data = JWT::decode($jwt, new Key($this->key, 'HS256'));
            return [
                "status"=>true,
                "data" => (array)$data
            ];
        } catch (ExpiredException $ex) {
            return [
                "status"=>false,
                "message"=>"Token anda telah kadaluarsa",
                "data" => null
            ];
        }catch(SignatureInvalidException $ex){
            return [
                "status"=>false,
                "message"=>"kode jwt anda salah",
                "data" => null
            ];
        }
    }

    public function register(Request $request)
    {
            $nama = $request->input('nama');
            $ttl = $request->input('ttl');
            $agama = $request->input('agama');
            $pekerjaan = $request->input('pekerjaan');
            $kewarganegaraan = $request->input('kewarganegaraan');
            $alamat = $request->input('alamat');
            $nohp = $request->input('nohp');
            $email = $request->input('email');
            $password = $request->input('password');
        // $hashPassword = Hash::make($password);
        $enc="";
        for($i=0;$i<strlen($password);$i++){
            $m=ord($password[$i]);
            if($m<119){
                $enc=$enc.chr($this->encRSA($m));
            }else{
                $enc=$enc.$password[$i];
 
            }
        }

        $user = Pengguna::where('email', $email)->first();
        

        if(isset($user["email"]) == $email){
            return response()->json(['message' => 'Email sudah di gunakan', 'code' => 401]);

        }else{ 
            $user = Pengguna::create([
                'nama'=> $nama,
                'ttl'=> $ttl,
                'agama'=> $agama,
                'pekerjaan'=> $pekerjaan,
                'kewarganegaraan'=> $kewarganegaraan,
                'alamat'=>$alamat,
                'nohp'=> $nohp,
                'email' => $email,
                'password' => $enc
            ]);

            if($user){
                return response()->json(['message' => 'Proses Registrasi Berhasil', 'code' => 201]);
            }else{
                return response()->json(['message' => 'Proses Registrasi gagal', 'code' => 401]);
            }
        }
    }

    private function encRSA($M){
        $data[0] =1;
        for($i=0;$i<=35;$i++){
            $rest[$i]=pow($M,1)%119;
            if($data[$i]>119){
                $data[$i+1]=$data[$i]*$rest[$i]%119;
            }else{
                $data[$i+1]=$data[$i]*$rest[$i];
            }
        }
        $get=$data[35]%119;
        return $get;
    }
 
    private function decRSA($E){
 
        $data[0] =1;
        for($i=0;$i<=11;$i++){
            $rest[$i]=pow($E,1)%119;
            if($data[$i]>119){
                $data[$i+1]=$data[$i]*$rest[$i]%119;
            }else{
                $data[$i+1]=$data[$i]*$rest[$i];
            }
        }
        $get=$data[11]%119;
        return $get;
    }


    public function login(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');

        $user = Pengguna::where('email', $email)->first();
        if(!$user) {
            return response()->json([ "status" => false,'message' => 'Gagal Masuk'],401);
        }
        $dec="";
        for($i=0;$i<strlen($user->password);$i++){
            $m=ord($user->password[$i]);
            if($m<119){
                $dec=$dec.chr($this->decRSA($m));
            }else{
                $dec=$dec.$user->password[$i];
            }
        }
          
        
        
      
        if($password == $dec) {
            $payload = array(
                "id" => $user->id,
                "nama" => $user->nama,
                "email" => $user->email,
                "exp" => (round(microtime(true)*1000)+($this->TIME_EXPIRE * 60000))
            );
            $jwt = JWT::encode($payload, $this->key, 'HS256');
            
            $user["jwt"] = $jwt;
            return response()->json([
                "status"=>true,
                "message"=>"Sukses",
                "data"=>$user
            ],201);
        }else{
            return response()->json([ "status" => false,'message' => 'Gagal Masuk'],401);
        }  
    }

    public function getdetailData(Request $request,$id){
        // return "hello";
        // get == array
        // first == object

        $validation = $this->validationJWT($request);

        if($validation["status"]){
        $pengguna = Pengguna::whereId($id)->get();
        // return $pengguna[0]["nama"];
        if($pengguna){
            return response()->json([
                "status" => true,
                "message" => "succes",
                "data"=>$pengguna
            ],201);
        }else{
            return response()->json([
                "status" => false,
                "message" => "id not found"
            ],401);
        }
        }else{
            return response()->json([
                "status"=>false,
                "message"=>$validation["message"]
            ],401);
        }      
    }

    public function getdAllData(Request $request){
        // return "hello";
        // get == array
        // first == object

        $validation = $this->validationJWT($request);

        if($validation["status"]){
        $pengguna = Pengguna::all();
        // return $pengguna[0]["nama"];
        if($pengguna){
            return response()->json([
                "status" => true,
                "message" => "succes",
                "data"=>$pengguna
            ],201);
        }else{
            return response()->json([
                "status" => false,
                "message" => "id not found"
            ],401);
        }
        }else{
            return response()->json([
                "status"=>false,
                "message"=>$validation["message"]
            ],401);
        }      
    }

    public function insert (Request $request){
    
        $validation = $this->validationJWT($request);
        
        if($validation["status"]){
            $nama = $request->input('nama');
            $ttl = $request->input('ttl');
            $agama = $request->input('agama');
            $pekerjaan = $request->input('pekerjaan');
            $kewarganegaraan = $request->input('kewarganegaraan');
            $alamat = $request->input('alamat');
            $nohp = $request->input('nohp');
            $email = $request->input('email');
            $password = $request->input('password');
    
            //   $pengguna = Pengguna::whereId($id)->get();
            $pengguna= Pengguna::create([
                'nama'=> $nama,
                'ttl'=> $ttl,
                'agama'=> $agama,
                'pekerjaan'=> $pekerjaan,
                'kewarganegaraan'=> $kewarganegaraan,
                'alamat'=>$alamat,
                'nohp'=> $nohp,
                'password'=> $password,
                'email'=> $email,
            ]);
    
    
            if($pengguna){
                return response()->json([
                    "status" => true,
                    "message" => "succes"
                ],201);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "id not found"
                ],401);
            }
        }else{
            return response()->json([
                "status"=>false,
                "message"=>$validation["message"]
            ],401);
        }
    }

    public function updated(Request $request,$id){

        $validation = $this->validationJWT($request);

        if($validation["status"]){
            $nama = $request->input('nama');
            $ttl = $request->input('ttl');
            $agama = $request->input('agama');
            $pekerjaan = $request->input('pekerjaan');
            $kewarganegaraan = $request->input('kewarganegaraan');
            $alamat = $request->input('alamat');
            $nohp = $request->input('nohp');
            $email = $request->input('email');
            $password = $request->input('password');
            //   $pengguna = Pengguna::whereId($id)->get();
            $pengguna= Pengguna::whereId($id)->update([
                'nama'=> $nama,
                'ttl'=> $ttl,
                'agama'=> $agama,
                'pekerjaan'=> $pekerjaan,
                'kewarganegaraan'=> $kewarganegaraan,
                'alamat'=>$alamat,
                'nohp'=> $nohp,
                'password'=> $password,
                'email'=> $email,
            ]);
    
    
            if($pengguna){
                return response()->json([
                    "status" => true,
                    "message" => "succes"
                ],201);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "id not found"
                ],401);
            }
        }else{
            return response()->json([
                "status"=>false,
                "message"=>$validation["message"]
            ],401);
        }
    }

    public function delete(Request $request,$id){
        //   $pengguna = Pengguna::whereId($id)->get();
        $validation = $this->validationJWT($request,$id );

        if($validation["status"]){
            $pengguna= Pengguna::whereId($id)->delete();
            if($pengguna){
                return response()->json([
                    "status" => true,
                    "message" => "succes"
                ],201);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "id not found"
                ],401);
            }
        }else{
            return response()->json([
                "status"=>false,
                "message"=>$validation["message"]
            ],401);
        }   
    }

}
