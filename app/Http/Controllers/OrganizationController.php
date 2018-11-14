<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Organization;
use Auth;

class OrganizationController extends Controller
{
  public function __construct()
{
  // auth : unutk mengecek auth
    $this->middleware('auth');
}

public function index()
  {
    $data = organization::all();

      if(count($data) > 0){ //mengecek apakah data kosong atau tidak
          $res['message'] = "Success!";
          $res['values'] = $data;
          return response($res);
      }
      else{
          $res['message'] = "Empty!";
          return response($res);
      }
  }

  // fungsi untuk melihat detail Organization
  public function show($id)
  {

    $user = Auth::user();

    $data = $organization = organization::findOrFail($id);
    if ($organization->user_id == $user->id || $user->role == 1) {
      if($data){ //mengecek apakah data kosong atau tidak
        $res['message'] = "Success!";
        $res['values'] = $data;
        return response($res);
      }
      else{
        $res['message'] = "Empty!";
        return response($res);
      }
    }

    $res['message'] = "access denied!";
    return response($res);

  }

// create
// 2.store data
public function store(Request $request)
{

  $user_id = Auth::id();
  // contoh penggunaan validate dimana :
  // 1. value name required
  $this->validate($request, [
    'name' => 'required',
    'phone' => 'required|numeric',
    'company_address' => 'required',
    'zipcode' => 'required|numeric',
  ]);

  $organization = new organization;

  if ($request->file('logo') == "") {
      // code...
    } else {
      // menyimpan nilai image
      $file = $request->file('logo');
      // mengambil nama file
      $fileName = $file->getClientOriginalName();
      // menyimpan file image kedalam folder "img"
      $request->file('logo')->move("logo/",$fileName);
      // menyimpan ke dalam database nama file dari image
      $organization->logo = $fileName;
    }

  $organization->name = $request->name;
  $organization->user_id = $user_id;
  $organization->phone = $request->phone;
  $organization->company_address = $request->company_address;
  $organization->zipcode = $request->zipcode;
  $organization->save();


  if($organization->save()){
      $res['message'] = "Success Created Organization!";
      $res['value'] = "$organization";
      return response($res);
  }
}

// 1. store data update
      public function update(Request $request, $id)
      {

        $organization = organization::findOrFail($id);

        $this->validate($request, [
          'name' => 'required',
          'phone' => 'required|numeric',
          'company_address' => 'required',
          'zipcode' => 'required|numeric',
        ]);

        if ($request->file('logo') == "") {
            // code...
          } else {
            // menyimpan nilai image
            $file = $request->file('logo');
            // mengambil nama file
            $fileName = $file->getClientOriginalName();
            // menyimpan file image kedalam folder "img"
            $request->file('logo')->move("logo/",$fileName);
            // menyimpan ke dalam database nama file dari image
            $organization->logo = $fileName;
          }

        $organization->name = $request->name;
        // dd($organization->name);
        $organization->phone = $request->phone;
        $organization->company_address = $request->company_address;
        $organization->zipcode = $request->zipcode;
        $organization->save();

        if($organization->save()){
            $res['message'] = "Success Updated Organization!";
            $res['value'] = "$organization";
            return response($res);
        }
      }

      // delete
      public function delete($id)
      {
        $organization = organization::find($id);

        if($organization->delete()){
            $res['message'] = "Success Deleted Organization!";
            $res['value'] = "$organization";
            return response($res);
        }
        else{
            $res['message'] = "Failed!";
            return response($res);
        }
      }
}
