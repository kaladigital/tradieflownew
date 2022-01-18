<?php

namespace App\Http\Controllers;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Http\Requests\ClientRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active_subscription');
    }

    public function index(Request $request)
    {
        $auth_user = Auth::user();
        return view('quote.index',compact(
            'auth_user'
        ));
    }

    public function create(Request $request)
    {
        $auth_user = Auth::user();
        $country_id = Helper::getCountryList();
        return view('quote.create',compact(
            'auth_user',
            'country_id'
        ));
    }

    public function uploadCompanyLogo(Request $request)
    {
        $auth_user = request()->user();
        try{
            if ($request->hasFile('qqfile')) {
                $file = $request->file('qqfile');
                $file_ext = $file->getClientOriginalExtension();
                if ($file->getSize() < 2 * 1024 * 1024) {
                    if (!in_array($file_ext,Constant::GET_ALLOWED_IMAGE_EXTENSIONS())) {
                        return response()->json([
                            'status' => false,
                            'error' => 'File not allowed'
                        ]);
                    }

                    $file_name = $auth_user->user_id.uniqid().'.'.$file_ext;
                    Storage::disk('quote_temp')->put($file_name, File::get($file));
                    return response()->json([
                        'status' => true,
                        'file_name' => $file_name
                    ]);
                }
            }
        }
        catch (\Exception $e) {

        }

        return response()->json([
            'status' => false,
            'error' => 'Unable to upload file, please upload max 2mb image'
        ]);
    }
}
