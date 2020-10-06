<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Profile\ChangePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateInformationRequest;
use App\Services\ProfileService;

class ProfileController extends BaseController
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        parent::__construct();
        $this->profileService = $profileService;
    }

    public function index()
    {
        return view('admin.profile.index');
    }

    public function updateInformation(UpdateInformationRequest $request)
    {
        $this->profileService->updateInformation($request);

        $status = 'Cập nhật thông tin cá nhân thành công!';

        return redirect(action('Admin\ProfileController@index'))
            ->with('status', $status);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $status = 'Đổi mật khẩu thành công!';

        $error = 'Mật khẩu hiện tại không chính xác.';

        if ($this->profileService->changePassword($request)) {
            return redirect(action('Admin\ProfileController@index'))
                ->with('status', $status);
        } else {
            return redirect(action('Admin\ProfileController@index'))
                ->with('error', $error);
        }
    }
}
