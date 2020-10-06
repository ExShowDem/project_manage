<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Storage;

class ProfileService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateInformation(Request $request)
    {
        if ($request->hasFile('image')) {
            $image_name = date('d-m-Y')."-".time().".".$request->image->getClientOriginalExtension();
            Storage::putFileAs('images/avatars/', $request->file('image'), $image_name);
            $user = User::findOrFail($request->id);
            $file = $user->image;
            Storage::delete('images/avatars/'.$file, 'public');
            $user->image = $image_name;

            return User::findOrFail($request->id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $image_name,
            ]);
        } else {
            return User::findOrFail($request->id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        try
        {
            $currentUser = User::findOrFail($request->id);

            $currentPassword = $currentUser->password;

            if (Hash::check($request->current_password, $currentPassword)) 
            {
                $currentUser->password = Hash::make($request->password);
                $currentUser->setRememberToken(Str::random(60));
                $currentUser->save();

                return true;
            }
            else
            {
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
}
