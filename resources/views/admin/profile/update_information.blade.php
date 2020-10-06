<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-dark">
            <span class="caption-subject bold uppercase">Thông tin cá nhân</span>
        </div>
    </div>
    <div class="portlet-body form">
        <form enctype="multipart/form-data"
              method="post"
              action="{{ route('admin.profile.updateInformation') }}"
              id="formUpdateInformation">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <div class="form-body">
                <div class="form-group">
                    <label>Tên</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Tên"
                           value="{{ Auth::user()->name }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ Auth::user()->email }}" disabled>
                </div>
                <div class="form-group">
                    <label>Ảnh</label>
                    <div class="text-left">
                        <img src="{{ Auth::user()->image ? 'assets/root/images/'.Auth::user()->image : 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image' }}" alt="" class="img-thumbnail" id="imgupdate" name="imgupdate">
                    </div>
                    <input type="file" name="image" class="form-control" placeholder="Ảnh">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn green">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
