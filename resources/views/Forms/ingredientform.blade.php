<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <form action="{{$url}}" method="post">
        @csrf
        <div class="container">
            <h1 class="text-center">{{$title}}</h1>
            <div class="form-group">
                <div id="ingredient-container">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" name="ingredient[]" required class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-orange-500 appearance-none dark:text-white dark:border-green-500 dark:focus:border-green-500 focus:outline-none focus:ring-0 focus:border-orange-500 peer" placeholder=" " value="{{ old('ingredient', $ingredient->name) }}" />
                            <label for="ingredient" class="absolute text-sm text-orange-500 dark:text-green-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Ingredient</label>
                        </div>  
                        @error('ingredient')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="button" id="add-ingredient" class="btn btn-primary">Add Ingredient</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            var currentRoute = '{{ Route::currentRouteName() }}';
    var disabledRoute = 'ingredientedit';

    if (currentRoute === disabledRoute) {
        // Disable the "Add Ingredient" button
        $('#add-ingredient').prop('disabled', true);
    }


    $(document).on('click', '#add-ingredient', function() {


                $('#ingredient-container').append(`
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" name="ingredient[]" required class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-orange-500 appearance-none dark:text-white dark:border-green-500 dark:focus:border-green-500 focus:outline-none focus:ring-0 focus:border-orange-500 peer" placeholder=" " />
            <label for="ingredient" class="absolute text-sm text-orange-500 dark:text-green-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">Ingredient</label>
            <button type="button" class="remove-ingredient absolute top-1 right-2 text-red-500 focus:outline-none">
                            ✕
                        </button>
                        </div>
                    </div>
                `);
            });
    
            $(document).on('click', '.remove-ingredient', function() {
                $(this).closest('.mb-4').remove();
            });
        });
    </script>
    

    
</body>
</html>




