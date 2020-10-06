<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-dark">
            <span class="caption-subject bold uppercase">Đổi mật khẩu</span>
        </div>
    </div>
    <div class="portlet-body form">
        <form method="post"
              action="{{ route('admin.profile.changePassword') }}"
              id="formChangePassword">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <div class="form-body">
                <div class="form-group">
                    <label>Mật khẩu hiện tại</label>
                    <input type="password"
                           class="form-control"
                           placeholder="Mật khẩu hiện tại"
                           name="current_password">
                </div>
                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <input type="password"
                           class="form-control"
                           placeholder="Mật khẩu mới"
                           name="password">
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu mới</label>
                    <input type="password"
                           class="form-control"
                           placeholder="Xác nhận mật khẩu mới"
                           name="password_confirmation">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn green">Đổi mật khẩu</button>
            </div>
        </form>
    </div>
</div>
