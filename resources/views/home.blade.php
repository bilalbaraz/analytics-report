@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ trans('layout.city') }}</th>
                            <th scope="col">{{ trans('layout.device_category') }}</th>
                            <th scope="col">{{ trans('layout.page_view') }}</th>
                            <th scope="col">{{ trans('layout.session') }}</th>
                            <th scope="col">{{ trans('layout.visitor') }}</th>
                            <th scope="col">{{ trans('layout.visit_date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                        <tr>
                            <th scope="row">{{ $record->id }}</th>
                            <td>{{ $record->city->city_name }}</td>
                            <td>{{ $record->deviceCategory->device_category_name }}</td>
                            <td>{{ $record->pageview }}</td>
                            <td>{{ $record->session }}</td>
                            <td>{{ $record->visitor }}</td>
                            <td>{{ $record->visit_date }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
