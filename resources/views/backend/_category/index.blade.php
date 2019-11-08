@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.categories.category-management'))

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('labels.backend.categories.category-management') }}
        <small>{{ trans('labels.backend.categories.category-management-small') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"></h3>

            <div class="pull-right">
                
                <div class="pull-right mb-10">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#create-category">{{trans('strings.backend.create-new')}}</button>
                    </div>
                </div><!--pull right-->
                <div class="clearfix"></div>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="courses-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{trans('strings.backend.id')}}</th>
                            <th>{{trans('strings.backend.name')}}</th>
                            <th>{{trans('strings.backend.no-of-courses')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->courses->count() }}</td>
                                <td>
                                    {!! Form::open(['route' => ['admin.categories.destroy', $category], 'method'=>'POST', 'class' => 'form-horizontal delete-category-form']) !!} 
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-xs btn-success edit">{{trans('strings.backend.edit')}}</a>
                                        
                                        @if(!$category->courses->count())
                                        <button class="btn btn-xs btn-danger delete" {{ $category->courses->count() ? 'disabled':null }}>{{trans('strings.backend.delete')}}</button>
                                        @endif
                                        {{ METHOD_FIELD('DELETE') }}
                                        
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
    
    
    
    <!-- MODALS: Edit category -->
            
    <div class="modal fade" id="edit-category" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            
               
              
            </div>
        </div>
    </div>
    
    <!-- MODALS: Create category -->
            
    <div class="modal fade" id="create-category" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="panel-body">
                        {!! Form::open(['route' => ['admin.categories.store'], 'method'=>'POST', 'class' => 'form-horizontal create-category-form']) !!}    
                	        
                            <div class="form-group">
                                {!! Form::label("name", trans('strings.backend.name')) !!}
                                {!! Form::text("name", null, ['class'=>'form-control', 'required']) !!}
                                 @if ($errors->has('name'))
                                    <div class="text-danger"><small>{{ $errors->first('name') }}</small></div>
                                @endif
                            </div>
                            
                	        {{ csrf_field() }}
                	        
                	    {!! Form::close() !!}  
                        
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" data-dismiss="modal">{{trans('strings.backend.close')}}</button>
                	<button type="button" class="btn btn-info" id="create-category-submit">{{trans('strings.backend.add')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after-scripts')
    <script>
        $(document).ready(function($){
            
            // edit category modal
           $('.edit').click(function(e){
				$('#edit-category').modal('show', {backdrop: 'static'});
				e.preventDefault();
				href = $(this).attr('href');
				$.ajax({
					url: href,
					success: function(response)
					{
						$('#edit-category .modal-content').html(response);
					}
				});
			}); 
			
			
			// create category
    		$('#create-category-submit').click(function(){
    			$('.create-category-form').submit();
    		});
	
        });
        
        //confirm delete
        $('button.delete').on('click', function(e){
            e.preventDefault();
            
            swal({   
                title: "{{trans('strings.backend.are-you-sure')}}",
                text: "{{trans('strings.backend.unable-to-recover')}}",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{trans('strings.backend.yes-delete')}}", 
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
            }, 
            
            function(isConfirmed){   
                if(isConfirmed){
                    setTimeout(function(){
                        swal("{{trans('strings.backend.deleted')}}", "", "success");
                        $(".delete-category-form").submit();
                    }, 2000);
                } else {
                       swal("{{trans('strings.backend.cancelled')}}", "", "error"); 
                }
              
            });
        });
        
        
    </script>
    
    
    
@endsection
