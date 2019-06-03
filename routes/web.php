<?php
    use App\Content;
    use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('body');
});*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', ['middleware' => 'auth', function() {
        $books = Content::all();
        return view('contents', [
            'books' => $books
        ]);
    }]);

    Route::post('/book', ['middleware' => 'auth', function (Request $request ) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return redirect('/')
            ->withInput()
            ->withErrors($validator);
        }

        $book = new Content;
        $book->name = $request->name;
        $book->save();

        return redirect('/');
    }]);

    Route::delete('/book/{book}', ['middleware' => 'auth', function (Content $book) {
        $book->delete();

        return redirect('/');
    }]);

    Route::auth();
});