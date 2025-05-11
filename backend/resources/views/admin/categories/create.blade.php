@extends('admin.layouts.master')

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-nActive d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">News Create</li>
                        </ol>
                    </nav>
                </div>
                {{-- <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button"
                            class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated
                                link</a>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-9 mx-auto">

                    {{-- <h6 class="mb-0 text-uppercase">File input</h6>
                    <hr /> --}}
                    {{-- <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Default file input example</label>
                                <input class="form-control" type="file" id="formFile">
                            </div>

                        </div>
                    </div> --}}
                    <h6 class="mb-0 text-uppercase">News Create</h6>
                    <hr />
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.categories.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name:</label>
                                    <input type="text" id="name" class="form-control" name="name">
                                </div>
                           
                                <div class="py-4">
                                    <button type="submit" class="btn btn-info px-5 float-end">Save</button>
                                </div>
                        </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
    </div>
    <!--end page wrapper -->
@endsection
