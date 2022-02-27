<?php

namespace App\Http\Controllers;

use App\Models\Daftarpo;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;

class DaftarpoController extends Controller
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

    public function getdetailData(Request $request,$id){
        // return "hello";
        // get == array
        // first == object
        $validation = $this->validationJWT($request);

        if($validation["status"]){
        $daftarpo = Daftarpo::whereId($id)->get();
        // return $pengguna[0]["nama"];
        if($daftarpo){
            return response()->json([
                "status" => true,
                "message" => "succes",
                "data"=>$daftarpo
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
        $daftarpo = Daftarpo::all();
        // return $pengguna[0]["nama"];
        if($daftarpo){
            return response()->json([
                "status" => true,
                "message" => "succes",
                "data"=>$daftarpo
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

    public function getAllbyId(Request $request){
        // return "hello";
        // get == array
        // first == object
        $validation = $this->validationJWT($request);

        

        if($validation["status"]){
        $daftarpo = Daftarpo::whereIdUser($validation["data"]["id"])->get();
        // return $pengguna[0]["nama"];
        if($daftarpo){
            return response()->json([
                "status" => true,
                "message" => "succes",
                "data"=>$daftarpo
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
            $id_user = $validation["data"]["id"];
            $nama = $request->input('nama');
            $ttl = $request->input('ttl');
            $jekel = $request->input('jekel');
            $tb = $request->input('tb');
            $rambut = $request->input('rambut');
            $kulit = $request->input('kulit');
            $mata = $request->input('mata');
            $cirik = $request->input('cirik');
            $tglhilang = $request->input('tglhilang');
            $infot = $request->input('infot');
            $photo = $request->file('photo');


            // return $photo;
            // if($photo){
            //     $gambar=time() . $photo->getClientOriginalName();
            //     $photo->move('images',$gambar);
            // }

            return $infot;
    
            //   $pengguna = Pengguna::whereId($id)->get();
            // $daftarpo= Daftarpo::create([
            //     'id_user'=> $id_user,
            //     'nama'=> $nama,
            //     'ttl'=> $ttl,
            //     'jekel'=> $jekel,
            //     'tb'=> $tb,
            //     'rambut'=> $rambut,
            //     'kulit'=> $kulit,
            //     'mata'=> $mata,
            //     'cirik'=> $cirik,
            //     'tglhilang'=> $tglhilang,
            //     'infot'=> $infot,
            //     'photo'=> 'lll',
            // ]);
            // if($daftarpo){
            //     return response()->json([
            //         "status" => true,
            //         "message" => "succes"
            //     ],201);
            // }else{
            //     return response()->json([
            //         "status" => false,
            //         "message" => "id not found"
            //     ],401);
            // }
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
            $id_user = $request->input('id_user');
            $nama = $request->input('nama');
            $ttl = $request->input('ttl');
            $jekel = $request->input('jekel');
            $tb = $request->input('tb');
            $rambut = $request->input('rambut');
            $kulit = $request->input('kulit');
            $mata = $request->input('mata');
            $cirik = $request->input('cirik');
            $tglhilang   = $request->input('tglhilang');
            $infot = $request->input('infot');
            $photo = $request->file('photo');

            if($photo){
                $gambar=time() .$photo->getClientOriginalName();
                $photo->move('images',$gambar);
            }
            //   $pengguna = Pengguna::whereId($id)->get();
            $daftarpo= Daftarpo::whereId($id)->update([
                'id_user'=> $id_user,
                'nama'=> $nama,
                'ttl'=> $ttl,
                'jekel'=> $jekel,
                'tb'=> $tb,
                'rambut'=> $rambut,
                'kulit'=> $kulit,
                'mata'=> $mata,
                'cirik'=> $cirik,
                'tglhilang'=> $tglhilang,
                'infot'=> $infot,
                'photo'=> $gambar,
            ]);
            if($daftarpo){
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

        $validation = $this->validationJWT($request);

        if($validation["status"]){
            $daftarpo= Daftarpo::whereId($id)->delete();
            $data = Daftarpo::all();
            if($daftarpo){
                return response()->json([
                    "status" => true,
                    "message" => "succes",
                    "data" => $data,
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