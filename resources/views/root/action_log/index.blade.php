@extends('root.partials.master')

@section('title')
    Lịch Sử
@endsection

@section('content')

<div class="row" >
	<form class="form-inline" method="GET" action="{{ url('action_log') }}">

		<div class="form-group">
			<label for="start_date">Từ ngày:</label>
			<input type="text" id="start_date" name="start_date" class="form-control">
		</div>

		<div class="form-group">
			<label for="end_date">Đến ngày:</label>
			<input type="text" id="end_date" name="end_date" class="form-control">
		</div>

		<div class="form-group">
			<label for="user">Người dùng:</label>
			<select class="form-control" id="user" name="user">
				<option value="">Chọn người dùng</option>
				@foreach ($users as $user)
				    <option value="{{$user->id}}">{{$user->name}}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="user">Chức năng:</label>
			<select class="form-control" id="model" name="model">
				<option value="">Chọn chức năng</option>
				@foreach ($models as $key => $model)
				    <option value="{{$key}}">{{$model}}</option>
				@endforeach
			</select>
		</div>

		<button type="submit" class="btn btn-default">Tìm</button>
		<a href="{{ url('action_log') }}" class="btn btn-default">Xóa</a>
	</form>
</div>

<hr />

<div class="row" >
    <table class="table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="10%">Thời gian</th>
                <th width="10%">Người dùng</th>
                <th width="10%">Chức năng</th>
                <th width="10%">Hành động</th>
                <th width="10%">Bản ghi</th>
                <th width="15%">Trạng thái trước</th>
                <th width="15%">Trạng thái sau</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
        	@foreach($logs as $log)
	            <tr>
	                <td>{!! $log['id'] !!}</td>
	                <td>@php {{ echo str_replace(' ', '<br>', $log['time']); }} @endphp</td>
	                <td>{!! $log['user_id'] !!} - {!! $log['user_name'] !!}</td>
	                <td>{!! $log['model'] !!},<br>{!! $log['table_name'] !!}</td>
	                <td>@php {{ echo ucfirst(str_replace('_', ' ', $log['method'])); }} @endphp</td>
	                <td>{!! $log['record_key_name'] !!} = {!! $log['record_id'] !!}</td>
	                @php {{ $skips = ['created_at','updated_at',/*'deleted_at',*/'created_by','updated_by','deleted_by','password']; }} @endphp
	                <td>
	                	@if ($log['params']['before'])
	                		@if ($log['method'] !== 'update_pivot')
								<table class="table">
									@foreach ($log['params']['before'] as $initLabel => $initValue)
										@if (!in_array($initLabel, $skips))
											<tr>
												<th>{{ $initLabel | translate:'vn' }}</th>
												<td style="max-width: 100px;word-wrap: break-word;">{!! $initValue !!}</td>
											</tr>
										@endif
									@endforeach
								</table>
							@else
								@foreach ($log['params']['before'] as $pivotEntry)
									<table class="table">
										@foreach ($pivotEntry as $pivotKey => $pivotValue)
											@if (!in_array($pivotKey, $skips))
												<tr>
													<th>{{ $pivotKey | translate:'vn' }}</th>
													<td style="max-width: 100px;word-wrap: break-word;">{!! $pivotValue !!}</td>
												</tr>
											@endif
										@endforeach
									</table>
								@endforeach
							@endif
						@endif
	                </td>
	                <td>
	                	@if ($log['params']['after'])
	                		@if ($log['method'] !== 'update_pivot')
								<table class="table">
									@foreach ($log['params']['after'] as $modLabel => $modValue)
										@if (!in_array($modLabel, $skips))
											<tr>
												<th>{{ $modLabel | translate:'vn' }}</th>
												<td style="max-width: 100px;word-wrap: break-word;">{!! $modValue !!}</td>
											</tr>
										@endif
									@endforeach
								</table>
							@else
								@foreach ($log['params']['after'] as $pivotEntry)
									<table class="table">
										@foreach ($pivotEntry as $pivotKey => $pivotValue)
											@if (!in_array($pivotKey, $skips))
												<tr>
													<th>{{ $pivotKey | translate:'vn' }}</th>
													<td style="max-width: 100px;word-wrap: break-word;">{!! $pivotValue !!}</td>
												</tr>
											@endif
										@endforeach
									</table>
								@endforeach
							@endif
						@endif
	                </td>
					
	            </tr>
	        @endforeach
        </tbody>
	</table>
</div>

<div class="row" >
    <div id="paginate">
        {{ $logs->links() }}
    </div>
</div>

@endsection
