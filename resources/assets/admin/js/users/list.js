$(function () {
  $('#tbl-users').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: route('users.get')
    },
    columns: [
      {data: 'id'},
      {data: 'name'},
      {data: 'email'},
      {data: 'created_at'},
      {data: 'action', name: 'action', orderable: false, searchable: false}
    ]
  });
});
