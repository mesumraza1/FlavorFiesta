@extends('layouts.main')
@section('main-container')


  
<div class="container mx-auto">
  <div class="relative overflow-x-auto mt-16 ml-0 sm:ml-64 md:ml-44 mr-4">
      <table class="w-full text-sm sm:text-base md:text-lg text-left text-gray-500 dark:text-gray-400" id="myTable">
        <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
          <tr>
            <td>{{$category->name}}</td>
            <td>
              <a href="{{route('categoryedit',['id'=>$category->id])}}"><button class="btn btn-primary">Edit</button></a>
              <a href="{{route('categorydelete',['id'=>$category->id])}}"><button class="btn btn-danger">Delete</button></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <a href="{{route('category.index')}}">
      <button class="btn btn-primary d-inline m-2 float-right">Add</button>
    </a>
  </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready( function () {
  $('#myTable').DataTable(
  {
  "pagingType": "full_numbers",
  "lengthMenu": [
  [5, 10, 25, 50, -1],
  [5, 10, 25, 50, "All"]
  ],
  responsive: true,
  language: {
  search: "_INPUT_",
  searchPlaceholder: "Search",
  }
  }
  );
  } );

  
  </script>
  
  @endsection