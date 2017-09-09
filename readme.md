# Laravel-Helpers

This is a collection of usefull helper-functions for the Laravel-Framework.

## Installation

First, install the package:

``composer require brotzka/laravel-helper``

Now you should add the HelperServiceProvider to your ``config/app.php`` providers-array:

````php
    'providers' => [
        // ...
        // Other ServiceProvider 
        // ...
        Brotzka\LaravelHelper\HelperServiceProvider::class,
    ],
````

## Helpers

Here you can find a short description of all available functions. Feel free to contribute.

### Slug-Helper

This Helper simply creates a unique slug for a specific model/table. If you update a model, simple add the ID of the Model to the function and it will be ignored.

Include the SlugHelper in your file (e.g. your Controller):

````php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use App\BlogPost;
    
    use Brotzka\LaravelHelper\Helpers\SlugHelper;
    
    class YourController extends Controller {
        
        public function store(Request $request)
        {
            $post = new BlogPost();
            $post->title = $request->input('title');
            
            $slug = new SlugHelper('posts', 'mysql');
            $post->slug = $slug->createUniqueSlug($post->title);
            
            // ...
            $post->save();
        }
        
        public function update(Request $request, $id)
        {
            $post = BlogPost::findOrFail($id);
            $post->title = $request->input('title');
                    
            $slug = new SlugHelper('posts', 'mysql');
            $post->slug = $slug->createUniqueSlug($post->title, $post->id);
                 
            // ...
            $post->save();
        }
    }
````