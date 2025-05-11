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
                            <li class="breadcrumb-item active" aria-current="page">News Edit</li>
                        </ol>
                    </nav>
                </div>
      
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-9 mx-auto">

                    {{-- <h6 class="mb-0 text-uppercase">Input Mask</h6> --}}
                    <h6 class="mb-0 text-uppercase">News Edit</h6>

                    <hr />
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.categories.update', $category->slug) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Name:</label>
                                    <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                                </div>
                            
                                {{-- <div class="mb-3">
										<h6 class="mb-0 text-uppercase">Form text editor</h6>
										<hr/>
										<div id="editor" name="">

										  </div> --}}
                                <div class="py-4">
                                    <button type="submit" class="btn btn-info px-5 float-end">Update</button>
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
