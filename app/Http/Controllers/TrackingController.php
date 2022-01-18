<?php

namespace App\Http\Controllers;
use App\Models\UserForm;
use App\Models\UserFormData;
use Auth;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getFormData(Request $request)
    {
        $user_form = UserForm::where('tracking_key','=',$request['special_tfw_token'])->first();
        if ($user_form) {
            $request_data = $request->all();
            unset($request_data['special_tfw_token']);
            $contact_name = $contact_phone = $email = null;

            foreach ($request_data as $key => $value) {
                $is_value_string = is_string($value);
                if ($key == 'name' || strpos($key,'name') !== false) {
                    $contact_name = $value;
                }
                else{
                    if (!$contact_phone && $is_value_string) {
                        if ($key == 'phone' || strpos($key,'phone') !== false) {
                            $contact_phone = $value;
                        }
                        else{
                            preg_match_all('/[0-9]{3}[\-][0-9]{6}|[0-9]{3}[\s][0-9]{6}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{9}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}/', $value, $matches);
                            if ($matches && isset($matches['0']) && $matches['0']) {
                                $contact_phone = $matches['0'];
                            }
                        }
                    }
                }

                if ($is_value_string && filter_var($value,FILTER_VALIDATE_EMAIL)) {
                    $email = $value;
                }
            }

            if (!$contact_name) {
                $contact_name = 'New Lead';
            }

            $model = new UserFormData();
            $model->user_id = $user_form->user_id;
            $model->user_form_id = $user_form->user_form_id;
            $model->contact_name = $contact_name;
            $model->contact_phone = $contact_phone;
            $model->contact_response = json_encode($request_data);
            $model->is_converted = '0';
            $model->url = $user_form->website;
            $model->email = $email;
            $model->save();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}
